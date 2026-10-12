<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request a Quote | Mod Decor</title>
    <meta name="description" content="Request a quotation from Mod Decor. Send your contact details and BOQ or project documents.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/Assets/css/rfq.css?v=5">
    <!--fonts-->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet" type="text/css">
    <link href="//fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&amp;subset=latin-ext" rel="stylesheet">
    <!--//fonts-->
</head>
<body>
    <nav class="navbar rfq-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="brand" href="index.html" aria-label="Mod Decor home">
                <img src="Images/MOD DECOR LOGO.png" alt="Logo" class="responsive-logo" onerror="this.style.display='none'">
            </a>
            <a class="back-link" href="products.html"><i class="fa fa-arrow-left"></i> Back to Products</a>
        </div>
    </nav>

    <header class="rfq-hero">
        <div class="container">
            <span class="eyebrow">LET'S BRING YOUR IDEAS TO LIFE</span>
            <h1>Request a <em>Quotation</em></h1>
            <p>Share your contact details and upload your BOQ or project documents. Our team will review your request and get back to you.</p>
        </div>
    </header>

    <main class="container rfq-main">
        <?php if (isset($_GET['sent']) && $_GET['sent'] === '1'): ?>
            <div class="alert alert-success" role="status">
                <strong>Thank you!</strong> Your quotation request has been sent. Our team will review it and get back to you.
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                We couldn't send your request. Please check the form and try again, or contact us directly at
                <a href="mailto:info@moddecor.com.ng">info@moddecor.com.ng</a>.
            </div>
        <?php endif; ?>

        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <form class="rfq-form" action="send-rfq.php" method="post" enctype="multipart/form-data">
                    <!-- Simple bot trap: real customers should leave this blank. -->
                    <div class="visually-hidden" aria-hidden="true">
                        <label for="website">Leave this field blank</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="section-heading">
                        <span class="step">01</span>
                        <div>
                            <h2>Your contact details</h2>
                            <p>Fields marked * are required.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="full_name">Full name <span>*</span></label>
                            <input class="form-control" id="full_name" name="full_name" required maxlength="120" autocomplete="name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="company">Company / business</label>
                            <input class="form-control" id="company" name="company" maxlength="120" autocomplete="organization">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email address <span>*</span></label>
                            <input class="form-control" type="email" id="email" name="email" required maxlength="180" autocomplete="email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Phone / WhatsApp <span>*</span></label>
                            <input class="form-control" type="tel" id="phone" name="phone" required maxlength="40" autocomplete="tel">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="location">Project location <span>*</span></label>
                            <input class="form-control" id="location" name="location" required maxlength="180" placeholder="e.g. Lekki, Lagos">
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="section-heading">
                        <span class="step">02</span>
                        <div>
                            <h2>Project information</h2>
                            <p>Tell us briefly what the quotation is for.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="product">Product / service <span>*</span></label>
                            <select class="form-select" id="product" name="product" required>
                                <option value="">Choose an option</option>
                                <option>Window Blinds</option>
                                <option>Decorative Tiles</option>
                                <option>Flower Ceiling Cornice</option>
                                <option>Floor Skirting</option>
                                <option>Corners</option>
                                <option>Interior Curtains</option>
                                <option>Interior Design</option>
                                <option>Home Decoration</option>
                                <option>Multiple Products / Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="quantity">Estimated quantity (optional)</label>
                            <input class="form-control" id="quantity" name="quantity" maxlength="100" placeholder="e.g. 12 windows or 50 sqm">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="details">Project description / extra details</label>
                            <textarea class="form-control" id="details" name="details" rows="4" maxlength="5000" placeholder="Measurements, preferred colours, materials, delivery timeline, or other requirements..."></textarea>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="section-heading">
                        <span class="step">03</span>
                        <div>
                            <h2>Upload your BOQ or documents</h2>
                            <p>Attach your BOQ, drawings, reference images or specification sheet.</p>
                        </div>
                    </div>

                    <label class="upload-box" for="documents">
                        <span class="upload-icon"><i class="fa fa-cloud-upload"></i></span>
                        <strong>Choose files</strong>
                        <span>PDF, JPG, PNG, WEBP, DOC or DOCX · Maximum 5 files, 8 MB each</span>
                        <input class="form-control mt-3" type="file" id="documents" name="documents[]" multiple
                               accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx">
                    </label>
                    <div id="file-list" class="file-list" aria-live="polite"></div>

                    <p class="privacy-note"><i class="fa fa-lock"></i> Your details and attached documents will be sent to Mod Decor to help us prepare your quotation.</p>

                    <button class="btn submit-btn" type="submit">
                        Submit Quote Request <i class="fa fa-arrow-right"></i>
                    </button>
                </form>
            </div>

            <div class="col-lg-4">
                <aside class="side-card">
                    <span class="eyebrow">MOD DECOR</span>
                    <h2>Let's plan your space.</h2>
                    <p>Send us the details of your project and any supporting documents. We'll use them to understand your requirements before responding.</p>
                    <ul>
                        <li><i class="fa fa-check-circle"></i> Product-specific enquiries</li>
                        <li><i class="fa fa-check-circle"></i> BOQ and drawing uploads</li>
                        <li><i class="fa fa-check-circle"></i> Direct follow-up from our team</li>
                    </ul>
                    <div class="contact-box">
                        <small>Prefer to email us?</small>
                        <a href="mailto:info@moddecor.com.ng">info@moddecor.com.ng</a>
                    </div>
                    <a class="products-link" href="products.html">Explore our products <i class="fa fa-long-arrow-right"></i></a>
                </aside>
            </div>
        </div>
    </main>

    <footer class="rfq-footer">
        <div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
            <span>© <?php echo date('Y'); ?> Mod Decor. All rights reserved.</span>
            <a href="products.html">Back to products</a>
        </div>
    </footer>

    <script>
    const input = document.getElementById('documents');
    const list = document.getElementById('file-list');
    input.addEventListener('change', () => {
        list.textContent = '';
        const files = Array.from(input.files || []);
        if (!files.length) return;
        const ul = document.createElement('ul');
        files.forEach(file => {
            const li = document.createElement('li');
            li.textContent = file.name + ' (' + (file.size / 1048576).toFixed(2) + ' MB)';
            ul.appendChild(li);
        });
        list.appendChild(ul);
    });
    </script>
</body>
</html>
