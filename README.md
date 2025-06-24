# Laravel Skill Test

A Laravel-based project implementing RESTful routes for a Post model, with support for drafts, scheduled publishing, and user-authenticated operations.

## 📅 Timeline

- Test started: June 23, 2025  
- Test completed: June 24, 2025  
- Time spent: ~1–2 days (including implementation & testing)

-----------------

## 🚀 How to Run the Project

### 1. Clone the Repository
git clone https://github.com/fahrezypurbaa/laravel-skill-test.git
cd laravel-skill-test

2. Install Dependencies
composer install
npm install && npm run build

3. Set Up Environment
cp .env.example .env
php artisan key:generate

4. Use SQLite (recommended)
Edit .env:
DB_CONNECTION=sqlite
DB_DATABASE=./database/database.sqlite

Buat file database jika belum ada:
touch database/database.sqlite

5. Run Migrations and Seeders
php artisan migrate --seed

6. Start Local Development Server
php artisan serve

--------------------------------

✅ How to Run Tests
Run all feature & unit tests:
php artisan test

Jika berhasil, outputnya:
Tests: 34 passed (76 assertions)

--------------------------------

📌 Features Implemented
✅ RESTful API routes for Post (index, show, store, update, destroy)

✅ Posts can be:
Draft (is_draft)
Scheduled (published_at)

✅ Authenticated users only can create/update/delete posts

✅ Guests can only see published posts

✅ Authorization checks (only author can edit/delete)

✅ Validation on store/update

✅ Full HTTP feature tests:
Success & failure scenarios
Auth & authorization handling

✅ Cookie/session-based auth (no token needed)

------------------------------------

🛠 Tech Stack
Laravel 12.x
PHP 8.3
SQLite
Vite + Inertia (UI optional)
