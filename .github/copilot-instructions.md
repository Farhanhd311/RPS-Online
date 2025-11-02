## Purpose

Short, focused guidance for AI coding agents working in this repository (a Laravel 12 app scaffold).

## Big picture
- This is a Laravel (PHP >= 8.2) MVC application. Key entry points:
  - Routes: `routes/web.php` — defines web routes and middleware (notably `auth`). Example: `/fakultas/{code}/program-studi/{slug}`.
  - Controllers: `app/Http/Controllers/` — handle requests, check `AuthController` and `FacultyController` for auth and faculty flows.
  - Models: `app/Models/` — Eloquent models. Note `User` uses `protected $primaryKey = 'user_id'` and `password` uses the `hashed` cast. `MataKuliah` stores academic course data and uses `kode_matakuliah` as a domain field.
  - Views: `resources/views/` — structured by role: `dosen/`, `mahasiswa/`, `reviewer/`, plus `layouts/` and `navigation/`.
  - Front-end: Vite + `laravel-vite-plugin`. Entry: `resources/js/app.js` and `resources/css/app.css`. `resources/js/bootstrap.js` configures `axios`.

Why this layout matters: code follows a conventional Laravel scaffold but with some project-specific primary key choices and route patterns for faculty/program pages. Routes sometimes return views directly (see `routes/web.php`) — changes to view locations or route signatures must be coordinated.

## Developer workflows (commands you can run)
Use PowerShell on Windows (workspace uses PowerShell by default). Typical setup:

```powershell
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
```

Useful composer scripts (from `composer.json`):
- `composer run dev` — runs a concurrently-managed dev set: `php artisan serve`, `php artisan queue:listen`, `php artisan pail`, and `npm run dev`.
- `composer test` — clears config cache and runs `php artisan test` (phpunit is configured to use sqlite :memory: per `phpunit.xml`).

Quick checks:
- To run the test suite: `composer test` or `php artisan test`.
- To build front-end assets: `npm run build` (uses `vite build`).

## Project-specific conventions & patterns
- Models sometimes override `protected $primaryKey` (e.g., `app/Models/User.php` uses `user_id`). When generating migrations or queries assume the primary key may not be `id`.
- `MataKuliah` model includes domain column `kode_matakuliah` and comments suggest the primary key may be domain-driven — check `database/migrations/` before changing keys.
- Routes: many faculty-related pages use `{code}` path segments; controllers expect `code` (faculty code) and sometimes `slug`. When adding routes keep the existing naming convention (see `routes/web.php` route names like `fakultas.programs` and `fakultas.program.detail`).
- Views for role-specific pages are placed in role-named subfolders under `resources/views` (e.g., `dosen/dosen_input_rps.blade.php`). When adding UI copy the folder pattern.

## Integration points & external dependencies
- Backend: `laravel/framework` core; `laravel/pail` used in dev script to tail logs (so dev script expects it).
- Frontend: `vite`, `laravel-vite-plugin`, `tailwindcss`, `axios` (bootstrap sets axios defaults in `resources/js/bootstrap.js`).
- Queue: dev script runs `php artisan queue:listen` — code may use queued jobs; check `app/Jobs` (if present) when modifying async behavior.
- Tests: `phpunit.xml` configures in-memory sqlite and disables external services (MAIL array, QUEUE sync), so unit/feature tests should run isolated.

## Files to inspect first when changing behavior
- `routes/web.php` — route shapes and middleware.
- `app/Http/Controllers/AuthController.php` and `app/Http/Controllers/FacultyController.php` — auth flow and faculty pages.
- `app/Models/User.php`, `app/Models/MataKuliah.php` — primary key and cast conventions.
- `resources/views/*` — view layout, role-specific blades.
- `database/migrations/` — authoritative schema; consult before altering model keys.
- `composer.json`, `package.json`, `vite.config.js` — how dev and build are wired.

## Small examples (copyable patterns)
- Return a role view for a route (used in `routes/web.php`):
  Route::get('/dosen/{code}/input-rps', function (string $code) {
      return view('dosen.dosen_input_rps', ['code' => $code]);
  })->name('dosen.input_rps');

- Model primary key override (see `app/Models/User.php`):
  protected $primaryKey = 'user_id';

## What NOT to assume
- Do not assume `id` is the primary key for every table — check model and migrations.
- Do not assume there is a central API or microservice; most functionality is server-rendered Laravel blades with Vite-managed assets.

## When you modify code: quick checklist
1. Update migrations and models consistently (check `database/migrations/`).
2. Run `php artisan migrate` (or rollback/migrate:fresh) locally and run `composer test`.
3. For front-end changes run `npm run dev` and verify Vite refreshes.

## Last notes
- Tests run against sqlite in-memory by default (`phpunit.xml`). If a test needs a real DB, change test config explicitly.
- If you need more context for any controller or view, point me to the file path and I will extract concrete examples to add to this doc.

---
Please review and tell me any missing areas (specific controllers, external integrations, or workflows you want included). I will iterate.
