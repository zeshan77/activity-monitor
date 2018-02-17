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

Import `RecordsActivity` trait in eloquent model which needs to be monitored.
```
use Zeshan77\ActivityMonitor\RecordsActivity;
```

After import, use `RecordsActivity` trait in the model as shown below in Post model.
```
class Post extends Model
{
    use RecordsActivity;
    
    //
    
}
```

A basic `Post` model will look something similar below:

```
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zeshan77\ActivityMonitor\RecordsActivity;


class Post extends Model
{
    use RecordsActivity;

    //

}

```
After this, any `Create`, `Update` and `Delete` activities on `Post` model will automatically be recorded.

To retrieve all activities using eager load approach on a model, i.e Post:
```
>>> $post = Post::with('activity')->first();
=> App\Post {#625
     id: 1,
     body: "Body is updated.",
     created_at: "2018-02-15 18:38:45",
     updated_at: "2018-02-15 19:18:26",
     activity: Illuminate\Database\Eloquent\Collection {#665
       all: [
         Zeshan77\ActivityMonitor\Activity {#668
           id: 1,
           user_id: 1,
           subject_id: 1,
           type: "created_post",
           subject_type: "App\Post",
           old: null,
           new: "{"body":"Nemo repellendus quasi maiores ipsum vel"}",
           created_at: "2018-02-15 18:38:45",
           updated_at: "2018-02-15 17:38:45",
         },
         Zeshan77\ActivityMonitor\Activity {#669
           id: 2,
           user_id: 1,
           subject_id: 1,
           type: "updated_post",
           subject_type: "App\Post",
           old: "{"body":"Nemo repellendus quasi maiores ipsum vel"}",
           new: "{"body":"Body is updated again."}",
           created_at: "2018-02-15 18:39:12",
           updated_at: "2018-02-15 17:39:12",
         },
       ],
     },
   }
>>> 
```
