# Jocko

Shop management for a car-parts retailer: stock across warehouses, purchases, orders and payments, reports and low-stock alerts. The interface is Persian (Jalali dates, Persian digits); admins sign in with an SMS code sent through [Kavenegar](https://kavenegar.com).

Laravel 13, Livewire 4, Flux, split into [nwidart modules](https://laravelmodules.com) under `Modules/`:

| Module | Covers |
| --- | --- |
| Auth | OTP login |
| Car | cars, car companies, colors |
| Dashboard | overview charts, categories, countries/provinces/cities, shared UI components |
| Inventory | items, brands, units, warehouses and their stock, suppliers, purchases, stock movements, low stock |
| Order | orders, invoices, payments |
| User | users, their cars, settings |

## Setup

Needs PHP 8.3+ (with `curl` and `fileinfo`), Composer and Node 22. The database is SQLite by default.

```bash
composer setup        # install, copy .env, key:generate, migrate, npm install, build
php artisan module:seed Dashboard Car User Inventory Order   # optional demo data, in this order
composer dev          # server, Vite, log tail and Horizon
```

`composer dev` also starts Horizon, which needs the PHP Redis extension. The default queue is `database`, so without Redis run `php artisan serve` and `npm run dev` instead.

## Signing in

Only users of type `admin` can sign in, with their mobile number and a 6-digit code sent by SMS. Set `KAVENEGAR_API_KEY`, `KAVENEGAR_SENDER` and `KAVENEGAR_OTP_TEMPLATE` in `.env` to send real messages.

For local work, set `SMS_ENABLED=false`: nothing is sent and the message, code included, is written to `storage/logs/laravel.log` (`php artisan pail` follows it). This is ignored in production. The seeded users have random mobile numbers; get an admin's with:

```bash
php artisan tinker --execute="echo Modules\User\Models\User::admin()->value('mobile');"
```

## Language

`APP_LOCALE=fa` is the default. English strings live in `resources/lang/en` and `Modules/*/resources/lang/en`; set `APP_LOCALE=en` to use them.

## Tests and checks

```bash
composer test             # Pint, PHPStan (level 8), then the Pest suite
composer lint             # fix code style
npx playwright install chromium   # once
composer test:browser     # real-browser smoke tests of the list pages
```

CI (`.github/workflows/tests.yml`) runs the same checks, then the browser tests.
