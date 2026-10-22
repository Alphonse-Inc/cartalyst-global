<h1 class="font-display text-3xl">Procurement requests</h1>
<form class="mt-4 flex flex-col gap-3 sm:flex-row" method="get" action="<?= e(base_url('admin/requests')) ?>">
    <select class="input sm:max-w-[180px]" name="status">
        <option value="">All statuses</option>
        <?php foreach (['new' => 'New', 'in_progress' => 'In progress', 'completed' => 'Completed'] as $k => $label): ?>
            <option value="<?= e($k) ?>" <?= $status === $k ? 'selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
    </select>
    <input class="input" name="q" placeholder="Search name, email, reference…" value="<?= e($q) ?>">
    <button class="btn btn-navy" type="submit">Filter</button>
    <a class="btn btn-ghost" href="<?= e(base_url('admin/requests/export?status=' . urlencode($status) . '&q=' . urlencode($q))) ?>">Export CSV</a>
</form>
<p class="mt-3 text-sm text-navy/60"><?= (int) $counts['all'] ?> total · <?= (int) $counts['new'] ?> new</p>
<div class="mt-4 overflow-x-auto card">
<table class="admin-table">
    <thead><tr><th>Ref</th><th>Client</th><th>Category</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($result['rows'] as $row): ?>
        <tr>
            <td><a class="font-semibold text-accent" href="<?= e(base_url('admin/requests/' . $row['id'])) ?>"><?= e($row['reference']) ?></a><br><span class="text-xs text-navy/50"><?= e($row['created_at']) ?></span></td>
            <td><?= e($row['full_name']) ?><br><span class="text-xs"><?= e($row['email']) ?></span></td>
            <td><?= e($row['category']) ?></td>
            <td><span class="badge badge-<?= e($row['status']) ?>"><?= e(str_replace('_', ' ', $row['status'])) ?></span></td>
            <td>
                <form method="post" action="<?= e(base_url('admin/requests/status')) ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                    <select class="input" name="status" onchange="this.form.submit()">
                        <?php foreach (['new','in_progress','completed'] as $st): ?>
                            <option value="<?= $st ?>" <?= $row['status'] === $st ? 'selected' : '' ?>><?= str_replace('_', ' ', $st) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$result['rows']): ?><tr><td colspan="5">No requests match this filter.</td></tr><?php endif; ?>
    </tbody>
</table>
</div>
<?php
$pages = max(1, (int) ceil($result['total'] / $result['perPage']));
if ($pages > 1): ?>
<nav class="mt-4 flex gap-2">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a class="btn <?= $i === $result['page'] ? 'btn-navy' : 'btn-ghost' ?>" href="?status=<?= e(urlencode($status)) ?>&q=<?= e(urlencode($q)) ?>&page=<?= $i ?>"><?= $i ?></a>
    <?php endfor; ?>
</nav>
<?php endif; ?>
