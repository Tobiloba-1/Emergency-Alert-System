<main class="max-w-md mx-auto px-6 py-16">
  <a href="<?= APP_URL ?>/admin/users" class="text-sm text-navy font-medium hover:text-alert">&larr; Back to user list</a>

  <?php if (!$user): ?>
    <h1 class="mt-4 font-display text-2xl font-semibold text-navy">User not found</h1>
  <?php else: ?>
    <h1 class="mt-4 font-display text-2xl font-semibold text-navy">Edit user</h1>
    <p class="mt-1 text-sm text-slate-500"><?= e($user['full_name']) ?> · <?= e($user['email']) ?></p>

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
      <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">

      <div>
        <label for="role" class="block text-sm font-medium text-slate-700">Role</label>
        <select id="role" name="role" class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
          <?php foreach (['student', 'staff', 'security', 'admin'] as $r): ?>
            <option value="<?= $r ?>" <?= $user['role'] === $r ? 'selected' : '' ?>><?= e(ucfirst($r)) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="department" class="block text-sm font-medium text-slate-700">Department / Zone</label>
        <input type="text" id="department" name="department" value="<?= e($user['department'] ?? '') ?>"
               class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
          <input type="tel" id="phone" name="phone" value="<?= e($user['phone'] ?? '') ?>"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>
        <div>
          <label for="badge_id" class="block text-sm font-medium text-slate-700">Badge / ID</label>
          <input type="text" id="badge_id" name="badge_id" value="<?= e($user['badge_id'] ?? '') ?>"
                 class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        </div>
      </div>

      <label class="flex items-center gap-3 text-sm">
        <input type="checkbox" name="is_active" <?= (int) $user['is_active'] ? 'checked' : '' ?>
               class="rounded border-slate-300 text-navy focus:ring-navy">
        Account active (uncheck to block login)
      </label>

      <button type="submit" class="w-full bg-navy text-white py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
        Save changes
      </button>
    </form>
  <?php endif; ?>
</main>