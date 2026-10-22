<header class="site-header" x-data="{ open: false }">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
        <a href="<?= e(base_url('/')) ?>" class="flex min-h-[44px] items-center shrink-0">
            <img src="<?= e(base_url('assets/img/logo.svg')) ?>" alt="Cartalyst Global logo" width="210" height="46" class="h-11 w-auto">
        </a>
        <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold" aria-label="Primary">
            <?php foreach (nav_items() as $item): ?>
                <a href="<?= e(base_url(ltrim($item['href'], '/'))) ?>"
                   class="<?= is_active($item['href']) ? 'text-accent' : 'text-navy/80 hover:text-navy' ?>">
                    <?= e($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <a href="<?= e(base_url('request-procurement')) ?>" class="btn btn-primary hidden sm:inline-flex text-sm">Request a Quote</a>
        <button type="button" class="lg:hidden inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-full border border-navy/10"
                @click="open = !open" :aria-expanded="open.toString()" aria-controls="mobile-nav" aria-label="Open menu">
            <span x-show="!open"><?= icon('menu', 'w-6 h-6') ?></span>
            <span x-cloak x-show="open"><?= icon('x', 'w-6 h-6') ?></span>
        </button>
    </div>
    <div id="mobile-nav" class="lg:hidden border-t border-navy/10 bg-cream" x-show="open" x-cloak x-transition>
        <nav class="flex flex-col px-4 py-3 text-base font-semibold" aria-label="Mobile">
            <?php foreach (nav_items() as $item): ?>
                <a class="min-h-[44px] flex items-center" href="<?= e(base_url(ltrim($item['href'], '/'))) ?>"><?= e($item['label']) ?></a>
            <?php endforeach; ?>
            <a class="btn btn-primary mt-2 mb-3" href="<?= e(base_url('request-procurement')) ?>">Request a Quote</a>
        </nav>
    </div>
</header>
<style>[x-cloak]{display:none !important;}</style>
