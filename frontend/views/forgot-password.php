<main class="max-w-md mx-auto px-6 py-20">
  <h1 class="font-display text-2xl font-semibold text-navy">Reset your password</h1>
  <p class="mt-2 text-slate-600 text-sm">
    Enter your registered email and we'll send you a link to reset your password.
  </p>

  <?php if (!empty($notice)): ?>
    <div class="mt-6 border border-confirm/30 bg-confirm/5 text-confirm text-sm rounded-sm p-4">
      <?= e($notice) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="mt-6 border border-alert/30 bg-alert/5 text-alert text-sm rounded-sm p-4">
      <ul class="list-disc list-inside space-y-1">
        <?php foreach ($errors as $err): ?>
          <li><?= e($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" class="mt-6 space-y-5">
    <input type="hidden" name="csrf_token" value="<?= e(Csrf::token()) ?>">
    <div>
      <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
      <input type="email" id="email" name="email" required autofocus
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>
    <button type="submit"
            class="w-full bg-navy text-white py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
      Send reset link
    </button>
  </form>

  <p class="mt-6 text-sm text-slate-500">
    <a href="login.php" class="text-navy font-medium hover:text-alert">Back to log in</a>
  </p>
</main>
