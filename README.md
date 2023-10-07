Nama: Faraaz Ahmad Permadi
Email: faraazap@gmail.com
HP: 0812 2074 9123

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://github.com/fahmad480/SimpleSalesAndPurchasing/blob/main/public/src/images/logo/logo.png?raw=true" width="400" alt="SimpleSalesAndPurchasing Logo"></a></p>

## About SimpleSalesAndPurchasing

SimpleSalesAndPurchasing is a simple CRUD application using laravel 10, datatables, mysql and datatables with the theme of inventory goods with additional features purchasing to fill inventory stock, and sales to sell inventory stock.

This application is very simple so some feature implementations are made less complex such as

-   RBAC (Role Based Access Control): using simple middleware
-   Authentication: I'm used to making use of JWT but because I'm chasing time and taking a simple concept, so I use Laravel's built-in sanctum as authentication.

## What's inside?

-   [Laravel 10](https://laravel.com/docs/10.x)
-   [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum)
-   [Yajra Datatables](https://yajrabox.com/docs/laravel-datatables/master)
-   [Sweetalert2](https://sweetalert2.github.io/)
-   [Select2](https://select2.org/)
-   Tailwind CSS

## Features

-   Multiple role (superadmin, sales, purchases, manager)
-   RBAC (Role Based Access Control) (simple middleware)
-   Authentication (using laravel sanctum)
-   Datatables (server side rendering, search, pagination, sorting, export to excel, pdf, csv, print)
-   CRUD (Create, Read, Update, Delete)
-   Sweetalert2 (for alert)

## Installation

-   Clone this repository
-   Run `composer install`
-   Run `cp .env.example .env`
-   Run `php artisan key:generate`
-   Update .env into correct database configuration
-   Run `php artisan migrate:fresh --seed`
-   Run `php artisan serve`

## User Login

### Superadmin

```
email: superadmin@example.com
password: password
```

### Sales

```
email: sales@example.com
password: password
```

### Purchases

```
email: purchases@example.com
password: password
```

### Manager

```
email: manager@example.com
password: password
```
