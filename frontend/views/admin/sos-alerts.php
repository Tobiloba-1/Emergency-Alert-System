<main class="max-w-6xl mx-auto px-6 py-16">
  <div class="flex items-center justify-between flex-wrap gap-4">
    <h1 class="font-display text-3xl font-semibold text-navy">SOS alerts</h1>
    <a href="<?= APP_URL ?>/admin/dashboard" class="text-sm text-navy font-medium hover:text-alert">&larr; Back to dashboard</a>
  </div>

  <div class="mt-8 flex flex-wrap gap-6 items-end">
    <div>
      <label for="status-filter" class="block text-sm font-medium text-slate-700">Status</label>
      <select id="status-filter" class="mt-1 border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        <option value="">All</option>
        <option value="active">Active</option>
        <option value="acknowledged">Acknowledged</option>
        <option value="resolved">Resolved</option>
      </select>
    </div>
    <p id="status-summary" class="text-sm text-slate-500 pb-2"></p>
  </div>

  <div class="mt-6 overflow-x-auto border border-slate-200 rounded-sm bg-white">
    <table class="w-full text-sm">
      <thead class="bg-surface text-slate-500 text-left">
        <tr>
          <th class="px-4 py-3 font-medium">Sent</th>
          <th class="px-4 py-3 font-medium">Student</th>
          <th class="px-4 py-3 font-medium">Status</th>
          <th class="px-4 py-3 font-medium">Official notified</th>
          <th class="px-4 py-3 font-medium">Responder</th>
          <th class="px-4 py-3 font-medium">Location</th>
        </tr>
      </thead>
      <tbody id="sos-tbody" class="divide-y divide-slate-200">
        <?php foreach ($sos_alerts as $s): ?>
          <tr data-status="<?= e($s['status']) ?>">
            <td class="px-4 py-3 whitespace-nowrap text-slate-500"><?= e($s['created_at']) ?></td>
            <td class="px-4 py-3">
              <p class="font-medium"><?= e($s['student_name']) ?></p>
              <p class="text-xs text-slate-500">
                <?= e($s['student_department'] ?: 'No department') ?>, <?= e($s['student_phone'] ?: 'no phone') ?>
              </p>
            </td>
            <td class="px-4 py-3">
              <span class="inline-block text-xs px-2 py-0.5 rounded-sm <?= sos_status_badge_classes($s['status']) ?>">
                <?= e(strtoupper($s['status'])) ?>
              </span>
            </td>
            <td class="px-4 py-3 text-slate-600">
              <?php if ($s['notified_name']): ?>
                <?= e($s['notified_name']) ?> <span class="text-slate-400">(<?= e($s['notified_badge'] ?: 'n/a') ?>)</span>
              <?php else: ?>
                <span class="text-slate-400">None on duty</span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 text-slate-600">
              <?php if ($s['responder_name']): ?>
                <?= e($s['responder_name']) ?> <span class="text-slate-400">(<?= e($s['responder_badge'] ?: 'n/a') ?>)</span>
                <p class="text-xs text-slate-500">Acknowledged <?= e($s['acknowledged_at']) ?></p>
                <?php if ($s['resolved_at']): ?>
                  <p class="text-xs text-slate-500">Resolved <?= e($s['resolved_at']) ?></p>
                <?php endif; ?>
              <?php else: ?>
                <span class="text-slate-400">Unacknowledged</span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
              <a href="https://maps.google.com/?q=<?= e($s['latitude']) ?>,<?= e($s['longitude']) ?>" target="_blank"
                 class="text-navy hover:text-alert underline">View on map</a>
              <?php if ($s['accuracy'] !== null): ?>
                <p class="text-xs text-slate-500">&plusmn;<?= e((string) round((float) $s['accuracy'])) ?>m</p>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p id="no-results" class="px-4 py-6 text-center text-slate-500 hidden">No SOS alerts match this filter.</p>
  </div>
</main>

<script>
const statusFilter = document.getElementById('status-filter');
const summary = document.getElementById('status-summary');
const noResults = document.getElementById('no-results');

function applyFilter() {
  const rows = Array.from(document.querySelectorAll('#sos-tbody tr'));
  const wanted = statusFilter.value;
  const counts = { active: 0, acknowledged: 0, resolved: 0 };
  let visible = 0;

  rows.forEach((row) => {
    counts[row.dataset.status] = (counts[row.dataset.status] || 0) + 1;
    const show = !wanted || row.dataset.status === wanted;
    row.classList.toggle('hidden', !show);
    if (show) visible++;
  });

  summary.textContent = `${counts.active} active, ${counts.acknowledged} acknowledged, ${counts.resolved} resolved`;
  noResults.classList.toggle('hidden', visible !== 0);
}

statusFilter.addEventListener('change', applyFilter);
applyFilter();

// Refresh the table in place every 5 seconds, keeping the current filter.
setInterval(async () => {
  try {
    const res = await fetch(window.location.href);
    const html = await res.text();
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const fresh = doc.getElementById('sos-tbody');
    if (fresh) {
      document.getElementById('sos-tbody').innerHTML = fresh.innerHTML;
      applyFilter();
    }
  } catch (e) {}
}, 5000);
</script>