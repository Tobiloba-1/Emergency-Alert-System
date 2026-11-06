<main class="max-w-md mx-auto px-6 py-20">
  <h1 class="font-display text-2xl font-semibold text-navy">Set a new password</h1>

  <?php if (!empty($errors)): ?>
    <div class="mt-6 border border-alert/30 bg-alert/5 text-alert text-sm rounded-sm p-4">
      <ul class="list-disc list-inside space-y-1">
        <?php foreach ($errors as $err): ?>
          <li><?= e($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <p class="mt-4 text-sm">
      <a href="<?= APP_URL ?>/forgot-password" class="text-navy font-medium hover:text-alert">Request a new link</a>
    </p>
  <?php endif; ?>

  <?php if ($valid_reset): ?>
    <form method="post" class="mt-6 space-y-5">
      <input type="hidden" name="csrf_token" value="<?= e(Csrf::token()) ?>">
      <input type="hidden" name="token" value="<?= e($token) ?>">

      <div>
        <label for="password" class="block text-sm font-medium text-slate-700">New password</label>
        <input type="password" id="password" name="password" required minlength="8" autofocus
               class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
      </div>

      <div>
        <label for="confirm_password" class="block text-sm font-medium text-slate-700">Confirm new password</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
               class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
      </div>

      <button type="submit"
              class="w-full bg-navy text-white py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
        Update password
      </button>
    </form>
  <?php endif; ?>
</main>
