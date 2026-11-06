<main>
  <!-- Hero -->
  <section class="max-w-6xl mx-auto px-6 pt-16 pb-20 grid md:grid-cols-2 gap-12 items-center">
    <div>
      <h1 class="font-display text-4xl sm:text-5xl font-semibold text-navy leading-tight">
        One alert reaches everyone who needs it — in seconds.
      </h1>
      <p class="mt-6 text-slate-600 text-lg max-w-md">
        The campus emergency alert system connects students, staff, and campus security
        through a single, reliable channel — so when something happens, the right people
        know immediately, not eventually.
      </p>
      <div class="mt-8 flex flex-wrap gap-4">
        <a href="<?= APP_URL ?>/register"
           class="bg-navy text-white px-6 py-3 rounded-sm font-medium hover:bg-navy-light transition-colors">
          Create an account
        </a>
        <a href="<?= APP_URL ?>/login"
           class="border border-navy text-navy px-6 py-3 rounded-sm font-medium hover:bg-white transition-colors">
          Log in
        </a>
      </div>
    </div>

    <div class="flex justify-center">
      <svg viewBox="0 0 320 260" class="w-full max-w-sm" role="img" aria-label="Network coverage diagram">
        <g stroke="#101B3D" stroke-width="1.2" opacity="0.35">
          <line x1="60" y1="60" x2="160" y2="120" />
          <line x1="160" y1="120" x2="260" y2="70" />
          <line x1="160" y1="120" x2="220" y2="200" />
          <line x1="160" y1="120" x2="80" y2="190" />
          <line x1="60" y1="60" x2="80" y2="190" />
          <line x1="260" y1="70" x2="220" y2="200" />
        </g>
        <g>
          <circle cx="160" cy="120" r="10" fill="#101B3D" />
          <circle cx="60" cy="60" r="6" fill="#1B2A52" />
          <circle cx="260" cy="70" r="6" fill="#1B2A52" />
          <circle cx="220" cy="200" r="6" fill="#1B2A52" />
          <circle cx="80" cy="190" r="6" fill="#1B2A52" />
          <circle cx="160" cy="120" r="20" fill="none" stroke="#C8443C" stroke-width="1.5" opacity="0.5" />
        </g>
      </svg>
    </div>
  </section>

  <!-- Coverage overview -->
  <section class="max-w-6xl mx-auto px-6 py-16 border-t border-slate-200">
    <h2 class="font-display text-2xl font-semibold text-navy">How coverage works</h2>
    <div class="mt-10 grid sm:grid-cols-3 gap-10">
      <div>
        <h3 class="font-display text-lg font-semibold">Register once</h3>
        <p class="mt-2 text-slate-600 text-sm leading-relaxed">
          Students and staff register with their institutional email and department,
          so alerts can reach exactly the right people.
        </p>
      </div>
      <div>
        <h3 class="font-display text-lg font-semibold">Get notified by email</h3>
        <p class="mt-2 text-slate-600 text-sm leading-relaxed">
          Alerts are sent directly to your registered email the moment they're issued —
          no app to check, no delay.
        </p>
      </div>
      <div>
        <h3 class="font-display text-lg font-semibold">Security responds</h3>
        <p class="mt-2 text-slate-600 text-sm leading-relaxed">
          Campus security officials are alerted and routed based on proximity,
          so help is directed to whoever is closest.
        </p>
      </div>
    </div>
  </section>
</main>
