###Prepare Env###
1. Install PHP5.x Composer
2. Create new .env file base .env.local for your local development env. you need modify some settings speical database settings. e.g. DB_HOST DB_DATABASE DB_USERNAME DB_PASSWORD
3. For you fist run our project. you need run `php artisan migrate` to init database. and add your user account into /database/seeds/DatabaseSeeder.php
e.g.
```php
DB::table('users')->insert([
    'name' => 'steve.yin',
    'email' => 'steve.yin@mediaimpactproject.org',
    'password' => bcrypt('admin123!@#'),
]);

$user = App\User::where('email', '=', 'steve.yin@mediaimpactproject.org')->first();
$role = App\Role::where('name', '=', 'SuperUser')->first();
$user->roles()->attach($role);
$user->save();
```
then run `php artisan db:seed` to add you account and role
4. Create .env file base .env.local and change to you need settings. BTW you can use `MAIL_DRIVER=log` for debug emails. do not need real email smtp service. you can check the log file in path `/storage/logs/laravel.log` to see what you send content in email.

###Command Line###
+ `php artisan optimize`
+ `php artisan config:cache`
+ `php artisan route:cache`
+ `php artisan view:clear`
These commands is been used to optimize the framework for better performance. The compiled file are stored to `bootstrap/cache/` folder. Need to manual run these command sometimes when found something wrong.

###Cache###
You can use you local cache server like Memcached or Redis as you like. see more [here](https://laravel.com/docs/5.1/cache#configuration)
these are the cache setting exmaple in the `.env` file
```
CACHE_DRIVER=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```
some useful Redis cache command
+ Connect to Redis `redis-cli` then you will see `127.0.0.1:6379>`
+ Test connection `ping` then you will see `PONG` if connection success
+ List all keys `keys *`
+ Another way to list all keys `scan 0`
+ Show value `get key` replace the key to what you need checked cache

###Menu###
Middleware `app/Http/Middleware/ParseCurrentControllerAndAction` is for get current Controller and Action will be used in menu view
BaseController `app/Controllers/AuthenticatedBaseController` register 2 middleware: routeInfo and auth. Any need authenticate Controller can inherit from this base controller
