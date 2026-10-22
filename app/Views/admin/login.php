<div class="card p-8">
    <h1 class="font-display text-2xl">Admin sign in</h1>
    <?php $errs = errors(); if (!empty($errs['_form'])): ?>
        <p class="mt-3 rounded-xl bg-red-50 p-3 text-red-800"><?= e($errs['_form']) ?></p>
    <?php endif; ?>
    <?php if ($ok = flash('success')): ?>
        <p class="mt-3 rounded-xl bg-emerald-50 p-3 text-emerald-800"><?= e($ok) ?></p>
    <?php endif; ?>
    <form class="mt-6 space-y-4" method="post" action="<?= e(base_url('admin/login')) ?>">
        <?= csrf_field() ?>
        <div>
            <label class="label" for="email">Email</label>
            <input class="input" id="email" name="email" type="email" required autocomplete="username">
        </div>
        <div>
            <label class="label" for="password">Password</label>
            <input class="input" id="password" name="password" type="password" required autocomplete="current-password">
        </div>
        <button class="btn btn-primary w-full" type="submit">Sign in</button>
    </form>
</div>
