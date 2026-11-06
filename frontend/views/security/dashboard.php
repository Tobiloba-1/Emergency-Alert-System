<main class="max-w-4xl mx-auto px-6 py-16">
  <div class="flex items-center justify-between flex-wrap gap-4">
    <div>
      <h1 class="font-display text-3xl font-semibold text-navy">Security Dashboard</h1>
      <p class="mt-2 text-slate-600">
        Signed in as <?= e($user['full_name']) ?><?= $user['badge_id'] ? ' · Badge ' . e($user['badge_id']) : '' ?>
      </p>
    </div>
    <div class="flex items-center gap-3">
      <span id="duty-status" class="text-sm font-medium <?= $user['on_duty'] ? 'text-confirm' : 'text-slate-400' ?>">
        <?= $user['on_duty'] ? 'On duty' : 'Off duty' ?>
      </span>
      <button id="duty-toggle"
              class="px-5 py-2.5 rounded-sm font-medium transition-colors <?= $user['on_duty'] ? 'bg-alert text-white hover:bg-alert/90' : 'bg-navy text-white hover:bg-navy-light' ?>">
        <?= $user['on_duty'] ? 'Go off duty' : 'Go on duty' ?>
      </button>
    </div>
  </div>

  <p id="location-warning" class="mt-4 text-sm text-alert hidden">
    Location permission is required to go on duty. Please allow location access and try again.
  </p>

  <div class="mt-12">
    <h2 class="font-display text-xl font-semibold text-navy">Active SOS alerts</h2>
    <div id="alerts-list" class="mt-4 space-y-3">
      <p class="text-slate-500 text-sm">Loading…</p>
    </div>
  </div>
</main>

<script src="<?= APP_URL ?>/assets/js/geo.js"></script>
<script>
const APP_URL = '<?= APP_URL ?>';
const CSRF_TOKEN = '<?= e(Csrf::token()) ?>';
let onDuty = <?= $user['on_duty'] ? 'true' : 'false' ?>;
let locationInterval = null;

const dutyToggle = document.getElementById('duty-toggle');
const dutyStatus = document.getElementById('duty-status');
const locationWarning = document.getElementById('location-warning');
const alertsList = document.getElementById('alerts-list');

function setDutyUI(isOn) {
  onDuty = isOn;
  dutyStatus.textContent = isOn ? 'On duty' : 'Off duty';
  dutyStatus.className = 'text-sm font-medium ' + (isOn ? 'text-confirm' : 'text-slate-400');
  dutyToggle.textContent = isOn ? 'Go off duty' : 'Go on duty';
  dutyToggle.className = 'px-5 py-2.5 rounded-sm font-medium transition-colors ' +
    (isOn ? 'bg-alert text-white hover:bg-alert/90' : 'bg-navy text-white hover:bg-navy-light');
}

async function postJSON(path, body) {
  const res = await fetch(APP_URL + path, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ csrf_token: CSRF_TOKEN, ...body }),
  });
  return res.json();
}

async function sendLocationUpdate() {
  try {
    const pos = await getBestPosition(5000, 30);
    postJSON('/security/update-location', {
      latitude: pos.coords.latitude,
      longitude: pos.coords.longitude,
      accuracy: pos.coords.accuracy,
    });
  } catch (e) {}
}

dutyToggle.addEventListener('click', async () => {
  locationWarning.classList.add('hidden');

  if (onDuty) {
    postJSON('/security/toggle-duty', { on_duty: false }).then(() => {
      setDutyUI(false);
      if (locationInterval) clearInterval(locationInterval);
    });
    return;
  }

  dutyToggle.disabled = true;
  dutyToggle.textContent = 'Getting your location…';

  try {
    const pos = await getBestPosition();
    await postJSON('/security/toggle-duty', {
      on_duty: true,
      latitude: pos.coords.latitude,
      longitude: pos.coords.longitude,
      accuracy: pos.coords.accuracy,
    });
    setDutyUI(true);
    locationInterval = setInterval(sendLocationUpdate, 15000);
  } catch (e) {
    locationWarning.textContent = e.message || 'Location permission is required to go on duty.';
    locationWarning.classList.remove('hidden');
  } finally {
    dutyToggle.disabled = false;
  }
});

if (onDuty) {
  locationInterval = setInterval(sendLocationUpdate, 15000);
}

async function loadAlerts() {
  try {
    const res = await fetch(APP_URL + '/security/active-alerts');
    const data = await res.json();
    renderAlerts(data.alerts || []);
  } catch (e) {
    alertsList.innerHTML = '<p class="text-slate-500 text-sm">Unable to load alerts.</p>';
  }
}

function renderAlerts(alerts) {
  if (alerts.length === 0) {
    alertsList.innerHTML = '<p class="text-slate-500 text-sm">No active SOS alerts right now.</p>';
    return;
  }

  alertsList.innerHTML = alerts.map((a) => {
    const mapsLink = `https://maps.google.com/?q=${a.latitude},${a.longitude}`;
    const distance = a.distance_km !== null ? `${a.distance_km} km away` : 'Distance unknown (go on duty to rank by distance)';
    const accuracyNote = a.accuracy !== null ? ` · ±${Math.round(a.accuracy)}m accuracy` : '';

    let statusBadge, actionButton;
    if (a.status === 'acknowledged') {
      statusBadge = '<span class="inline-block text-xs px-2 py-0.5 rounded-sm bg-warning/10 text-warning border border-warning/30">ACKNOWLEDGED</span>';
      actionButton = `<button data-id="${a.id}" class="resolve-btn bg-confirm text-white px-4 py-2 rounded-sm text-sm font-medium hover:bg-confirm/90 transition-colors">Mark resolved</button>`;
    } else {
      statusBadge = '<span class="inline-block text-xs px-2 py-0.5 rounded-sm bg-alert/10 text-alert border border-alert/30">ACTIVE</span>';
      actionButton = `<button data-id="${a.id}" class="ack-btn bg-navy text-white px-4 py-2 rounded-sm text-sm font-medium hover:bg-navy-light transition-colors">Acknowledge</button>`;
    }

    return `
      <div class="border border-slate-200 rounded-sm p-4 bg-white flex items-center justify-between gap-4 flex-wrap">
        <div>
          ${statusBadge}
          <p class="mt-1 font-medium">${a.student_name} <span class="text-slate-400 font-normal">· ${a.student_department || 'No department'}</span></p>
          <p class="text-xs text-slate-500">${distance}${accuracyNote} · <a href="${mapsLink}" target="_blank" class="text-navy hover:text-alert underline">View on map</a></p>
        </div>
        ${actionButton}
      </div>
    `;
  }).join('');

  document.querySelectorAll('.ack-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
      btn.disabled = true;
      btn.textContent = 'Acknowledging…';
      await postJSON('/security/acknowledge', { alert_id: parseInt(btn.dataset.id, 10) });
      loadAlerts();
    });
  });

  document.querySelectorAll('.resolve-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
      btn.disabled = true;
      btn.textContent = 'Resolving…';
      await postJSON('/security/resolve', { alert_id: parseInt(btn.dataset.id, 10) });
      loadAlerts();
    });
  });
}

loadAlerts();
setInterval(loadAlerts, 5000);
</script>