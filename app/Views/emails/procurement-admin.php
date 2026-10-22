<?php
$ranges = budget_ranges();
$budget = $r['budget_range'] ?? '';
$budgetLabel = $ranges[$budget] ?? ($budget ?: '—');
?>
<!DOCTYPE html>
<html><body style="font-family:Arial,sans-serif;color:#0B1F3A;background:#F6F3EC;padding:24px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:auto;background:#fff;border-radius:16px;padding:24px;">
<tr><td>
<p style="color:#0F9D6E;font-weight:700;margin:0;">New procurement request</p>
<h1 style="font-size:24px;margin:8px 0 16px;"><?= e($r['reference']) ?></h1>
<table width="100%" cellpadding="6" style="font-size:14px;">
<tr><td><strong>Name</strong></td><td><?= e($r['full_name']) ?></td></tr>
<tr><td><strong>Company</strong></td><td><?= e($r['company_name'] ?? '—') ?></td></tr>
<tr><td><strong>Email</strong></td><td><?= e($r['email']) ?></td></tr>
<tr><td><strong>Phone</strong></td><td><?= e($r['phone']) ?></td></tr>
<tr><td><strong>Location</strong></td><td><?= e($r['country_city']) ?></td></tr>
<tr><td><strong>Category</strong></td><td><?= e($r['category']) ?></td></tr>
<tr><td><strong>Quantity</strong></td><td><?= e((string) $r['quantity']) ?> <?= e($r['unit']) ?></td></tr>
<tr><td><strong>Budget</strong></td><td><?= e($budgetLabel) ?></td></tr>
<tr><td><strong>Delivery</strong></td><td><?= e($r['delivery_date'] ?? '—') ?></td></tr>
<tr><td><strong>Attachment</strong></td><td><?= e($r['attachment_original'] ?? 'None') ?></td></tr>
</table>
<p><strong>Item description</strong><br><?= nl2br(e($r['item_description'])) ?></p>
<p><strong>Notes</strong><br><?= nl2br(e($r['notes'] ?? '—')) ?></p>
<p style="font-size:12px;color:#5b6778;">Reply directly to this email to contact the requester. Open the admin panel to update status.</p>
</td></tr></table>
</body></html>
