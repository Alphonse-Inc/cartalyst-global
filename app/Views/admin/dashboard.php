<h1 class="font-display text-3xl">Dashboard</h1>
<p class="mt-1 text-navy/60">Welcome<?= $admin ? ', ' . e($admin['name']) : '' ?>.</p>
<div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="card p-5"><p class="text-sm text-navy/60">New requests</p><p class="font-display text-3xl"><?= (int) $counts['new'] ?></p></div>
    <div class="card p-5"><p class="text-sm text-navy/60">In progress</p><p class="font-display text-3xl"><?= (int) $counts['in_progress'] ?></p></div>
    <div class="card p-5"><p class="text-sm text-navy/60">Pending reviews</p><p class="font-display text-3xl"><?= (int) $pendingReviews ?></p></div>
    <div class="card p-5"><p class="text-sm text-navy/60">Public rating</p><p class="font-display text-3xl"><?= e($reviewStats['average']) ?> <span class="text-base">(<?= (int) $reviewStats['count'] ?>)</span></p></div>
</div>
<div class="mt-8 flex flex-wrap gap-3">
    <a class="btn btn-primary" href="<?= e(base_url('admin/requests?status=new')) ?>">Open new requests</a>
    <a class="btn btn-ghost" href="<?= e(base_url('admin/reviews?status=pending')) ?>">Moderate reviews</a>
</div>
<p class="mt-10 text-sm text-navy/60">Change the default admin password immediately if you have not already.</p>
