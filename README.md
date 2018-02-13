# A powerfull, an easy to use eloquent activity monitor

Activity-monitor is a powerful, easy to use laravel eloquent activity monitor. We sometimes want to keep track of what's going on in a database table, who makes what changes, at what time etc. This package will help easy this for you.

# Installation
You can install this package via composer using this command:

```
composer require zeshan77/activity-monitor
```

Configure the service provider as given below:
```
// config/app.php
'providers' => [
    ...
    \Zeshan77\ActivityMonitorServiceProvider::class,
];
```

Publish the migration with:
```
php artisan vendor:publish --provider="\Zeshan77\ActivityMonitorServiceProvider --tag="migrations"
```

After the migration has been published you can create the `activities` table by running the migrations:

`php artisan migrate`


# Usage

