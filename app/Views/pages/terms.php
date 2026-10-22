<section class="mx-auto max-w-3xl px-4 py-14">
    <h1 class="font-display text-4xl text-navy">Terms of Use — Cartalyst Global</h1>
    <p class="mt-2 text-sm text-navy/60">Last updated <?= date('j F Y') ?>. Replace with your legal terms before taking orders.</p>
    <div class="mt-8 space-y-5 text-navy/80 leading-relaxed">
        <p>By using the Cartalyst Global website you agree to these terms. If you do not agree, do not submit forms or rely on the site for contractual commitments.</p>
        <h2 class="font-display text-2xl text-navy">Quotes are invitations, not contracts</h2>
        <p>A procurement request creates an enquiry with a reference number. It does not bind Cartalyst Global to supply goods until a written quotation is accepted on agreed terms.</p>
        <h2 class="font-display text-2xl text-navy">Accurate information</h2>
        <p>You confirm that the details and files you submit are accurate and that you have the right to share them.</p>
        <h2 class="font-display text-2xl text-navy">Reviews</h2>
        <p>Reviews must be lawful, non-defamatory, and based on a genuine experience. We may edit for spelling or withhold publication.</p>
        <h2 class="font-display text-2xl text-navy">Acceptable use</h2>
        <p>You must not attempt to disrupt the site, bypass security, scrape content at a rate that harms the service, or submit spam.</p>
        <h2 class="font-display text-2xl text-navy">Contact</h2>
        <p><?= e(setting('email', (string) config('mail.contact_to'))) ?></p>
    </div>
</section>
