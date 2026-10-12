<?php
declare(strict_types=1);

/*
 * Mod Decor simple RFQ email handler.
 * Sends submissions to info@moddecor.com.ng.
 * For better delivery, ask your hosting provider for SMTP settings and use
 * authenticated SMTP if PHP mail() is restricted or messages do not arrive.
 */

const RECIPIENT_EMAIL = 'info@moddecor.com.ng';
const MAX_FILE_SIZE = 8 * 1024 * 1024; // 8 MB each
const MAX_FILES = 5;
const MAX_TOTAL_SIZE = 35 * 1024 * 1024; // stay below common PHP post limits

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: rfq.php');
    exit;
}

function fail_form(): never {
    header('Location: rfq.php?error=1');
    exit;
}

function clean_field(string $key, int $max): string {
    $value = trim((string)($_POST[$key] ?? ''));
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $max);
    }
    return substr($value, 0, $max);
}

/* Reject bots that fill the hidden field. */
if (!empty($_POST['website'])) {
    header('Location: rfq.php?sent=1');
    exit;
}

$name = clean_field('full_name', 120);
$company = clean_field('company', 120);
$email = clean_field('email', 180);
$phone = clean_field('phone', 40);
$location = clean_field('location', 180);
$product = clean_field('product', 120);
$quantity = clean_field('quantity', 100);
$details = clean_field('details', 5000);

$allowedProducts = [
    'Window Blinds', 'Decorative Tiles', 'Flower Ceiling Cornice',
    'Floor Skirting', 'Corners', 'Interior Curtains', 'Interior Design',
    'Home Decoration', 'Multiple Products / Other'
];

if (
    $name === '' || $email === '' || $phone === '' || $location === '' ||
    $product === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !in_array($product, $allowedProducts, true)
) {
    fail_form();
}

/* Validate and read uploaded attachments. */
$attachments = [];
$totalSize = 0;
$allowedMimeByExt = [
    'pdf' => ['application/pdf'],
    'jpg' => ['image/jpeg'],
    'jpeg' => ['image/jpeg'],
    'png' => ['image/png'],
    'webp' => ['image/webp'],
    'doc' => ['application/msword', 'application/octet-stream', 'application/CDFV2'],
    'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/octet-stream']
];

if (isset($_FILES['documents']) && is_array($_FILES['documents']['name'])) {
    $count = count($_FILES['documents']['name']);
    if ($count > MAX_FILES) {
        fail_form();
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);

    for ($i = 0; $i < $count; $i++) {
        $error = $_FILES['documents']['error'][$i] ?? UPLOAD_ERR_NO_FILE;
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($error !== UPLOAD_ERR_OK) {
            fail_form();
        }

        $tmp = $_FILES['documents']['tmp_name'][$i] ?? '';
        $size = (int)($_FILES['documents']['size'][$i] ?? 0);
        $original = basename((string)($_FILES['documents']['name'][$i] ?? 'document'));
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));

        if (!is_uploaded_file($tmp) || $size < 1 || $size > MAX_FILE_SIZE || !isset($allowedMimeByExt[$ext])) {
            fail_form();
        }

        $mime = $finfo->file($tmp) ?: 'application/octet-stream';
        if (!in_array($mime, $allowedMimeByExt[$ext], true)) {
            fail_form();
        }

        $totalSize += $size;
        if ($totalSize > MAX_TOTAL_SIZE) {
            fail_form();
        }

        $contents = file_get_contents($tmp);
        if ($contents === false) {
            fail_form();
        }

        $attachments[] = [
            'name' => preg_replace('/[^A-Za-z0-9._ -]/', '_', $original),
            'mime' => $mime,
            'contents' => $contents
        ];
    }
}

/* Construct a multipart email with attachment parts. */
$reference = 'MD-RFQ-' . date('Ymd-His') . '-' . strtoupper(bin2hex(random_bytes(2)));
$boundary = '=_ModDecor_' . bin2hex(random_bytes(18));
$fromAddress = RECIPIENT_EMAIL; // use the domain mailbox as From to reduce spoofing problems

$bodyText = "NEW MOD DECOR QUOTATION REQUEST\n"
    . "Reference: {$reference}\n\n"
    . "CUSTOMER DETAILS\n"
    . "Name: {$name}\n"
    . "Company: " . ($company !== '' ? $company : 'Not provided') . "\n"
    . "Email: {$email}\n"
    . "Phone / WhatsApp: {$phone}\n"
    . "Project location: {$location}\n\n"
    . "PROJECT DETAILS\n"
    . "Product / service: {$product}\n"
    . "Estimated quantity: " . ($quantity !== '' ? $quantity : 'Not provided') . "\n"
    . "Additional details:\n" . ($details !== '' ? $details : 'Not provided') . "\n\n"
    . "Attachments: " . count($attachments) . "\n"
    . "Please reply directly to the customer's email address: {$email}\n";

$subject = 'New Mod Decor RFQ - ' . $reference;
$encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

$headers = [];
$headers[] = 'From: Mod Decor Website <' . $fromAddress . '>';
$headers[] = 'Reply-To: ' . $email;
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: multipart/mixed; boundary="' . $boundary . '"';

$message = "--{$boundary}\r\n";
$message .= "Content-Type: text/plain; charset=UTF-8\r\n";
$message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$message .= $bodyText . "\r\n";

foreach ($attachments as $attachment) {
    $message .= "--{$boundary}\r\n";
    $message .= 'Content-Type: ' . $attachment['mime'] . '; name="' . $attachment['name'] . '"' . "\r\n";
    $message .= "Content-Transfer-Encoding: base64\r\n";
    $message .= 'Content-Disposition: attachment; filename="' . $attachment['name'] . '"' . "\r\n\r\n";
    $message .= chunk_split(base64_encode($attachment['contents'])) . "\r\n";
}
$message .= "--{$boundary}--\r\n";

$sent = mail(
    RECIPIENT_EMAIL,
    $encodedSubject,
    $message,
    implode("\r\n", $headers)
);

if ($sent) {
    header('Location: rfq.php?sent=1');
    exit;
}

error_log('Mod Decor RFQ email could not be accepted by PHP mail(). Reference: ' . $reference);
fail_form();
