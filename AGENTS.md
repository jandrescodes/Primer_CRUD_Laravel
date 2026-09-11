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
- Controller (`PersonasController`) thin, Eloquent direct (no service/repository layer). Soft delete flow: `destroy()` → `onlyTrashed()` list → `withTrashed()->findOrFail()` for restore/forceDelete.
- Validation: `StorePersonaRequest`/`UpdatePersonaRequest` (required, max 100, `fecha_nacimiento` valid date before today).
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

## Verify before finishing a task

```bash
php artisan view:cache
php artisan test
node --check public/js/main.js && node --check public/js/tema.js
```

Frontend changes: re-check with axe that no page regresses in EITHER theme, and confirm the versioned asset URLs (`?v=` are present in the served HTML so viewers always get the current CSS/JS.