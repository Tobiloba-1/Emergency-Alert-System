<main class="max-w-md mx-auto px-6 py-16">
  <h1 class="font-display text-2xl font-semibold text-navy">Notification preferences</h1>
  <p class="mt-2 text-slate-600 text-sm">Choose which alert categories you receive by email.</p>

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

  <form method="post" class="mt-6 space-y-4 bg-white border border-slate-200 rounded-sm p-6">
    <input type="hidden" name="csrf_token" value="<?= e(Csrf::token()) ?>">

    <label class="flex items-center gap-3 text-sm">
      <input type="checkbox" name="notify_critical" <?= $user['notify_critical'] ? 'checked' : '' ?>
             class="rounded border-slate-300 text-alert focus:ring-alert">
      Critical alerts <span class="text-slate-400">(recommended: always on)</span>
    </label>
    <label class="flex items-center gap-3 text-sm">
      <input type="checkbox" name="notify_warning" <?= $user['notify_warning'] ? 'checked' : '' ?>
             class="rounded border-slate-300 text-navy focus:ring-navy">
      Warning alerts
    </label>
    <label class="flex items-center gap-3 text-sm">
      <input type="checkbox" name="notify_info" <?= $user['notify_info'] ? 'checked' : '' ?>
             class="rounded border-slate-300 text-navy focus:ring-navy">
      Informational alerts
    </label>

    <button type="submit" class="w-full bg-navy text-white py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
      Save preferences
    </button>
  </form>
</main>