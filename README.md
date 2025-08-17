<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


## Mini Task Management

Minimal CRUD for a `Task` with fields: `title` (string), `description` (text, optional), `completed` (boolean).

### Features
- Search across title and description
- Status filters: All, Pending, Completed
- Inline editing on the list (no separate edit page)
- Clean, beginner-friendly Create page
- Delete with confirmation
- Minimal CSS; no build tools required

### Tech
- Laravel 12, PHP 8.2+
- MySQL (Laragon) by default
- Blade templates (no Node/Vite required)

### Quick start (Laragon + MySQL)
1. Make sure PHP 8.2+, Composer, and MySQL (Laragon) are installed and MySQL is running.
2. Create database (or use Laragon UI):
   - `mysql -uroot -e "CREATE DATABASE IF NOT EXISTS laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"`
3. Copy env file and set DB connection:
   - `cp .env.example .env`
   - Update `.env`:
     - `DB_CONNECTION=mysql`
     - `DB_HOST=127.0.0.1`
     - `DB_PORT=3306`
     - `DB_DATABASE=laravel`
     - `DB_USERNAME=root`
     - `DB_PASSWORD=` (empty unless you changed it)
4. Install dependencies: `composer install`
5. App key: `php artisan key:generate`
6. Migrate database: `php artisan migrate`
7. (Optional) Seed demo data (first run):
   - Recommended: `php artisan migrate:fresh --seed`
   - If you later see a duplicate email error while seeding, use `php artisan migrate:fresh --seed` to reset and reseed cleanly.
8. Run the app: `php artisan serve`
9. Open `http://127.0.0.1:8000` → Tasks index

### Routes
- GET `/` → redirects to `tasks.index`
- GET `/tasks` → list tasks
- GET `/tasks/create` → create form
- POST `/tasks` → store
- GET `/tasks/{task}` → defined but redirects to list (inline editing used)
- GET `/tasks/{task}/edit` → defined but redirects to list (inline editing used)
- PUT `/tasks/{task}` → update
- DELETE `/tasks/{task}` → delete

### Code map
- Model: `app/Models/Task.php`
- Controller: `app/Http/Controllers/TaskController.php`
- Routes: `routes/web.php`
- Views: `resources/views/tasks/*.blade.php`
- Migration: `database/migrations/*_create_tasks_table.php`

### Troubleshooting
- Duplicate email error when seeding: run `php artisan migrate:fresh --seed` to reset the DB, or edit `database/seeders/DatabaseSeeder.php` to skip creating the sample user if it already exists.
- Port already in use: stop other PHP servers or run `php artisan serve --port=8001`.
- Database connection issues: verify `.env` DB settings and that MySQL is running.



## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.
