<!DOCTYPE html>
<html><body style="font-family:Arial,sans-serif;color:#0B1F3A;padding:24px;">
<h1>New pending review</h1>
<p>A review was submitted on Cartalyst Global and is waiting for approval.</p>
<p><strong><?= e($_POST['name'] ?? ($post['name'] ?? '')) ?></strong> — <?= (int) ($post['rating'] ?? 0) ?>/5</p>
<p><strong><?= e($post['title'] ?? '') ?></strong></p>
<p><?= nl2br(e($post['body'] ?? '')) ?></p>
<p>Sign in to /admin/reviews to approve, edit, or reject.</p>
</body></html>
