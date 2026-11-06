<main class="max-w-2xl mx-auto px-6 py-16">
  <h1 class="font-display text-3xl font-semibold text-navy">Broadcast an alert</h1>
  <p class="mt-2 text-slate-600">This sends an immediate email to everyone matching your selected audience.</p>

  <?php if (!empty($errors)): ?>
    <div class="mt-6 border border-alert/30 bg-alert/5 text-alert text-sm rounded-sm p-4">
      <ul class="list-disc list-inside space-y-1">
        <?php foreach ($errors as $err): ?>
          <li><?= e($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" class="mt-8 space-y-6 bg-white border border-slate-200 rounded-sm p-8">
    <input type="hidden" name="csrf_token" value="<?= e(Csrf::token()) ?>">

    <div>
      <label for="title" class="block text-sm font-medium text-slate-700">Alert title</label>
      <input type="text" id="title" name="title" required
             value="<?= e($old['title']) ?>"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <div>
      <label for="message" class="block text-sm font-medium text-slate-700">Message</label>
      <textarea id="message" name="message" required rows="5"
                class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy"><?= e($old['message']) ?></textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label for="severity" class="block text-sm font-medium text-slate-700">Severity</label>
        <select id="severity" name="severity"
                class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
          <option value="info" <?= $old['severity'] === 'info' ? 'selected' : '' ?>>Info</option>
          <option value="warning" <?= $old['severity'] === 'warning' ? 'selected' : '' ?>>Warning</option>
          <option value="critical" <?= $old['severity'] === 'critical' ? 'selected' : '' ?>>Critical</option>
        </select>
      </div>
      <div>
        <label for="target_role" class="block text-sm font-medium text-slate-700">Audience</label>
        <select id="target_role" name="target_role"
                class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
          <option value="all" <?= $old['target_role'] === 'all' ? 'selected' : '' ?>>Everyone</option>
          <option value="student" <?= $old['target_role'] === 'student' ? 'selected' : '' ?>>Students only</option>
          <option value="staff" <?= $old['target_role'] === 'staff' ? 'selected' : '' ?>>Staff only</option>
        </select>
      </div>
    </div>

    <div>
      <label for="target_department" class="block text-sm font-medium text-slate-700">
        Department / Hall <span class="text-slate-400">(optional — leave blank for all)</span>
      </label>
      <input type="text" id="target_department" name="target_department"
             value="<?= e($old['target_department']) ?>"
             class="mt-1 w-full border border-slate-300 rounded-sm px-3 py-2 focus:border-navy focus:ring-1 focus:ring-navy">
    </div>

    <button type="submit"
            class="w-full bg-alert text-white py-3 rounded-sm font-medium hover:bg-alert/90 transition-colors">
      Send alert now
    </button>
  </form>
</main>