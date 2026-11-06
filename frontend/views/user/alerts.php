<main class="max-w-4xl mx-auto px-6 py-16">
  <h1 class="font-display text-3xl font-semibold text-navy">My Alerts</h1>
  <p class="mt-2 text-slate-600">All alerts sent to your role and department.</p>

  <?php if (empty($alerts)): ?>
    <p class="mt-8 text-slate-500 text-sm">No alerts yet.</p>
  <?php else: ?>
    <div class="mt-8 divide-y divide-slate-200 border border-slate-200 rounded-sm bg-white">
      <?php foreach ($alerts as $alert): ?>
        <a href="<?= APP_URL ?>/user/alert-detail?id=<?= (int) $alert['id'] ?>" class="block p-4 hover:bg-surface transition-colors">
          <span class="inline-block text-xs px-2 py-0.5 rounded-sm <?= severity_badge_classes($alert['severity']) ?>">
            <?= e(strtoupper($alert['severity'])) ?>
          </span>
          <p class="mt-1 font-medium"><?= e($alert['title']) ?></p>
          <p class="text-xs text-slate-500"><?= e($alert['created_at']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>