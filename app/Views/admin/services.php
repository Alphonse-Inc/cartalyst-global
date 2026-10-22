<h1 class="font-display text-3xl">Services</h1>
<p class="text-navy/60">Edits appear on the public site immediately.</p>
<div class="mt-6 space-y-6">
<?php foreach ($services as $row): ?>
    <form class="card p-5 space-y-3" method="post" action="<?= e(base_url('admin/services')) ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
        <div class="grid gap-3 sm:grid-cols-2">
            <input class="input" name="title" value="<?= e($row['title']) ?>" required>
            <input class="input" name="slug" value="<?= e($row['slug']) ?>">
        </div>
        <input class="input" name="short_description" value="<?= e($row['short_description']) ?>">
        <textarea class="input" name="description"><?= e($row['description']) ?></textarea>
        <div class="grid gap-3 sm:grid-cols-3">
            <input class="input" name="icon" value="<?= e($row['icon']) ?>" placeholder="icon key">
            <input class="input" name="sort_order" type="number" value="<?= (int) $row['sort_order'] ?>">
            <label class="inline-flex items-center gap-2 min-h-[44px]"><input type="checkbox" name="is_active" <?= $row['is_active'] ? 'checked' : '' ?>> Active</label>
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
    </form>
    <form method="post" action="<?= e(base_url('admin/services/delete')) ?>" onsubmit="return confirm('Remove this service?')">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
        <button class="text-sm text-red-700" type="submit">Delete</button>
    </form>
<?php endforeach; ?>
</div>
<form class="card p-5 mt-10 space-y-3" method="post" action="<?= e(base_url('admin/services')) ?>">
    <?= csrf_field() ?>
    <h2 class="font-display text-xl">Add service</h2>
    <input class="input" name="title" placeholder="Title" required>
    <input class="input" name="slug" placeholder="slug-optional">
    <input class="input" name="short_description" placeholder="Short description">
    <textarea class="input" name="description" placeholder="Full description"></textarea>
    <input class="input" name="icon" value="package">
    <input class="input" name="sort_order" type="number" value="110">
    <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" checked> Active</label>
    <button class="btn btn-primary" type="submit">Create</button>
</form>
