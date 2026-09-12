# Hotel Management

A Laravel-based hotel management system for handling hotels, rooms, customers, staff, bookings, payments, invoices, and public room reservations.

## Features

- Public booking page with room availability search.
- Authentication with separate admin and user/reception views.
- Hotel, room, staff, and customer management.
- Booking workflow for creating, assigning rooms, completing, and cancelling reservations.
- Payment tracking with invoice generation.
- Room images, descriptions, categories, capacity, amenities, and hotel-specific room numbers.
- Performance indexes for bookings, rooms, staff, and payments.

## Tech Stack

- PHP 8.1+
- Laravel 10
- MySQL
- Laravel UI authentication
- Bootstrap 5
- Vite
- Flatpickr
- barryvdh/laravel-dompdf for PDF invoices

## Requirements

- PHP 8.1 or newer
- Composer
- Node.js and npm
- MySQL or MariaDB
- XAMPP, Laravel Valet, Laravel Sail, or another local PHP server

## Installation

1. Clone the project and enter the project directory.

```bash
git clone <repository-url>
cd HotelManagement
```

2. Install PHP dependencies.

```bash
composer install
```

3. Install JavaScript dependencies.

```bash
npm install
```

4. Create the environment file.

```bash
cp .env.example .env
```

On Windows PowerShell, use:

```powershell
Copy-Item .env.example .env
```

5. Generate the application key.

```bash
php artisan key:generate
```

6. Configure the database in `.env`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotelmanagment
DB_USERNAME=root
DB_PASSWORD=
```

Create the matching database before running migrations.

7. Run the migrations.

```bash
php artisan migrate
```

8. Link storage for uploaded/public files if needed.

```bash
php artisan storage:link
```

9. Start the Laravel server.

```bash
php artisan serve
```

10. Start the Vite development server in another terminal.

```bash
npm run dev
```

The app will usually be available at `http://127.0.0.1:8000`.

## Database Notes

The default `.env.example` database name is `hotelmanagment`.

The included `DatabaseSeeder` does not currently create demo users or sample records. After migrating, register a user from the application and add hotel, room, customer, booking, and payment data through the UI.

Admin-only routes use the `isAdmin` middleware and expect an admin flag on the authenticated user. If you are starting from a fresh migration, confirm that your `users` table has the expected admin column (`isadmin` or `isAdmin`) and set it to `1` for admin users.

## Main Routes

| Route | Purpose |
| --- | --- |
| `/` | Public booking page |
| `/availability` | Public room availability search |
| `/login` | Login |
| `/register` | Register |
| `/home` | Admin dashboard |
| `/hotel` | Hotel management |
| `/rooms` | Room management |
| `/staff` | Staff management |
| `/customer` | Customer management |
| `/booking` | Admin booking management |
| `/payment` | Admin payment management |
| `/user/home` | User dashboard |
| `/user/rooms` | User room view |
| `/user/booking` | User booking view |
| `/user/customer` | User customer view |
| `/user/payment` | User payment view |

## Useful Commands

```bash
php artisan migrate
php artisan migrate:fresh
php artisan route:list
php artisan cache:clear
php artisan config:clear
npm run dev
npm run build
```

## Project Structure

```text
app/Http/Controllers    Application controllers
app/Models              Eloquent models
database/migrations     Database schema
database/seeders        Database seeders
resources/views         Blade templates
resources/js            Frontend JavaScript
resources/sass          Sass styles
routes/web.php          Web route definitions
public/                 Public assets and entry point
```

## Testing

Run the PHPUnit test suite with:

```bash
php artisan test
```

## License

This project follows the Laravel skeleton license configuration and is marked as MIT in `composer.json`.
