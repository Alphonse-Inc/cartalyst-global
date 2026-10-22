<?php if (App\Captcha::enabled()): ?>
    <?php if (App\Captcha::provider() === 'turnstile'): ?>
        <div class="cf-turnstile mt-4" data-sitekey="<?= e(App\Captcha::siteKey()) ?>"></div>
    <?php else: ?>
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
        <script>
          if (window.grecaptcha) {
            grecaptcha.ready(function () {
              grecaptcha.execute("<?= e(App\Captcha::siteKey()) ?>", {action: "submit"}).then(function (token) {
                var el = document.getElementById("g-recaptcha-response");
                if (el) el.value = token;
              });
            });
          }
        </script>
    <?php endif; ?>
<?php endif; ?>
<?php if (!empty(error('captcha'))): ?><p class="help"><?= e(error('captcha')) ?></p><?php endif; ?>
