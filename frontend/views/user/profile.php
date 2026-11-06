<main class="max-w-md mx-auto px-6 py-16">
  <h1 class="font-display text-2xl font-semibold text-navy">My profile</h1>

  <?php if (!empty($notice)): ?>
    <div class="mt-6 border border-confirm/30 bg-confirm/5 text-confirm text-sm rounded-sm p-4"><?= e($notice) ?></div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="mt-6 border border-alert/30 bg-alert/5 text-alert text-sm rounded-sm p-4">
      <ul class="list-disc list-inside space-y-1">
        <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" class="mt-6 space-y-5 bg-white border border-slate-200 rounded-sm p-6">
    <input type="hidden" name="csrf_token" value="<?= e(Csrf::token()) ?>">

    <div>
      <label for="full_name" class="block text-sm font-medium text-slate-700">Full name</label>
      <input type="text" id="full_name" name="full_name" required value="<?= e($user['full_name']) ?>"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <div>
      <label class="block text-sm font-medium text-slate-700">Email</label>
      <p class="mt-1 text-slate-500 text-sm"><?= e($user['email']) ?> <span class="text-slate-400">(cannot be changed here)</span></p>
    </div>

    <div>
      <label for="department" class="block text-sm font-medium text-slate-700">Department / Hall</label>
      <input type="text" id="department" name="department" value="<?= e($user['department'] ?? '') ?>"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <div>
      <label for="phone" class="block text-sm font-medium text-slate-700">Phone <span class="text-slate-400">(optional)</span></label>
      <input type="text" id="phone" name="phone" value="<?= e($user['phone'] ?? '') ?>"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <button type="submit" class="w-full bg-navy text-white py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
      Save changes
    </button>
  </form>

  <p class="mt-6 text-sm text-slate-500">
    Want to change your password? <a href="<?= APP_URL ?>/forgot-password" class="text-navy font-medium hover:text-alert">Reset it here</a>
  </p>
</main>