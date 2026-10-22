<p><a class="text-accent font-semibold" href="<?= e(base_url('admin/requests')) ?>">← All requests</a></p>
<h1 class="font-display mt-2 text-3xl"><?= e($row['reference']) ?></h1>
<p class="text-navy/60"><?= e($row['created_at']) ?> · <span class="badge badge-<?= e($row['status']) ?>"><?= e(str_replace('_', ' ', $row['status'])) ?></span></p>
<div class="card mt-6 p-6 grid gap-3 sm:grid-cols-2 text-sm">
    <p><strong>Name</strong><br><?= e($row['full_name']) ?></p>
    <p><strong>Company</strong><br><?= e($row['company_name'] ?: '—') ?></p>
    <p><strong>Email</strong><br><a href="mailto:<?= e($row['email']) ?>"><?= e($row['email']) ?></a></p>
    <p><strong>Phone</strong><br><?= e($row['phone']) ?></p>
    <p><strong>Location</strong><br><?= e($row['country_city']) ?></p>
    <p><strong>Category</strong><br><?= e($row['category']) ?></p>
    <p><strong>Quantity</strong><br><?= e((string) $row['quantity']) ?> <?= e($row['unit']) ?></p>
    <p><strong>Budget</strong><br><?= e($row['budget_range'] ?: '—') ?></p>
    <p><strong>Delivery</strong><br><?= e($row['delivery_date'] ?: '—') ?></p>
    <p><strong>Attachment</strong><br>
        <?php if ($row['attachment_stored']): ?>
            <a class="text-accent" href="<?= e(base_url('admin/requests/' . $row['id'] . '/file')) ?>"><?= e($row['attachment_original']) ?></a>
        <?php else: ?>—<?php endif; ?>
    </p>
</div>
<div class="card mt-4 p-6">
    <h2 class="font-semibold">Item description</h2>
    <p class="mt-2 whitespace-pre-wrap"><?= e($row['item_description']) ?></p>
    <h2 class="font-semibold mt-6">Notes</h2>
    <p class="mt-2 whitespace-pre-wrap"><?= e($row['notes'] ?: '—') ?></p>
</div>
<form class="mt-6 flex gap-3" method="post" action="<?= e(base_url('admin/requests/status')) ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
    <select class="input max-w-xs" name="status">
        <?php foreach (['new','in_progress','completed'] as $st): ?>
            <option value="<?= $st ?>" <?= $row['status'] === $st ? 'selected' : '' ?>><?= str_replace('_', ' ', $st) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn btn-primary" type="submit">Update status</button>
</form>
