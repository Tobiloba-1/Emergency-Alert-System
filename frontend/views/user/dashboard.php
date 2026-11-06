<main class="max-w-4xl mx-auto px-6 py-16">
  <h1 class="font-display text-3xl font-semibold text-navy">
    Welcome, <?= e(explode(' ', $user['full_name'])[0]) ?>
  </h1>
  <p class="mt-2 text-slate-600">
    Here's what's been sent to <?= e($user['department'] ?: 'your department') ?> recently.
  </p>

  <div class="mt-8 border-2 border-alert/30 bg-alert/5 rounded-sm p-6">
    <h2 class="font-display text-lg font-semibold text-alert">Emergency</h2>
    <p class="mt-1 text-sm text-slate-600">
      If you are in immediate danger, press the button below. Your location will be sent to the nearest on-duty security official.
    </p>
    <button id="sos-btn" class="mt-4 bg-alert text-white px-8 py-4 rounded-sm font-semibold text-lg hover:bg-alert/90 transition-colors">
      Send SOS
    </button>
    <div id="sos-status" class="mt-3 text-sm"></div>
  </div>

  <div class="mt-10 flex flex-wrap gap-4">
    <a href="<?= APP_URL ?>/user/sos-history" class="border border-navy text-navy px-5 py-2.5 rounded-sm font-medium hover:bg-white transition-colors">
      My SOS alerts
    </a>
    <a href="<?= APP_URL ?>/user/alerts" class="border border-navy text-navy px-5 py-2.5 rounded-sm font-medium hover:bg-white transition-colors">
      View all alerts
    </a>
    <a href="<?= APP_URL ?>/user/preferences" class="border border-navy text-navy px-5 py-2.5 rounded-sm font-medium hover:bg-white transition-colors">
      Notification preferences
    </a>
    <a href="<?= APP_URL ?>/user/profile" class="border border-navy text-navy px-5 py-2.5 rounded-sm font-medium hover:bg-white transition-colors">
      Edit profile
    </a>
  </div>

  <div class="mt-14">
    <h2 class="font-display text-xl font-semibold text-navy">Recent alerts</h2>

    <?php if (empty($recent_alerts)): ?>
      <p class="mt-4 text-slate-500 text-sm">No alerts have been sent to you yet.</p>
    <?php else: ?>
      <div class="mt-4 divide-y divide-slate-200 border border-slate-200 rounded-sm bg-white">
        <?php foreach ($recent_alerts as $alert): ?>
          <a href="<?= APP_URL ?>/user/alert-detail?id= (int) $alert['id'] ?>" class="block p-4 hover:bg-surface transition-colors">
            <span class="inline-block text-xs px-2 py-0.5 rounded-sm <?= severity_badge_classes($alert['severity']) ?>">
              <?= e(strtoupper($alert['severity'])) ?>
            </span>
            <p class="mt-1 font-medium"><?= e($alert['title']) ?></p>
            <p class="text-xs text-slate-500"><?= e($alert['created_at']) ?></p>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>

<script src="<?= APP_URL ?>/assets/js/geo.js"></script>
<script>
const APP_URL = '<?= APP_URL ?>';
const CSRF_TOKEN = '<?= e(Csrf::token()) ?>';
const sosBtn = document.getElementById('sos-btn');
const sosStatus = document.getElementById('sos-status');

function setStatus(message, cssClass) {
  sosStatus.textContent = message;
  sosStatus.className = 'mt-3 text-sm ' + cssClass;
}

function showOfficial(official, accuracy) {
  sosStatus.className = 'mt-3 text-sm text-slate-700';
  sosStatus.textContent = '';

  const heading = document.createElement('p');
  heading.className = 'font-medium text-confirm';
  heading.textContent = 'SOS sent. The nearest on-duty security official has been notified.';

  const details = document.createElement('p');
  details.className = 'mt-1';
  details.textContent = `${official.name}, Badge ${official.badge_id || 'n/a'}, ${official.distance_km} km away. Phone: ${official.phone || 'not provided'}.`;

  const note = document.createElement('p');
  note.className = 'mt-1 text-xs text-slate-500';
  note.textContent = `Your location was sent (accurate to about ${Math.round(accuracy)}m). We'll email you when they acknowledge.`;

  const link = document.createElement('a');
  link.href = APP_URL + '/user/sos-history';
  link.className = 'mt-2 inline-block text-navy font-medium hover:text-alert underline';
  link.textContent = 'Track this SOS';

  sosStatus.append(heading, details, note, link);
}

sosBtn.addEventListener('click', async () => {
  sosBtn.disabled = true;
  sosBtn.textContent = 'Getting your precise location…';
  setStatus('Refining location for a few seconds. Stay on this page.', 'text-slate-500');

  let pos;
  try {
    pos = await getBestPosition();
  } catch (e) {
    setStatus(e.message || 'Location permission is required to send an SOS.', 'text-alert');
    sosBtn.disabled = false;
    sosBtn.textContent = 'Send SOS';
    return;
  }

  sosBtn.textContent = 'Sending SOS…';
  try {
    const res = await fetch(APP_URL + '/sos/trigger', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        csrf_token: CSRF_TOKEN,
        latitude: pos.coords.latitude,
        longitude: pos.coords.longitude,
        accuracy: pos.coords.accuracy,
      }),
    });
    const data = await res.json();
    if (!data.ok) throw new Error(data.error || 'Unknown error');

    sosBtn.textContent = 'SOS sent';
    if (data.official) {
      showOfficial(data.official, pos.coords.accuracy);
    } else {
      setStatus('SOS sent, but no security officials are currently on duty. Please also call campus security directly.', 'text-alert');
    }
  } catch (e) {
    setStatus('Something went wrong sending your SOS. Please call campus security directly.', 'text-alert');
    sosBtn.disabled = false;
    sosBtn.textContent = 'Send SOS';
  }
});
</script>