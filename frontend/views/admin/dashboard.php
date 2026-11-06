<main class="max-w-6xl mx-auto px-6 py-16">
  <h1 class="font-display text-3xl font-semibold text-navy">Admin Dashboard</h1>
  <p class="mt-2 text-slate-600">Overview of alert activity and campus population.</p>

  <div class="mt-10 grid sm:grid-cols-3 gap-6">
    <div class="border border-slate-200 rounded-sm p-6 bg-white">
      <p class="text-sm text-slate-500">Total alerts sent</p>
      <p class="mt-2 font-display text-3xl font-semibold text-navy"><?= (int) $total_alerts ?></p>
    </div>
    <div class="border border-slate-200 rounded-sm p-6 bg-white">
      <p class="text-sm text-slate-500">Registered students</p>
      <p class="mt-2 font-display text-3xl font-semibold text-navy"><?= (int) $total_students ?></p>
    </div>
    <div class="border border-slate-200 rounded-sm p-6 bg-white">
      <p class="text-sm text-slate-500">Registered staff</p>
      <p class="mt-2 font-display text-3xl font-semibold text-navy"><?= (int) $total_staff ?></p>
    </div>
  </div>

    <div class="mt-10 flex flex-wrap gap-4">
    <a href="<?= APP_URL ?>/admin/broadcast"
       class="bg-navy text-white px-6 py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
      Broadcast a new alert
    </a>
    <a href="<?= APP_URL ?>/admin/history"
       class="border border-navy text-navy px-6 py-3 rounded-sm font-medium hover:bg-white transition-colors">
      View alert history
    </a>
    <a href="<?= APP_URL ?>/admin/users"
       class="border border-navy text-navy px-6 py-3 rounded-sm font-medium hover:bg-white transition-colors">
      Manage users
    </a>
        <a href="<?= APP_URL ?>/admin/settings"
       class="border border-navy text-navy px-6 py-3 rounded-sm font-medium hover:bg-white transition-colors">
      System settings
    </a>
    <a href="<?= APP_URL ?>/admin/reports"
       class="border border-navy text-navy px-6 py-3 rounded-sm font-medium hover:bg-white transition-colors">
      Reports & analytics
    </a>
        <a href="<?= APP_URL ?>/admin/sos-alerts"
       class="border border-navy text-navy px-6 py-3 rounded-sm font-medium hover:bg-white transition-colors">
      SOS alerts
    </a>
  </div>
  </div>

  <div class="mt-14">
    <h2 class="font-display text-xl font-semibold text-navy">Recent alerts</h2>

    <?php if (empty($recent_alerts)): ?>
      <p class="mt-4 text-slate-500 text-sm">No alerts have been sent yet.</p>
    <?php else: ?>
      <div class="mt-4 divide-y divide-slate-200 border border-slate-200 rounded-sm bg-white">
        <?php foreach ($recent_alerts as $alert): ?>
          <div class="p-4 flex items-center justify-between gap-4">
            <div>
              <span class="inline-block text-xs px-2 py-0.5 rounded-sm <?= severity_badge_classes($alert['severity']) ?>">
                <?= e(strtoupper($alert['severity'])) ?>
              </span>
              <p class="mt-1 font-medium"><?= e($alert['title']) ?></p>
              <p class="text-xs text-slate-500">
                Sent by <?= e($alert['sender_name']) ?> · <?= e($alert['created_at']) ?>
              </p>
            </div>
            <div class="text-sm text-slate-500 whitespace-nowrap">
              <?= (int) $alert['sent_count'] ?>/<?= (int) $alert['recipients_count'] ?> delivered
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>