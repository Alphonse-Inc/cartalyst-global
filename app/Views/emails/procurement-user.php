<!DOCTYPE html>
<html><body style="font-family:Arial,sans-serif;color:#0B1F3A;background:#F6F3EC;padding:24px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:auto;background:#fff;border-radius:16px;padding:24px;">
<tr><td>
<p style="margin:0;color:#0F9D6E;font-weight:700;">Cartalyst Global</p>
<h1 style="font-size:24px;">We received your procurement request</h1>
<p>Thank you, <?= e($r['full_name']) ?>. Your reference number is:</p>
<p style="font-size:28px;font-weight:700;letter-spacing:1px;"><?= e($r['reference']) ?></p>
<p>Keep this number for all follow-up. Our team will review the brief and reply to <?= e($r['email']) ?>.</p>
<p style="font-size:13px;color:#5b6778;">This is an automatic confirmation. If you did not submit a request, ignore this email.</p>
</td></tr></table>
</body></html>
