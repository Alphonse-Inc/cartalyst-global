<!DOCTYPE html>
<html><body style="font-family:Arial,sans-serif;color:#0B1F3A;padding:24px;">
<h1>Website contact — Cartalyst Global</h1>
<p><strong>Name:</strong> <?= e($m['name']) ?><br>
<strong>Email:</strong> <?= e($m['email']) ?><br>
<strong>Phone:</strong> <?= e($m['phone'] ?? '—') ?><br>
<strong>Subject:</strong> <?= e($m['subject']) ?></p>
<p><?= nl2br(e($m['message'])) ?></p>
</body></html>
