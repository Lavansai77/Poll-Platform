# Real-Time Live Poll Platform (Scaffold)

This repository contains the Laravel app-level scaffold (controllers, models, migrations, views, and JS) for the live polling platform. It does **not** include the full Laravel framework bootstrap files (such as `artisan`, `bootstrap/`, or `vendor/`), so you must integrate this scaffold into a standard Laravel installation to run it.

## Prerequisites

- PHP 8.1+
- Composer
- MySQL
- A standard Laravel 10 project initialized via `laravel new` or `composer create-project`

## Run the app (recommended workflow)

1. Create a new Laravel project (or use an existing one):
   ```bash
   composer create-project laravel/laravel poll-platform
   ```
2. Copy the scaffold from this repo into that Laravel project (keeping the same folder structure).
3. Install dependencies and configure environment:
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```
4. Update your `.env` database settings to point at MySQL.
5. Run migrations:
   ```bash
   php artisan migrate
   ```
6. Start the development server:
   ```bash
   php artisan serve
   ```

## Push the code to a GitHub repo

1. Create a repository on GitHub (empty).
2. Add the GitHub remote locally:
   ```bash
   git remote add origin git@github.com:<ORG>/<REPO>.git
   ```
3. Push the current branch:
   ```bash
   git push -u origin <branch-name>
   ```

## Deploy on GitHub Pages (limitations)

GitHub Pages only hosts **static** sites (HTML/CSS/JS). A Laravel app requires a PHP runtime and a database, so it **cannot** run on GitHub Pages directly.

If you still want to use GitHub Pages for a demo:

1. Use it **only** for static documentation or UI mockups (no live polling).
2. Export static HTML (e.g., for the README or a marketing page) and place it in a `/docs` folder or a separate branch (e.g., `gh-pages`).
3. In the GitHub repo settings → **Pages**, select the `/docs` folder or the `gh-pages` branch as the source.

For a real deployment of the Laravel app, use a PHP-capable host (Laravel Forge, Render, AWS, etc.) with MySQL.
