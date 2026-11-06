<main class="max-w-md mx-auto px-6 py-16">
  <a href="users.php" class="text-sm text-navy font-medium hover:text-alert">&larr; Back to user list</a>
  <h1 class="mt-4 font-display text-2xl font-semibold text-navy">Add user</h1>
  <p class="mt-1 text-sm text-slate-500">
    You set a temporary password. Share it with the user, who can change it later with "Forgot password".
  </p>

  <?php if (!empty($errors)): ?>
    <div class="mt-6 border border-alert/30 bg-alert/5 text-alert text-sm rounded-sm p-4">
      <ul class="list-disc list-inside space-y-1">
        <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" class="mt-6 space-y-5 bg-white border border-slate-200 rounded-sm p-6">
    <input type="hidden" name="csrf_token" value="<?= e(Csrf::token()) ?>">

    <div>
      <label for="full_name" class="block text-sm font-medium text-slate-700">Full name</label>
      <input type="text" id="full_name" name="full_name" required value="<?= e($old['full_name']) ?>"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <div>
      <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
      <input type="email" id="email" name="email" required value="<?= e($old['email']) ?>"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <div>
      <label for="role" class="block text-sm font-medium text-slate-700">Role</label>
      <select id="role" name="role" class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
        <?php foreach (['student', 'staff', 'security', 'admin'] as $r): ?>
          <option value="<?= $r ?>" <?= $old['role'] === $r ? 'selected' : '' ?>><?= e(ucfirst($r)) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label for="department" class="block text-sm font-medium text-slate-700">Department / Hall / Zone</label>
      <input type="text" id="department" name="department" value="<?= e($old['department']) ?>"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
        <input type="tel" id="phone" name="phone" value="<?= e($old['phone']) ?>"
               class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
      </div>
      <div>
        <label for="badge_id" class="block text-sm font-medium text-slate-700">Badge / ID</label>
        <input type="text" id="badge_id" name="badge_id" value="<?= e($old['badge_id']) ?>"
               class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
      </div>
    </div>
    <p class="text-xs text-slate-500 -mt-3">Phone and badge are required for security officials.</p>

    <div>
      <label for="password" class="block text-sm font-medium text-slate-700">Temporary password</label>
      <input type="password" id="password" name="password" required minlength="8"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <button type="submit" class="w-full bg-navy text-white py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
      Create user
    </button>
  </form>
</main>