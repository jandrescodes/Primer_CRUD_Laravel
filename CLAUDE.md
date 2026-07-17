# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

Laravel 10 learning/practice project: a single-entity CRUD (`Personas` — people) with soft deletes, a recycle bin ("papelera"), Form Request validation, DataTables listing with export buttons, and PHPUnit feature tests. Blade + Bootstrap 5 frontend, no SPA/API layer.

## Commands

```bash
# Install
composer install
npm install
cp .env.example .env && php artisan key:generate

# Run
php artisan serve
npm run dev          # Vite asset watcher (separate terminal)
npm run build         # production assets

# Tests (SQLite in-memory, configured in phpunit.xml — never touches the real DB)
php artisan test
php artisan test --filter=test_method_name
php artisan test tests/Feature/PersonasCrudTest.php

# Migrations
php artisan migrate
php artisan db:seed
```

### XAMPP/LAMPP permission issue

If serving via Apache/XAMPP produces a 500 error from `storage/logs/laravel.log` permission denied, the Apache user (`daemon`) needs write access:

```bash
sudo chown -R $USER:daemon storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

Not needed when using `php artisan serve` (runs as the current terminal user).

## Architecture

Everything revolves around one resource, `Personas` (`app/Models/Personas.php`), fields: `nombre`, `paterno`, `materno`, `fecha_nacimiento`, plus `deleted_at` (SoftDeletes).

- **Routes** (`routes/web.php`): all under `people` prefix, hand-declared (not `Route::resource`) with non-standard verbs/paths — e.g. store is `POST /people/store` not `POST /people`, update is `PUT /people/update/{id}`, destroy is soft-delete via `DELETE /people/destroy/{id}`. Trash flow adds `GET /people/trash`, `PUT /people/restore/{id}`, `DELETE /people/force-delete/{id}`. Route names follow `personas.*` (Spanish model name, note the controller/model use `Personas` while routes/views use `people`).
- **Controller** (`app/Http/Controllers/PersonasController.php`): thin, no service/repository layer — Eloquent calls directly in controller methods.
- **Validation**: `App\Http\Requests\StorePersonaRequest` / `UpdatePersonaRequest` — required fields, max 100 chars for text fields, `fecha_nacimiento` must be a valid date before today.
- **Soft deletes**: `destroy()` moves to trash; `trash()` lists `onlyTrashed()`; `restore()` and `forceDelete()` operate via `withTrashed()->findOrFail()`.
- **Views** (`resources/views`): `home/index.blade.php` (dashboard with live counts), `people/{index,create,edit,trash}.blade.php`, shared layout in `templates/header.blade.php`. Listing uses DataTables (with Buttons: CSV/Excel/PDF/colvis) and SweetAlert2 for delete/restore confirmations, wired in `public/js/main.js`.
- **Tests** (`tests/Feature/PersonasCrudTest.php`): feature tests covering index/create/store/edit/update/soft-delete/trash/restore/force-delete, run against in-memory SQLite regardless of the app's configured DB driver.
