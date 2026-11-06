<main class="grid md:grid-cols-2 min-h-[80vh]">
  <div class="bg-navy text-white px-10 py-16 flex flex-col justify-center">
    <h1 class="font-display text-3xl font-semibold leading-tight max-w-xs">Welcome back.</h1>
    <p class="mt-4 text-slate-300 max-w-xs text-sm leading-relaxed">
      Log in to manage your alert preferences and stay connected to campus safety updates.
    </p>
  </div>

  <div class="bg-white px-10 py-16 flex flex-col justify-center">
    <div class="max-w-sm w-full">
      <h2 class="font-display text-2xl font-semibold text-navy">Log in</h2>

      <?php if (!empty($_GET['registered'])): ?>
        <div class="mt-6 border border-confirm/30 bg-confirm/5 text-confirm text-sm rounded-sm p-4">
          Account created. You can log in now.
        </div>
      <?php endif; ?>

      <?php if (!empty($_GET['reset'])): ?>
        <div class="mt-6 border border-confirm/30 bg-confirm/5 text-confirm text-sm rounded-sm p-4">
          Password updated. Log in with your new password.
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
                 value="<?= e($old['email']) ?>"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
          <input type="password" id="password" name="password" required
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>

        <div class="flex justify-end text-sm">
          <a href="<?= APP_URL ?>/forgot-password" class="text-navy hover:text-alert">Forgot password?</a>
        </div>

        <button type="submit"
                class="w-full bg-navy text-white py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
          Log in
        </button>
      </form>

      <p class="mt-6 text-sm text-slate-500">
        New here? <a href="<?= APP_URL ?>/register" class="text-navy font-medium hover:text-alert">Create an account</a>
      </p>
      <p class="mt-2 text-sm text-slate-500">
  Security official? <a href="<?= APP_URL ?>/security/register" class="text-navy font-medium hover:text-alert">Register here</a>
</p>
    </div>
  </div>
</main>
