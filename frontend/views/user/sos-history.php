<main class="max-w-4xl mx-auto px-6 py-16">
  <div class="flex items-center justify-between flex-wrap gap-4">
    <h1 class="font-display text-3xl font-semibold text-navy">My SOS alerts</h1>
    <a href="<?= APP_URL ?>/user/dashboard" class="text-sm text-navy font-medium hover:text-alert">&larr; Back to dashboard</a>
  </div>
  <p class="mt-2 text-slate-600">Every SOS you've sent, who was notified, and who responded. This page updates by itself.</p>

  <div id="sos-list" class="mt-8 space-y-4">
    <?php if (empty($sos_alerts)): ?>
      <p class="text-slate-500 text-sm">You haven't sent any SOS alerts.</p>
    <?php else: foreach ($sos_alerts as $s): ?>
      <div class="border border-slate-200 rounded-sm bg-white p-5">
        <div class="flex items-center justify-between gap-4 flex-wrap">
          <span class="inline-block text-xs px-2 py-0.5 rounded-sm <?= sos_status_badge_classes($s['status']) ?>">
            <?= e(strtoupper($s['status'])) ?>
          </span>
          <span class="text-xs text-slate-500">Sent <?= e($s['created_at']) ?></span>
        </div>

        <div class="mt-4 grid sm:grid-cols-2 gap-6 text-sm">
          <div>
            <p class="text-slate-500">Nearest official notified</p>
            <?php if ($s['notified_name']): ?>
              <p class="mt-1 font-medium"><?= e($s['notified_name']) ?>, Badge <?= e($s['notified_badge'] ?: 'n/a') ?></p>
              <p class="text-slate-600">Phone: <?= e($s['notified_phone'] ?: 'not provided') ?></p>
            <?php else: ?>
              <p class="mt-1 text-slate-500">No official was on duty when this was sent.</p>
            <?php endif; ?>
          </div>

          <div>
            <p class="text-slate-500">Responding official</p>
            <?php if ($s['responder_name']): ?>
              <p class="mt-1 font-medium"><?= e($s['responder_name']) ?>, Badge <?= e($s['responder_badge'] ?: 'n/a') ?></p>
              <p class="text-slate-600">Phone: <?= e($s['responder_phone'] ?: 'not provided') ?></p>
              <p class="text-xs text-slate-500">Acknowledged <?= e($s['acknowledged_at']) ?></p>
              <?php if ($s['resolved_at']): ?>
                <p class="text-xs text-slate-500">Resolved <?= e($s['resolved_at']) ?></p>
              <?php endif; ?>
            <?php else: ?>
              <p class="mt-1 text-slate-500">Waiting for an official to acknowledge.</p>
            <?php endif; ?>
          </div>
        </div>

        <p class="mt-4 text-xs text-slate-500">
          Location sent<?= $s['accuracy'] !== null ? ' (accurate to about ' . e((string) round((float) $s['accuracy'])) . 'm)' : '' ?>:
          <a href="https://maps.google.com/?q=<?= e($s['latitude']) ?>,<?= e($s['longitude']) ?>" target="_blank"
             class="text-navy hover:text-alert underline">View on map</a>
        </p>
      </div>
    <?php endforeach; endif; ?>
  </div>
</main>

<script>
setInterval(async () => {
  try {
    const res = await fetch(window.location.href);
    const html = await res.text();
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const fresh = doc.getElementById('sos-list');
    if (fresh) document.getElementById('sos-list').innerHTML = fresh.innerHTML;
  } catch (e) {}
}, 5000);
</script>