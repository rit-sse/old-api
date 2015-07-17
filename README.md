# SSE API

This project is an API for the Society of Software Engineers. It manages such
things as events, members, memberships, mentors, officers, and so on.

## Requirements

1. `php` 5.5.9+
1. [Composer](http://getcomposer.org)
1. sqlite3

## Getting Started

To get started, follow these steps:

1. Change directory into the root of the repository.
1. Execute `composer install`.
1. Move `.env.example` to `.env`.
1. Init app storage (sessions, cache, etc.) and the database: `./bootstrap/init_storage.sh`
1. Execute `php artisan migrate:refresh --seed`.
1. Execute `php -S localhost:8000 server.php`.

This will initialize your development database with seed data and start the
development server locally on port 8000.

## Endpoints

The endpoints are controlled by Laravel routes. To look at the routing setup,
open up the router definition in `app/Http/routes.php`.

All `Controllers` within this project are RESTful controllers (as defined by
Laravel, denoted by the `Route::resource` syntax in `routes.php`). The specific
mapping for this type of controller can be found in Laravel's [documentation](http://laravel.com/docs/5.1/controllers#restful-resource-controllers).

The API root of the application is currently set to `/api/v1`. This route is
also controlled by `app/Http/routes.php`, the definition of which is controlled
by the `Route::group` syntax.

### `/events`

The model for an `Event` is location in `app/Event.php` and the controller is
located in `app/Http/Controllers/EventController.php`.

### `/lingo`

The model for a `Lingo` instance is location in `app/Member.php` and the
controller is located in `app/Http/Controllers/LingoController.php`.

### `/members`

The model for a `Member` is location in `app/Member.php` and the controller is
located in `app/Http/Controllers/MemberController.php`.

### `/memberships`

The model for a `Membership` is location in `app/Membership.php` and the
controller is located in `app/Http/Controllers/MembershipController.php`.

### `/mentors`

The model for a `Mentor` is location in `app/Mentor.php` and the
controller is located in `app/Http/Controllers/MentorController.php`.

### `/officers`

The model for a `Officer` is location in `app/Officer.php` and the controller is
located in `app/Http/Controllers/OfficerController.php`.

### `/terms`

The model for a `Term` is location in `app/Term.php` and the controller is
located in `app/Http/Controllers/TermController.php`.

## Troubleshooting

### Composer Fails

Generally, `composer` fails when the proper PHP extensions are not enabled.
Ensure you have the following PHP extensions enabled in your `php.ini` file:

* OpenSSL
* PDO
* Mbstring
* Tokenizer

### Seeding

If you have issues with `Class does not exist` errors while attempting to seed
your development database, try running `composer dump-autoload` and re-seeding.
