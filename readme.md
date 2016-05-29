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

