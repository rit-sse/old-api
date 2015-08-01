#!/usr/bin/env php
<?php

$DIR = dirname(__FILE__);

function create_path($path = '.')
{
    if (!(file_exists($path))) {
        mkdir($path);
    }
}

print 'Setting up basic environment variables.' . PHP_EOL;
copy($DIR . '/../.env.example', $DIR . '/../.env');

print 'Setting up application directories.' . PHP_EOL;
create_path($DIR . '/../storage/app');
create_path($DIR . '/../storage/framework');
create_path($DIR . '/../storage/framework/sessions');
create_path($DIR . '/../storage/logs');

print 'Creating empty development database.' . PHP_EOL;
touch($DIR . '/../storage/development.db');

if (chdir($DIR . '/..')) {
    print 'Generating random application key.' . PHP_EOL;
    exec('php artisan key:generate');

    print 'Running database migrations and seeds.' . PHP_EOL;
    exec('php artisan migrate:refresh --seed');
} else {
    print 'Failed to change working directory, please file an issue.' . PHP_EOL;
}
