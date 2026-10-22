<h1 class="font-display text-3xl">Reviews</h1>
<div class="mt-4 flex gap-2">
    <?php foreach (['pending','approved','rejected',''] as $st): ?>
        <a class="btn <?= $status === $st ? 'btn-navy' : 'btn-ghost' ?>" href="?status=<?= e($st) ?>"><?= $st === '' ? 'All' : ucfirst($st) ?></a>
    <?php endforeach; ?>
</div>
<div class="mt-6 space-y-6">
<?php foreach ($result['rows'] as $row): ?>
    <article class="card p-5">
        <div class="flex flex-wrap items-start justify-between gap-2">
            <div>
                <?= stars((int) $row['rating']) ?>
                <h2 class="font-semibold mt-1"><?= e($row['title']) ?></h2>
                <p class="text-xs text-navy/50"><?= e($row['name']) ?> · <?= e($row['email']) ?> · <?= e($row['created_at']) ?></p>
            </div>
            <span class="badge badge-<?= e($row['status']) ?>"><?= e($row['status']) ?></span>
        </div>
        <form class="mt-4 space-y-3" method="post" action="<?= e(base_url('admin/reviews')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
            <input type="hidden" name="return_status" value="<?= e($status) ?>">
            <input class="input" name="name" value="<?= e($row['name']) ?>">
            <div class="grid gap-3 sm:grid-cols-2">
                <input class="input" name="company" placeholder="Company" value="<?= e((string) $row['company']) ?>">
                <input class="input" name="job_title" placeholder="Job title" value="<?= e((string) $row['job_title']) ?>">
            </div>
            <select class="input" name="rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>" <?= (int) $row['rating'] === $i ? 'selected' : '' ?>><?= $i ?> stars</option>
                <?php endfor; ?>
            </select>
            <input class="input" name="title" value="<?= e($row['title']) ?>">
            <textarea class="input" name="body"><?= e($row['body']) ?></textarea>
            <label class="inline-flex items-center gap-2 min-h-[44px]"><input type="checkbox" name="featured" value="1" <?= $row['featured'] ? 'checked' : '' ?>> Featured on homepage</label>
            <select class="input" name="status">
                <?php foreach (['pending','approved','rejected'] as $st): ?>
                    <option value="<?= $st ?>" <?= $row['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                <?php endforeach; ?>
            </select>
            <div class="flex flex-wrap gap-2">
                <button class="btn btn-primary" name="do" value="save" type="submit">Save</button>
                <button class="btn btn-navy" name="do" value="approve" type="submit">Approve</button>
                <button class="btn btn-ghost" name="do" value="reject" type="submit">Reject</button>
                <button class="btn btn-ghost text-red-700" name="do" value="delete" type="submit" onclick="return confirm('Delete this review?')">Delete</button>
            </div>
        </form>
    </article>
<?php endforeach; ?>
<?php if (!$result['rows']): ?><p>No reviews in this view.</p><?php endif; ?>
</div>
