<main class="max-w-6xl mx-auto px-6 py-16">
  <div class="flex items-center justify-between flex-wrap gap-4">
    <h1 class="font-display text-3xl font-semibold text-navy">User Management</h1>
    <div class="flex items-center gap-6">
      <button type="button" id="open-add-user"
              class="bg-navy text-white px-4 py-2 rounded-sm text-sm font-medium hover:bg-navy-light transition-colors">
        Add user
      </button>
      <a href="<?= APP_URL ?>/admin/dashboard" class="text-sm text-navy font-medium hover:text-alert">&larr; Back to dashboard</a>
    </div>
  </div>

  <?php if (!empty($_GET['created'])): ?>
    <div class="mt-6 border border-confirm/30 bg-confirm/5 text-confirm text-sm rounded-sm p-4">User created.</div>
  <?php endif; ?>

  <div class="mt-8 flex flex-wrap gap-4 items-end">
    <div>
      <label for="role-filter" class="block text-sm font-medium text-slate-700">Role</label>
      <select id="role-filter" class="mt-1 border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        <option value="">All roles</option>
        <option value="student">Student</option>
        <option value="staff">Staff</option>
        <option value="security">Security</option>
        <option value="admin">Admin</option>
      </select>
    </div>
    <div class="flex-1 min-w-[200px]">
      <label for="search-input" class="block text-sm font-medium text-slate-700">Search name or email</label>
      <input type="text" id="search-input" placeholder="Start typing…"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>
    <p id="results-count" class="text-sm text-slate-500 pb-2"></p>
  </div>

  <div class="mt-6 overflow-x-auto border border-slate-200 rounded-sm bg-white">
    <table class="w-full text-sm">
      <thead class="bg-surface text-slate-500 text-left">
        <tr>
          <th class="px-4 py-3 font-medium">Name</th>
          <th class="px-4 py-3 font-medium">Email</th>
          <th class="px-4 py-3 font-medium">Role</th>
          <th class="px-4 py-3 font-medium">Department</th>
          <th class="px-4 py-3 font-medium">Status</th>
          <th class="px-4 py-3 font-medium"></th>
        </tr>
      </thead>
      <tbody id="users-tbody" class="divide-y divide-slate-200">
        <?php foreach ($users as $u): ?>
          <tr data-name="<?= e(strtolower($u['full_name'])) ?>"
              data-email="<?= e(strtolower($u['email'])) ?>"
              data-role="<?= e($u['role']) ?>">
            <td class="px-4 py-3 font-medium"><?= e($u['full_name']) ?></td>
            <td class="px-4 py-3 text-slate-600"><?= e($u['email']) ?></td>
            <td class="px-4 py-3 text-slate-600"><?= e(ucfirst($u['role'])) ?></td>
            <td class="px-4 py-3 text-slate-600"><?= e($u['department'] ?? '') ?></td>
            <td class="px-4 py-3">
              <?php if ((int) $u['is_active']): ?>
                <span class="inline-block text-xs px-2 py-0.5 rounded-sm bg-confirm/10 text-confirm border border-confirm/30">ACTIVE</span>
              <?php else: ?>
                <span class="inline-block text-xs px-2 py-0.5 rounded-sm bg-alert/10 text-alert border border-alert/30">DEACTIVATED</span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 text-right">
              <a href="<?= APP_URL ?>/admin/user-edit?id=<?= (int) $u['id'] ?>" class="text-navy font-medium hover:text-alert">Edit</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p id="no-results" class="px-4 py-6 text-center text-slate-500 hidden">No users match this filter.</p>
  </div>
</main>

<!-- Add user modal -->
<div id="add-user-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="add-user-title">
  <div data-close-modal class="absolute inset-0 bg-navy/60"></div>

  <div class="relative bg-white rounded-sm w-full max-w-md max-h-[90vh] overflow-y-auto border border-slate-200">
    <div class="flex items-start justify-between gap-4 px-6 pt-6">
      <div>
        <h2 id="add-user-title" class="font-display text-xl font-semibold text-navy">Add user</h2>
        <p class="mt-1 text-sm text-slate-500">
          You set a temporary password. Share it with the user, who can change it later with "Forgot password".
        </p>
      </div>
      <button type="button" data-close-modal aria-label="Close"
              class="text-slate-400 hover:text-navy text-2xl leading-none">&times;</button>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="mx-6 mt-4 border border-alert/30 bg-alert/5 text-alert text-sm rounded-sm p-4">
        <ul class="list-disc list-inside space-y-1">
          <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= APP_URL ?>/admin/users" class="px-6 pb-6 mt-4 space-y-5">
      <input type="hidden" name="csrf_token" value="<?= e(Csrf::token()) ?>">

      <div>
        <label for="new_full_name" class="block text-sm font-medium text-slate-700">Full name</label>
        <input type="text" id="new_full_name" name="full_name" required value="<?= e($old['full_name']) ?>"
               class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
      </div>

      <div>
        <label for="new_email" class="block text-sm font-medium text-slate-700">Email</label>
        <input type="email" id="new_email" name="email" required value="<?= e($old['email']) ?>"
               class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="new_role" class="block text-sm font-medium text-slate-700">Role</label>
          <select id="new_role" name="role"
                  class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
            <?php foreach (['student', 'staff', 'security', 'admin'] as $r): ?>
              <option value="<?= $r ?>" <?= $old['role'] === $r ? 'selected' : '' ?>><?= e(ucfirst($r)) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="new_department" class="block text-sm font-medium text-slate-700">Department / Zone</label>
          <input type="text" id="new_department" name="department" value="<?= e($old['department']) ?>"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="new_phone" class="block text-sm font-medium text-slate-700">Phone</label>
          <input type="tel" id="new_phone" name="phone" value="<?= e($old['phone']) ?>"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>
        <div>
          <label for="new_badge_id" class="block text-sm font-medium text-slate-700">Badge / ID</label>
          <input type="text" id="new_badge_id" name="badge_id" value="<?= e($old['badge_id']) ?>"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>
      </div>
      <p id="security-hint" class="text-xs text-slate-500 -mt-3 hidden">Phone and badge are required for security officials.</p>

      <div>
        <label for="new_password" class="block text-sm font-medium text-slate-700">Temporary password</label>
        <input type="password" id="new_password" name="password" required minlength="8"
               class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
      </div>

      <div class="flex gap-3">
        <button type="button" data-close-modal
                class="flex-1 border border-slate-300 text-slate-700 py-3 rounded-sm font-medium hover:bg-surface transition-colors">
          Cancel
        </button>
        <button type="submit"
                class="flex-1 bg-navy text-white py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
          Create user
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// ---- Live filtering ----
const searchInput = document.getElementById('search-input');
const roleFilter = document.getElementById('role-filter');
const rows = Array.from(document.querySelectorAll('#users-tbody tr'));
const resultsCount = document.getElementById('results-count');
const noResults = document.getElementById('no-results');

function applyFilter() {
  const query = searchInput.value.trim().toLowerCase();
  const role = roleFilter.value;
  let visibleCount = 0;

  rows.forEach((row) => {
    const matchesRole = !role || row.dataset.role === role;
    const matchesQuery = !query
      || row.dataset.name.includes(query)
      || row.dataset.email.includes(query);

    const visible = matchesRole && matchesQuery;
    row.classList.toggle('hidden', !visible);
    if (visible) visibleCount++;
  });

  resultsCount.textContent = `${visibleCount} of ${rows.length} users`;
  noResults.classList.toggle('hidden', visibleCount !== 0);
}

searchInput.addEventListener('input', applyFilter);
roleFilter.addEventListener('change', applyFilter);
applyFilter();

// ---- Add user modal ----
const modal = document.getElementById('add-user-modal');
const openBtn = document.getElementById('open-add-user');
const newRole = document.getElementById('new_role');
const newPhone = document.getElementById('new_phone');
const newBadge = document.getElementById('new_badge_id');
const securityHint = document.getElementById('security-hint');

function openModal() {
  modal.classList.remove('hidden');
  modal.classList.add('flex');
  document.body.classList.add('overflow-hidden');
  document.getElementById('new_full_name').focus();
}

function closeModal() {
  modal.classList.add('hidden');
  modal.classList.remove('flex');
  document.body.classList.remove('overflow-hidden');
  openBtn.focus();
}

// Phone and badge become required only for security officials.
function syncRoleFields() {
  const isSecurity = newRole.value === 'security';
  newPhone.required = isSecurity;
  newBadge.required = isSecurity;
  securityHint.classList.toggle('hidden', !isSecurity);
}

openBtn.addEventListener('click', openModal);
document.querySelectorAll('[data-close-modal]').forEach((el) => el.addEventListener('click', closeModal));
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
});
newRole.addEventListener('change', syncRoleFields);
syncRoleFields();

<?php if (!empty($show_modal)): ?>
openModal(); // A failed submit reopens the modal so the errors are visible.
<?php endif; ?>
</script>