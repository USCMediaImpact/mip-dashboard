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