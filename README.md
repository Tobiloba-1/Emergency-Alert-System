# Campus Emergency Alert System — Phase 1: Public/Access Pages (MVC structure)

## Folder structure
```
eas-web/
├── public/                  ← Point your browser/WAMP host here. Thin entry files only.
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── forgot-password.php
│   ├── reset-password.php
│   └── logout.php
├── backend/                 ← All logic and data access. Nothing here is browsable directly.
│   ├── bootstrap.php        (loads config, autoloader, starts session)
│   ├── config/config.php
│   ├── core/                (Database, Session, Csrf, RateLimiter, View, helpers)
│   ├── models/               (User, PasswordReset — pure data access)
│   └── controllers/          (AuthController, RegisterController, PasswordController)
├── frontend/
│   └── views/                ← Pure templates. Receive data from controllers, no logic of their own.
│       ├── partials/head.php / foot.php
│       ├── home.php
│       ├── login.php
│       ├── register.php
│       ├── forgot-password.php
│       └── reset-password.php
├── sql/schema.sql
└── README.md
```

## How a request flows
1. Browser hits a file in **`public/`** (e.g. `public/login.php`).
2. That file loads `backend/bootstrap.php`, then calls a **controller** method
   (e.g. `AuthController::login()`), which does all validation/DB work and returns
   a plain data array (`errors`, `old`, etc.) — no HTML involved.
3. `View::render('login', $data)` includes the matching template from
   **`frontend/views/`**, with `$data` available as local variables.

This keeps logic (`backend/`) and presentation (`frontend/`) fully separate, while still
being simple PHP — no framework, no Composer required.

## Setup on WAMP
1. Copy the whole `eas-web` folder into your WAMP `www` directory.
2. Import `sql/schema.sql` via phpMyAdmin (creates `eas_db` and its tables).
3. Check `backend/config/config.php` — confirm `DB_USER` / `DB_PASS` match your MySQL setup.
4. Visit `http://localhost/eas-web/public/` in your browser.
   - (Optional, cleaner URLs: set up a WAMP virtual host pointing its document root
     directly at the `public/` folder — then everything runs from `http://eas-web.local/`.)
5. Register an account, then log in.

## Security measures in place
- Passwords hashed with `password_hash()` (bcrypt)
- All DB access goes through PDO prepared statements (in `models/`) — no SQL injection surface
- CSRF token on every form (`Csrf` class)
- Rate-limited login attempts (`RateLimiter`)
- Hardened session cookies (`HttpOnly`, `SameSite=Lax`; enable `secure` once on HTTPS)
- Generic messages on login/forgot-password — never reveals which emails are registered
- Password reset tokens are single-use and expire after 30 minutes

## Known placeholders (revisit in later phases)
- **Email** uses PHP's built-in `mail()` for now — won't actually deliver from a local WAMP
  box. Swap in PHPMailer + real SMTP when we build the alert dispatcher in the Admin phase.
- **Roles**: self-registration only offers `student` / `staff`. `admin` accounts should be
  created directly in the database, not self-registered.
- No HTTPS locally — the `secure` cookie flag is commented out in `backend/core/Session.php`.

## Next stage
Once you're happy with this, say go and we'll move to the **Admin Pages**
(dashboard, alert creation/broadcast, alert history) — same MVC structure.
