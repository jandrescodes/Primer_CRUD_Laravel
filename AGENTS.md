# AGENTS.md

## Project overview

Laravel 10 learning/practice project: single-entity CRUD (`Personas`) with soft deletes, papelería (trash), Form Request validation, DataTables listing with export buttons, and PHPUnit feature tests. Blade + Bootstrap 5.3 + Font Awesome 6 frontend, no SPA/API layer. UI language: **Spanish**.

## Commands

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
cp .env.testing.example .env.testing  # MariaDB laravel_test, same credentials
mysql -h127.0.0.1 -uroot -proot -e "CREATE DATABASE IF NOT EXISTS laravel_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

php artisan serve            # dev server (runs as current user)
npm run dev                  # Vite watcher (separate terminal)
npm run build                # production assets

php artisan test             # runs against MariaDB laravel_test (phpunit.xml), never touches dev laravel DB
php artisan test --filter=test_method_name
php artisan test tests/Feature/PersonasCrudTest.php

php artisan migrate
php artisan db:seed
```

```bash
# Only when serving via Apache/XAMPP (500 error on storage/logs):
sudo chown -R $USER:daemon storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## Architecture (short)

- One resource `Personas` (`app/Models/Personas.php`): `nombre`, `paterno`, `materno`, `fecha_nacimiento` + soft deletes (`deleted_at`).
- Routes hand-declared in `routes/web.php` under `people` prefix with non-standard verbs/paths (`POST /people/store`, `PUT /people/update/{id}`, trash flow: `GET /people/trash`, `PUT /people/restore/{id}`, `DELETE /people/force-delete/{id}`). Route names follow `personas.*`. Model/controller use `Personas`, routes/views use `people` — the mismatch is intentional.
- Controller (`PersonasController`) thin, Eloquent direct (no service/repository layer). Soft delete flow: `destroy()` → `onlyTrashed()` list → `withTrashed()->findOrFail()` for restore, `onlyTrashed()->findOrFail()` for forceDelete (an active row returns 404).
- Validation: `StorePersonaRequest`/`UpdatePersonaRequest` (required, max 100, `fecha_nacimiento` as `date_format:Y-m-d` before today). `POST /people/store` carries `throttle:60,1`.
- Views: `home/index`, `people/{index,create,edit,trash}`, shared `layout/plantilla` + `templates/{header,footer}`.
- Tests: `tests/Feature/PersonasCrudTest.php` (feature tests with `RefreshDatabase`; uses `PersonasFactory` for realistic data).
- **Testing DB**: `phpunit.xml` enforces `DB_DATABASE=laravel_test` (MariaDB, root/root) and a test-only `APP_KEY` (never used outside testing). `RefreshDatabase` drops & recreates tables on every test — NEVER use `laravel_test` with real data. `.env.testing` (gitignored) exists for `--env=testing` artisan commands; `.env.testing.example` is the committed template.
- **Parallel test safety**: if paratest is introduced, suffix `laravel_test` with the process ID (`laravel_test_{PID}`) or worker index to avoid DROP/CREATE race conditions on the same database.

## Frontend conventions

- **Bootstrap 5.3.8 pinned** (CDN + SRI in header/footer). Do NOT "fix" to 5.2.x: `data-bs-theme` (dark mode) only exists from 5.3.0. If Dependabot reopens the bump, reject/keep 5.3.x — a downgrade breaks the theme toggle on every component.
- **Font Awesome 6.7.2 pinned** (CDN + SRI in `header`). 6.x is LTS; keep 6.7.2, do NOT jump to 7.x (another major). 6.4.0's `fa-solid-900.woff2` triggers cosmetic Chrome warnings (`glyf: Glyph bbox was incorrect`); 6.7.2 is clean. All decorative `<i class="fa-solid …">` icons carry `aria-hidden="true"`; icon-only buttons must have `aria-label`/`title` (never rely on the glyph for the accessible name).
- **Dark mode** is Bootstrap-native (`data-bs-theme` + `tema.js` vanilla toggle). Custom overrides live in `public/css/main.css` scoped to `html[data-bs-theme="dark"]`, token-based. Surface hierarchy: page `--bs-body-bg` (`#212529`); elevated surfaces (navbar, cards, footer) `--bs-secondary-bg` (`#343a40`); headers/card-header/thead `--bs-tertiary-bg` (`#2b3035`). `.text-*`/`.btn-outline-*` are NOT remapped by Bootstrap in dark — their AA fallbacks are the `*-text-emphasis` overrides.
- **`.bg-dark` carries `!important`** (utility), so dark overrides on `.navbar`/`footer` need `!important` too — a plain higher-specificity rule will silently lose.
- **Theme never flashes**: the anti-FOUC script at the top of `header` reads localStorage (try/catch-guarded) and sets `data-bs-theme` + `window.__bsTema` before paint; a parse-time inline script right after `#btn-tema` syncs icon/aria (moon⇄sun) so the header never shows the wrong state after a reload. `tema.js` stays the single vanilla toggle at the end of `body`.
- **Cache-busting required**: local assets load as `{{ asset('css/main.css') }}?v={{ filemtime(public_path('css/main.css')) }}` (same for `js/main.js`, `js/tema.js`). The server sends NO cache-control headers, so unversioned URLs make browsers serve stale CSS via heuristic cache — previously produced a "bad dark mode" (components dark, background light) that only a hard refresh fixed. Never drop the `?v=` suffix.
- jQuery loads only on `people/index` and `people/trash` (DataTables/SweetAlert2); `tema.js` is vanilla. Never reintroduce jQuery globally.
- Touch targets ≥44px via `::before` expansion (`.table .btn-group .btn`, `.card-header .btn`, `.dt-buttons .btn`, `.navbar .btn`) — never `min-height` on the visible box.
- Mobile: `.dataTables_wrapper` gets `overflow-x: clip` under 576px (popups de Columnas/Reportes desbordan 2px).
- Any a11y/contrast fix must include BOTH theme counterparts and be re-audited with axe in light AND dark.
- Code comments explain domain behavior, not process artifacts (specs/tickets/vault notes).

## Security decisions (intentional)

- **No authentication**: all `people/*` routes are public on purpose (practice project, local use only). Do NOT expose it on a network or deploy it as-is: anyone could `forceDelete` records. Adding auth means `auth` middleware + a policy for `forceDelete`.
- **Laravel 10 pinned on purpose** (first Laravel project). `composer update` blocks every 10.x because the remaining `laravel/framework` advisories (TokenGuard bypass, `email` rule CRLF, signed-URL path confusion, debug-page XSS) are only fixed in 12/13; none applies here (no API guards, no `email` rule, no signed URLs). Use `composer update --no-security-blocking` to stay on the latest 10.x. Reject Dependabot major bumps unless the migration is planned.
- `.env.example` ships `APP_DEBUG=false`; turn it on only in your local `.env`.
- **Exports neutralize spreadsheet formulas**: the CSV and copy buttons in `public/js/main.js` use `exportOptions.customizeData` (runs after Buttons' default HTML/entity formatting) to prefix `'` to cells starting with `= + - @`, tab or CR. Keep any new export button that targets a spreadsheet on the same hook.
- `forceDelete` only acts on trashed rows (`onlyTrashed()`); `fecha_nacimiento` is validated as `date_format:Y-m-d`; `POST /people/store` is `throttle:60,1`.
- Not done on purpose: `TrustHosts` (`APP_URL=http://localhost` would block `127.0.0.1`/LAN access in dev; enable it when deploying) and server-side pagination (DataTables paginates the full list client-side).
- Every CDN `<script>`/`<link>` carries `integrity` + `crossorigin`, and `sweetalert2` is pinned to an exact version. When bumping one, recompute the SRI hash from the downloaded file.

## Verify before finishing a task

```bash
php artisan view:cache
php artisan test
node --check public/js/main.js && node --check public/js/tema.js
```

Frontend changes: re-check with axe that no page regresses in EITHER theme, and confirm the versioned asset URLs (`?v=` are present in the served HTML so viewers always get the current CSS/JS.

### Frontend lessons (Bootstrap / DataTables)

- A `<button>` inside an `.input-group` (flex `align-items:stretch`) never shrinks like a sibling `<div>`. Wrap it in a `.input-group-text`-style `<div>` and reset the button (`all:unset; display:flex; align-items:center; justify-content:center; width:100%; height:100%`). For the 44px touch target, do not put `min-height`/`min-width` on the visible box: use an absolute `::before` with `width/height: max(100%, 44px)`.
- Do not patch a shared base selector (`.form-control`, `.btn`) for a local symptom. Compare `getComputedStyle` against a sibling that looks right and scope the fix to the broken element's selector.
- Bootstrap tooltips are lost when DataTables paginates/sorts/searches (rows are replaced). Initialize in a delegated `$(document).on('draw.dt', …)` handler **without** a prior `tooltip('dispose')`, guarding with `!$(this).data('bs.tooltip')`; bump the cache-busting version after core JS changes.
- A hidden HTML template that shares a class with the cloned items breaks any global selector on that class (counts, guards): scope selectors to the real container (`'#container .item'`).
- When assets are versioned with a query string (`?v=filemtime`), sweep every first-party `<script>`/`<link>`, including hand-written ones outside a loop; "the file exists but the browser can't see the helper" is a cached unversioned tag.

### Test lessons

- A fixture/trait that does `DROP`/`CREATE` on a fixed-name resource (DB, file) must suffix the name with a per-process id (`getmypid()`) when tests run in parallel (paratest).
- A test that resets a shared singleton/global (DB connection, config) must restore the test double in `tearDown`, not leave it `null`: with random order, later tests would try to reach the real resource. "Passes locally" proves nothing when local has the real resource; to reproduce CI, run without `.env`/`.env.testing` and with `--order-by=default`.
- Never compute a test's cutoff date with `date()`/`strtotime()` to compare against the DB's `NOW()`/`CURDATE()`: the process clock/timezone and the DB server's can differ. Use an SQL expression (`NOW() - INTERVAL 1 HOUR`).

### Laravel lessons

- `Schema::getColumns()` returns `default` as the string `'NULL'` (not `null`) on MariaDB for a column with no default; a `->toBeNull()` in a schema test fails even when the schema is right. Use `->toBeIn([null, 'NULL'])` or, better, a behavior assert (insert without the column and read it back). Note that `ADD col ... DEFAULT 0` fills existing rows with `0`, silent corruption where `NULL` means "no value".
