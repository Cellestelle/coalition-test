
# Tasker — Simple Laravel Task Manager

A minimal Laravel 11 app that demonstrates clean, idiomatic “Laravel-way” code:

- CRUD for **Tasks** (`name`, `priority`, timestamps)  
- **Drag-and-drop** reorder; priorities re-numbered **1…N**  
- **Projects** (bonus): filter tasks per project; dense ordering per project  
- MySQL or SQLite storage  
- Small, readable controllers + form requests + Blade views

## Tech Stack

- PHP 8.2+ · Laravel 11  
- DB: SQLite (default) or MySQL  
- Frontend: Blade + [SortableJS](https://sortablejs.github.io/Sortable/)  
- Styling: Pico.css (CDN)

---

## 1) Quick Start (Local)

### A) Clone & install
```bash
git clone <your-repo-url> tasker
cd tasker
composer install
cp .env.example .env
php artisan key:generate
```

### B) Choose your database

**Option 1 — SQLite (fastest)**
```bash
# create the file if it doesn't exist
mkdir -p database && copy NUL database\database.sqlite   # Windows
# or: touch database/database.sqlite                     # macOS/Linux
```
Edit `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
DB_FOREIGN_KEYS=true
```
> Windows note: ensure `pdo_sqlite` and `sqlite3` are enabled in your CLI `php.ini`.

**Option 2 — MySQL**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tasker
DB_USERNAME=root
DB_PASSWORD=yourpassword
```
Create the DB in MySQL before migrating.

### C) Migrate (and optional seed)
```bash
php artisan migrate --seed
```

### D) Run
```bash
php artisan serve
```
Open: http://127.0.0.1:8000

---

## 2) How to Use

- **Tasks page (/)**
  - Add tasks (optionally assign a project)
  - **Drag rows** to reorder — priorities update automatically (1 = top)
  - Delete a task — priorities densify (no gaps)
  - Filter by project via the dropdown

- **Projects page (/projects)**
  - Create, rename, delete projects (inline)
  - Deleting a project will null the `project_id` on its tasks (configurable)

---

## 3) Deployment

### Option A — Laravel Forge / VPS
1. **Server requirements**: PHP 8.2+, Nginx, MySQL (or SQLite), Composer
2. **Clone repo** on server and set the web root to `public/`
3. Environment:
   - `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY` set
   - DB vars configured
4. One-time:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate   # only if APP_KEY not set
   php artisan migrate --force
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
5. Ensure `storage/` and `bootstrap/cache/` are writable

### Option B — Shared Hosting (CPanel)
- Upload project to a folder, point **Document Root** to `/public`
- Move `.env` outside web root if possible
- Run `composer install` via terminal or local → upload `vendor/`
- Set PHP version ≥ 8.2; enable pdo_mysql or pdo_sqlite

### Option C — Docker (sketch)
Use your preferred PHP-FPM + Nginx + MySQL images; mount project; run `composer install`, then `php artisan migrate`.

---
