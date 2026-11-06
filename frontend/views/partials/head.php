<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($page_title) ? e($page_title) . ' · ' . APP_NAME : APP_NAME ?></title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          navy: { DEFAULT: '#101B3D', light: '#1B2A52' },
          ink: '#1A1F2B',
          surface: '#F6F7FA',
          alert: '#C8443C',
          confirm: '#1D7A6E',
          warning: '#B45309',
        },
        fontFamily: {
          display: ['"Space Grotesk"', 'sans-serif'],
          body: ['"IBM Plex Sans"', 'sans-serif'],
        },
      },
    },
  };
</script>

<style>
  body { font-family: 'IBM Plex Sans', sans-serif; }
  .font-display { font-family: 'Space Grotesk', sans-serif; }
  :focus-visible { outline: 2px solid #C8443C; outline-offset: 2px; }
</style>
</head>
<body class="bg-surface text-ink">

<nav class="border-b border-slate-200 bg-white">
  <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
    <a href="<?= APP_URL ?>/" class="font-display text-lg font-semibold text-navy">Campus Alert</a>
    <div class="flex items-center gap-6 text-sm font-medium">
      <?php if (Session::isLoggedIn()): ?>
        <?php if (Session::role() === 'admin'): ?>
          <a href="<?= APP_URL ?>/admin/dashboard" class="text-navy hover:text-alert transition-colors">Admin Panel</a>
        <?php elseif (Session::role() === 'security'): ?>
          <a href="<?= APP_URL ?>/security/dashboard" class="text-navy hover:text-alert transition-colors">Security Panel</a>
        <?php else: ?>
          <a href="<?= APP_URL ?>/user/dashboard" class="text-navy hover:text-alert transition-colors">My Dashboard</a>
        <?php endif; ?>
        <span class="text-slate-500">Signed in as <?= e(Session::userName()) ?></span>
        <a href="<?= APP_URL ?>/logout" class="text-navy hover:text-alert transition-colors">Log out</a>
      <?php else: ?>
        <a href="<?= APP_URL ?>/login" class="text-navy hover:text-alert transition-colors">Log in</a>
        <a href="<?= APP_URL ?>/register" class="bg-navy text-white px-4 py-2 rounded-sm hover:bg-navy-light transition-colors">
          Register
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>