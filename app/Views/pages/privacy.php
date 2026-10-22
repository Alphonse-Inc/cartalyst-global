<section class="mx-auto max-w-3xl px-4 py-14">
    <h1 class="font-display text-4xl text-navy">Privacy Policy — Cartalyst Global</h1>
    <p class="mt-2 text-sm text-navy/60">Last updated <?= date('j F Y') ?>. Have this page reviewed by your counsel before launch.</p>
    <div class="mt-8 space-y-5 text-navy/80 leading-relaxed">
        <p>Cartalyst Global (“we”, “us”) operates this website to describe our procurement services and to receive enquiries, quote requests, and reviews.</p>
        <h2 class="font-display text-2xl text-navy">What we collect</h2>
        <p>When you submit a form we collect the details you provide (such as name, company, email, phone, location, and message). Reviews also include a star rating. File uploads attached to procurement requests are stored securely for fulfilment. We log a hashed identifier of your IP address for spam control — not your raw IP in application records.</p>
        <h2 class="font-display text-2xl text-navy">How we use it</h2>
        <p>We use this information to respond to requests, provide quotes, prevent abuse, and (for approved reviews only) publish first name/company and review text. Email addresses on reviews are never shown publicly.</p>
        <h2 class="font-display text-2xl text-navy">Sharing</h2>
        <p>We share data with email delivery providers (SMTP) and, if configured, Cloudflare Turnstile or Google reCAPTCHA. We do not sell personal information.</p>
        <h2 class="font-display text-2xl text-navy">Retention & rights</h2>
        <p>Procurement records are kept as long as needed for the commercial relationship and accounting. You may ask us to correct or delete personal data that is not required by law. Contact <?= e(setting('email', (string) config('mail.contact_to'))) ?>.</p>
        <h2 class="font-display text-2xl text-navy">Cookies</h2>
        <p>We use a session cookie for form security (CSRF) and admin sign-in. Captcha providers may set their own cookies.</p>
    </div>
</section>
