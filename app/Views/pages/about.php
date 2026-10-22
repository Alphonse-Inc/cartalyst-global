<section class="hero-wash">
    <div class="mx-auto max-w-6xl px-4 py-14">
        <p class="text-sm font-bold uppercase tracking-[0.18em] text-accent">About us</p>
        <h1 class="font-display mt-3 max-w-3xl text-4xl text-navy sm:text-5xl">Built for organisations that need a procurement partner, not another inbox.</h1>
        <p class="mt-5 max-w-2xl text-lg text-navy/70">Cartalyst Global exists to make buying simpler: clarify the requirement, find the right supply, and stay accountable through delivery.</p>
    </div>
</section>
<section class="mx-auto grid max-w-6xl gap-10 px-4 py-14 lg:grid-cols-2 lg:items-center">
    <!-- REPLACE: Unsplash collaboration placeholder -->
    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1200&q=80"
         alt="Professionals reviewing procurement documents at Cartalyst Global"
         class="h-72 w-full rounded-3xl object-cover shadow-lg sm:h-96" width="800" height="600" loading="lazy">
    <div>
        <h2 class="font-display text-3xl text-navy">Our story</h2>
        <p class="mt-4 text-navy/75">Cartalyst Global was founded by people who had sat on both sides of the table: operations teams waiting on materials, and buyers drowning in fragmented vendor lists. We built a focused procurement practice that treats every brief as a project — with a reference number, a named process, and a bias for clear communication.</p>
        <p class="mt-3 text-navy/75">Today we support industrial, corporate, construction, healthcare, and hospitality clients who need a partner that can source across categories without losing the specification.</p>
    </div>
</section>
<section class="bg-white">
    <div class="mx-auto grid max-w-6xl gap-6 px-4 py-14 md:grid-cols-2">
        <article class="card p-8">
            <h2 class="font-display text-2xl text-navy">Mission</h2>
            <p class="mt-3 text-navy/75">To give organisations a trustworthy path from requirement to delivery, wherever the goods need to arrive.</p>
        </article>
        <article class="card p-8">
            <h2 class="font-display text-2xl text-navy">Vision</h2>
            <p class="mt-3 text-navy/75">A world where global sourcing is as transparent and dependable as a local purchase order.</p>
        </article>
    </div>
</section>
<section class="mx-auto max-w-6xl px-4 py-14">
    <h2 class="font-display text-3xl text-navy">Values</h2>
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <?php
        $values = [
            ['title' => 'Clarity', 'copy' => 'Specifications, lead times, and costs are written down — not implied.'],
            ['title' => 'Care', 'copy' => 'We treat other people’s operations as if they were our own production line.'],
            ['title' => 'Integrity', 'copy' => 'No hidden mark-ups in the shadows. We say what we can and cannot do.'],
            ['title' => 'Pace with control', 'copy' => 'Move quickly, without skipping vendor checks or documentation.'],
        ];
        foreach ($values as $value): ?>
            <article class="card p-6">
                <h3 class="font-semibold text-navy"><?= e($value['title']) ?></h3>
                <p class="mt-2 text-sm text-navy/70"><?= e($value['copy']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<section class="mx-auto max-w-6xl px-4 pb-16">
    <h2 class="font-display text-3xl text-navy">Team</h2>
    <p class="mt-2 text-navy/70">Placeholder profiles — replace names, roles, and photos with your people.</p>
    <div class="mt-8 grid gap-4 sm:grid-cols-3">
        <?php
        $team = [
            ['name' => 'Amina Okoye', 'role' => 'Managing Partner', 'img' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'James Whitaker', 'role' => 'Head of Sourcing', 'img' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Sofia Rahman', 'role' => 'Client Delivery Lead', 'img' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=600&q=80'],
        ];
        foreach ($team as $person): ?>
            <article class="card overflow-hidden">
                <!-- REPLACE: Unsplash portrait placeholder -->
                <img src="<?= e($person['img']) ?>" alt="Portrait placeholder for <?= e($person['name']) ?>" class="h-56 w-full object-cover" width="400" height="300" loading="lazy">
                <div class="p-5">
                    <h3 class="font-semibold"><?= e($person['name']) ?></h3>
                    <p class="text-sm text-navy/60"><?= e($person['role']) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
