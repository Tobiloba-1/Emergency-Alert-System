<main class="max-w-md mx-auto px-6 py-16">
  <div class="flex items-center justify-between">
    <h1 class="font-display text-2xl font-semibold text-navy">System settings</h1>
    <a href="<?= APP_URL ?>/admin/dashboard" class="text-sm text-navy font-medium hover:text-alert">&larr; Back</a>
  </div>
  <p class="mt-2 text-slate-600 text-sm">Controls the sender identity used on every outgoing alert email.</p>

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
      <label for="mail_from_name" class="block text-sm font-medium text-slate-700">Sender name</label>
      <input type="text" id="mail_from_name" name="mail_from_name" required value="<?= e($settings['mail_from_name']) ?>"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <div>
      <label for="mail_from_email" class="block text-sm font-medium text-slate-700">Sender email</label>
      <input type="email" id="mail_from_email" name="mail_from_email" required value="<?= e($settings['mail_from_email']) ?>"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <div>
      <label for="email_footer" class="block text-sm font-medium text-slate-700">Email footer <span class="text-slate-400">(optional)</span></label>
      <textarea id="email_footer" name="email_footer" rows="3"
                class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy"><?= e($settings['email_footer']) ?></textarea>
    </div>

    <button type="submit" class="w-full bg-navy text-white py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
      Save settings
    </button>
  </form>
</main>