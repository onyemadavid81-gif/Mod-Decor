MOD DECOR SIMPLE RFQ FORM - INSTALLATION

FILES
- rfq.php: customer form (the file is PHP because it displays the current year and confirmation message)
- send-rfq.php: validates the form and emails the details and attachments to info@moddecor.com.ng
- assets/css/rfq.css: styling

INSTALL
1. Back up your website first.
2. Upload rfq.php and send-rfq.php to the same public_html folder where index.html and products.html are located.
3. Upload the assets/css/rfq.css file, creating folders if needed.
4. In your products page, set the CTA link to:
   <a href="rfq.php" class="rfq-banner-btn">Request a Quote</a>
5. Open https://moddecor.com.ng/rfq.php and submit a test request.

EMAIL DELIVERY NOTES
- The script uses PHP mail() and sets info@moddecor.com.ng as the From and recipient, with the customer's email as Reply-To.
- PHP mail() being accepted does not guarantee delivery. Check Inbox, Spam/Junk and DirectAdmin mail logs if available.
- If email doesn't arrive or attachments are blocked, ask your hosting provider for the authenticated SMTP hostname, port, encryption type, and mailbox authentication instructions. Then use an SMTP library such as PHPMailer; do not put your mailbox password in public HTML or JavaScript.
- Confirm info@moddecor.com.ng exists and can send/receive mail before testing.

UPLOAD LIMITS
- Up to 5 attachments, 8 MB each. PHP hosting limits must allow multipart POSTs large enough for attachments. If needed, ask hosting support to set upload_max_filesize to 8M or higher and post_max_size to 45M or higher.
- Allowed formats: PDF, JPG/JPEG, PNG, WEBP, DOC, DOCX.
- The handler validates extension and MIME type and attaches the files directly to the email; it does not store uploads permanently on your server.

TROUBLESHOOTING
- If form returns to rfq.php?error=1, one field or attachment may be invalid, or mail() failed.
- If the page downloads as a file or shows PHP source, contact hosting support immediately; PHP must be enabled.
- If you get an HTTP 500 error, check DirectAdmin's PHP error logs.
- If the email never arrives, check spam/junk and ask the host whether PHP mail is enabled. For better reliability, configure authenticated SMTP through PHPMailer using the provider's official SMTP details.
