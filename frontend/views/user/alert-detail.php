<main class="max-w-2xl mx-auto px-6 py-16">
  <?php if (!$alert): ?>
    <h1 class="font-display text-2xl font-semibold text-navy">Alert not found</h1>
    <p class="mt-2 text-slate-600 text-sm">This alert may have been removed or the link is incorrect.</p>
    <p class="mt-6"><a href="<?= APP_URL ?>/user/alerts" class="text-navy font-medium hover:text-alert">Back to my alerts</a></p>
  <?php else: ?>
    <span class="inline-block text-xs px-2 py-0.5 rounded-sm <?= severity_badge_classes($alert['severity']) ?>">
      <?= e(strtoupper($alert['severity'])) ?>
    </span>
    <h1 class="mt-3 font-display text-3xl font-semibold text-navy"><?= e($alert['title']) ?></h1>
    <p class="mt-1 text-sm text-slate-500"><?= e($alert['created_at']) ?></p>

    <div class="mt-6 bg-white border border-slate-200 rounded-sm p-6 whitespace-pre-line text-slate-700">
      <?= e($alert['message']) ?>
    </div>

    <p class="mt-6"><a href="<?= APP_URL ?>/user/alerts" class="text-navy font-medium hover:text-alert">Back to my alerts</a></p>
  <?php endif; ?>
</main>