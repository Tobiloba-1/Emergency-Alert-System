<main class="max-w-6xl mx-auto px-6 py-16">
  <h1 class="font-display text-3xl font-semibold text-navy">Alert history</h1>

  <?php if ($just_sent): ?>
    <div class="mt-6 border border-confirm/30 bg-confirm/5 text-confirm text-sm rounded-sm p-4">
      Alert sent successfully.
    </div>
  <?php endif; ?>

  <?php if (empty($alerts)): ?>
    <p class="mt-8 text-slate-500 text-sm">No alerts have been sent yet.</p>
  <?php else: ?>
    <div class="mt-8 overflow-x-auto border border-slate-200 rounded-sm bg-white">
      <table class="w-full text-sm">
        <thead class="bg-surface text-slate-500 text-left">
          <tr>
            <th class="px-4 py-3 font-medium">Sent</th>
            <th class="px-4 py-3 font-medium">Severity</th>
            <th class="px-4 py-3 font-medium">Title</th>
            <th class="px-4 py-3 font-medium">Audience</th>
            <th class="px-4 py-3 font-medium">Sender</th>
            <th class="px-4 py-3 font-medium">Delivered</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <?php foreach ($alerts as $alert): ?>
            <tr>
              <td class="px-4 py-3 whitespace-nowrap text-slate-500"><?= e($alert['created_at']) ?></td>
              <td class="px-4 py-3">
                <span class="inline-block text-xs px-2 py-0.5 rounded-sm <?= severity_badge_classes($alert['severity']) ?>">
                  <?= e(strtoupper($alert['severity'])) ?>
                </span>
              </td>
              <td class="px-4 py-3 font-medium"><?= e($alert['title']) ?></td>
              <td class="px-4 py-3 text-slate-600">
                <?= e(ucfirst($alert['target_role'])) ?><?= $alert['target_department'] ? ' · ' . e($alert['target_department']) : '' ?>
              </td>
              <td class="px-4 py-3 text-slate-600"><?= e($alert['sender_name']) ?></td>
              <td class="px-4 py-3 whitespace-nowrap">
                <?= (int) $alert['sent_count'] ?>/<?= (int) $alert['recipients_count'] ?>
                <?php if ((int) $alert['failed_count'] > 0): ?>
                  <span class="text-alert">(<?= (int) $alert['failed_count'] ?> failed)</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</main>