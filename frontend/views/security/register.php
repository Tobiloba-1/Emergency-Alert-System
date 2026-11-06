<main class="grid md:grid-cols-2 min-h-[80vh]">
  <div class="bg-navy text-white px-10 py-16 flex flex-col justify-center">
    <h1 class="font-display text-3xl font-semibold leading-tight max-w-xs">
      Register as campus security.
    </h1>
    <p class="mt-4 text-slate-300 max-w-xs text-sm leading-relaxed">
      Once registered, go on duty to receive proximity-ranked SOS alerts. Your name, badge and phone number are shared with a student when you're assigned to their SOS.
    </p>
  </div>

  <div class="bg-white px-10 py-16 flex flex-col justify-center">
    <div class="max-w-sm w-full">
      <h2 class="font-display text-2xl font-semibold text-navy">Security official registration</h2>

      <?php if (!empty($errors)): ?>
        <div class="mt-6 border border-alert/30 bg-alert/5 text-alert text-sm rounded-sm p-4">
          <ul class="list-disc list-inside space-y-1">
            <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" class="mt-6 space-y-5">
        <input type="hidden" name="csrf_token" value="<?= e(Csrf::token()) ?>">

        <div>
          <label for="full_name" class="block text-sm font-medium text-slate-700">Full name</label>
          <input type="text" id="full_name" name="full_name" required value="<?= e($old['full_name']) ?>"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-slate-700">Institutional email</label>
          <input type="email" id="email" name="email" required value="<?= e($old['email']) ?>"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="badge_id" class="block text-sm font-medium text-slate-700">Badge / ID number</label>
            <input type="text" id="badge_id" name="badge_id" required value="<?= e($old['badge_id']) ?>"
                   class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
          </div>
          <div>
            <label for="phone" class="block text-sm font-medium text-slate-700">Phone number</label>
            <input type="tel" id="phone" name="phone" required value="<?= e($old['phone']) ?>"
                   class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
          </div>
        </div>

        <div>
          <label for="department" class="block text-sm font-medium text-slate-700">Assigned zone / building</label>
          <input type="text" id="department" name="department" value="<?= e($old['department']) ?>"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
          <input type="password" id="password" name="password" required minlength="8"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>

        <div>
          <label for="confirm_password" class="block text-sm font-medium text-slate-700">Confirm password</label>
          <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>

        <button type="submit" class="w-full bg-navy text-white py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
          Register
        </button>
      </form>

      <p class="mt-6 text-sm text-slate-500">
        Already registered? <a href="<?= APP_URL ?>/login"   class="text-navy font-medium hover:text-alert">Log in</a>
      </p>
    </div>
  </div>
</main>