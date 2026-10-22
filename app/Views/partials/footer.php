<footer class="mt-16 bg-navy text-cream">
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 md:grid-cols-4">
        <div class="md:col-span-2">
            <img src="<?= e(base_url('assets/img/logo-light.svg')) ?>" alt="Cartalyst Global" class="h-12 w-auto" width="210" height="46">
            <p class="mt-4 max-w-md text-sm text-cream/80">Cartalyst Global is a procurement and sourcing partner for organisations that need reliable supply, clear communication, and accountable delivery.</p>
        </div>
        <div>
            <h2 class="font-display text-lg">Explore</h2>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a class="hover:text-accent" href="<?= e(base_url('services')) ?>">Services</a></li>
                <li><a class="hover:text-accent" href="<?= e(base_url('request-procurement')) ?>">Request procurement</a></li>
                <li><a class="hover:text-accent" href="<?= e(base_url('reviews')) ?>">Reviews</a></li>
                <li><a class="hover:text-accent" href="<?= e(base_url('about')) ?>">About us</a></li>
            </ul>
        </div>
        <div>
            <h2 class="font-display text-lg">Contact</h2>
            <ul class="mt-3 space-y-2 text-sm text-cream/80">
                <li><?= e(setting('email', (string) config('mail.contact_to'))) ?></li>
                <li><?= e(setting('phone')) ?></li>
                <li><?= e(setting('address')) ?></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 px-4 py-5 text-center text-xs text-cream/60">
        <a class="hover:text-cream" href="<?= e(base_url('privacy')) ?>">Privacy Policy</a>
        ·
        <a class="hover:text-cream" href="<?= e(base_url('terms')) ?>">Terms of Use</a>
        · © <?= date('Y') ?> Cartalyst Global. All rights reserved.
    </div>
</footer>
