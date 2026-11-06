<main class="max-w-6xl mx-auto px-6 py-16">
  <div class="flex items-center justify-between flex-wrap gap-4">
    <h1 class="font-display text-3xl font-semibold text-navy">Reports & Analytics</h1>
    <a href="<?= APP_URL ?>/admin/dashboard" class="text-sm text-navy font-medium hover:text-alert">&larr; Back to dashboard</a>
  </div>

  <!-- Top-line stats -->
  <div class="mt-10 grid sm:grid-cols-4 gap-6">
    <div class="border border-slate-200 rounded-sm p-6 bg-white">
      <p class="text-sm text-slate-500">Total alerts sent</p>
      <p class="mt-2 font-display text-3xl font-semibold text-navy"><?= (int) $total_alerts ?></p>
    </div>
    <div class="border border-slate-200 rounded-sm p-6 bg-white">
      <p class="text-sm text-slate-500">Delivery success rate</p>
      <p class="mt-2 font-display text-3xl font-semibold text-navy">
        <?= $delivery_stats['success_rate'] !== null ? e($delivery_stats['success_rate']) . '%' : '—' ?>
      </p>
      <p class="text-xs text-slate-400 mt-1">
        <?= (int) $delivery_stats['total_sent'] ?>/<?= (int) $delivery_stats['total_recipients'] ?> delivered
      </p>
    </div>
    <div class="border border-slate-200 rounded-sm p-6 bg-white">
      <p class="text-sm text-slate-500">SOS alerts triggered</p>
      <p class="mt-2 font-display text-3xl font-semibold text-navy"><?= (int) $sos_total ?></p>
      <p class="text-xs text-slate-400 mt-1"><?= (int) $sos_active ?> currently open</p>
    </div>
    <div class="border border-slate-200 rounded-sm p-6 bg-white">
      <p class="text-sm text-slate-500">Avg. time to acknowledge</p>
      <p class="mt-2 font-display text-3xl font-semibold text-navy">
        <?= $sos_response['avg_ack_minutes'] !== null ? e($sos_response['avg_ack_minutes']) . ' min' : '—' ?>
      </p>
      <p class="text-xs text-slate-400 mt-1">
        Resolve: <?= $sos_response['avg_resolve_minutes'] !== null ? e($sos_response['avg_resolve_minutes']) . ' min' : '—' ?>
      </p>
    </div>
  </div>

  <!-- Alert volume over time -->
  <div class="mt-12 bg-white border border-slate-200 rounded-sm p-6">
    <h2 class="font-display text-lg font-semibold text-navy">Alert volume — last 30 days</h2>
    <canvas id="volumeChart" class="mt-4" height="80"></canvas>
  </div>

  <!-- Breakdown charts -->
  <div class="mt-8 grid md:grid-cols-2 gap-8">
    <div class="bg-white border border-slate-200 rounded-sm p-6">
      <h2 class="font-display text-lg font-semibold text-navy">Alerts by severity</h2>
      <canvas id="severityChart" class="mt-4" height="200"></canvas>
    </div>
    <div class="bg-white border border-slate-200 rounded-sm p-6">
      <h2 class="font-display text-lg font-semibold text-navy">Alerts by audience</h2>
      <canvas id="audienceChart" class="mt-4" height="200"></canvas>
    </div>
  </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const dailyCounts = <?= json_encode($daily_counts) ?>;
const severity = <?= json_encode($severity_breakdown) ?>;
const audience = <?= json_encode($target_breakdown) ?>;

// Colors pulled from the app's own design tokens.
const navy = '#101B3D';
const navyLight = '#1B2A52';
const alert = '#C8443C';
const warning = '#B45309';
const confirm = '#1D7A6E';

new Chart(document.getElementById('volumeChart'), {
  type: 'line',
  data: {
    labels: dailyCounts.map(d => d.date.slice(5)), // MM-DD
    datasets: [{
      label: 'Alerts sent',
      data: dailyCounts.map(d => d.count),
      borderColor: navy,
      backgroundColor: 'rgba(16,27,61,0.08)',
      fill: true,
      tension: 0.3,
      pointRadius: 0,
    }],
  },
  options: {
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
  },
});

new Chart(document.getElementById('severityChart'), {
  type: 'bar',
  data: {
    labels: ['Info', 'Warning', 'Critical'],
    datasets: [{
      data: [severity.info, severity.warning, severity.critical],
      backgroundColor: [navy, warning, alert],
    }],
  },
  options: {
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
  },
});

new Chart(document.getElementById('audienceChart'), {
  type: 'doughnut',
  data: {
    labels: ['Everyone', 'Students only', 'Staff only'],
    datasets: [{
      data: [audience.all, audience.student, audience.staff],
      backgroundColor: [navy, navyLight, confirm],
    }],
  },
  options: {
    plugins: { legend: { position: 'bottom' } },
  },
});
</script>