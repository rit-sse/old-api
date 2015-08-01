# SSE API

This project is an API for the Society of Software Engineers. It manages such
things as events, members, memberships, mentors, officers, and so on.

## Requirements

1. `php` 5.5.9+
1. [Composer](http://getcomposer.org)
1. sqlite3

## Getting Started

On a Mac or Linux system? Check out [this](https://youtu.be/iF3erw9RVlg) video for a short screencast to speed things up.

To get started, follow these steps:

1. Change directory into the root of the repository.
1. Execute `composer install` (this assumes you have installed Composer as `composer` in your `PATH`).
1. Init your environment (sessions, cache, etc.) and the database: `./bootstrap/init.php`
1. Execute `php -S localhost:8000 server.php`.

_Note_: If you are having issues running the [api-client][api-client] locally, run `php -S 0.0.0.0:8000 server.php` instead.

This will initialize your development database with seed data and start the
development server locally on port 8000.

## Authentication

The API uses the Google OAuth 2.0 service for authentication. This is a workaround
that allows us to remove the need to interact directly with Shibboleth. In order to
test the authentication service, you need to generate a client id, secret, and insert
a callback URL into `config/services.php` under the `google` key.

To generate the necessary client id and secret, head to the [Google Developer Console](https://console.developers.google.com/project), create a project, select 'APIs & Auth > Credentials', and
finally click 'Create a new Client ID'. Make sure you enter your *full* callback URL,
which is `http[s]://{host}/api/v1/auth/google/callback`.

*Note*: To prevent unauthorized errors, you **must** also enable the Google+ API!

## Endpoints

The endpoints are controlled by Laravel routes. To look at the routing setup,
open up the router definition in `app/Http/routes.php`.

Most `Controllers` within this project are RESTful controllers (as defined by
Laravel, denoted by the `Route::resource` syntax in `routes.php`). The specific
mapping for this type of controller can be found in Laravel's [documentation](http://laravel.com/docs/5.1/controllers#restful-resource-controllers). For the most part, you probably
want a resource controller, unless doing something more specialized such as
authentication or statistics. In that case, you should take a look at using the
`Route::controller` syntax found [here](http://laravel.com/docs/5.1/controllers#implicit-controllers).

The API root of the application is currently set to `/api/v1`. This route is
also controlled by `app/Http/routes.php`, the definition of which is controlled
by the `Route::group` syntax.

## Documentation

This project can have its API documentation automatically generated. To do so,
execute `php artisan api:docs "SSE API" v1` in the root of the repository.
Optionally, you can add a `--file=` parameter to the command to save the output.
Otherwise, the generator will dump its output to `stdout`. The output is valid
[API Blueprint](https://apiblueprint.org/) syntax.

The documentation for annotation required by the generator can be found at
[dingo/api-docs](https://github.com/dingo/api-docs/blob/master/API%20Blueprint%20Documentation.md).

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

### Validation

If you are using `$this->validate` in the context of a `Controller`, there are
a few things of which you should be aware. If the `Request` does not consider
the request to be an AJAX call, it will send a redirect response rather than
an error. To see the validation messages, add the `X-Requested-With` header with
a value of `XMLHttpRequest` (yes, case matters!).

[api-client]: https://github.com/rit-sse/api-client
