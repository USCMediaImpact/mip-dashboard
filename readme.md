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

$user = App\Models\User::where('email', '=', 'steve.yin@mediaimpactproject.org')->first();
$role = App\Models\Role::where('name', '=', 'SuperUser')->first();
$user->roles()->attach($role);
$user->save();
```
then run `php artisan db:seed` to add you account and role
4. Create .env file base .env.local and change to you need settings. BTW you can use `MAIL_DRIVER=log` for debug emails. do not need real email smtp service. you can check the log file in path `/storage/logs/laravel.log` to see what you send content in email.
5. Install library `composer install`
6. We used PHP intl see need run `brew install php55-intl` to install this extensions. need restart php-fpm or just restart you computer.BTW in project folder php.ini for google engine already add this extensions.

###Background Services###
The Background Services deployed to Google App Engine are use corn for python. The sourcecode are in the folder `background`
You need splited deploy these code to App Engine.
Cause we used some 3rd library need install these library into folder `lib`
for Mac. there have a issue for Homebrew [see more here](http://stackoverflow.com/questions/24257803/distutilsoptionerror-must-supply-either-home-or-prefix-exec-prefix-not-both)
Create a pip config file `~/.pydistutils.cfg` with these content
```
[install]
prefix=
```
Then run the pip install command `pip install -t lib -r requirements.txt`

####How to run history####
3. use the newest sql to create the local mysql database in the folder `init_sql`
3. use gcloud command to backup the newset settings table (plz change the google storage file name gs://mip-dashboard_mysql_dump/20160730_1.gz to what you want name)
`gcloud sql instances export mip-dashboard-prd gs://mip-dashboard_mysql_dump/20160730_1.gz --async --database media_impact --table settings,clients`
4. download file
5. add `use media_impact;` in the setting files
6. use command to import this file. you may need change the file path
`mysql -uroot < 20160730_1`
7. make sure you are on master branch
8. go to background folder and run test first
`python debug.py`
9. if everything is fine run `python history.py > history.log`
10. you can `tail -f history.log` to check the progress
11. after success run history. export your local mysql data
`mysqldump -uroot media_impact SCPR_data_quality_weekly SCPR_data_stories_weekly SCPR_data_users_weekly > export.sql`
12. upload this export.sql file to google storage `mip-dashboard_mysql_dump`
13. import this file use command or operate on web.

###Command Line###
+ `php artisan clear-compiled`
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

###Client Info###
Middleware `app/Http/Middleware/InjectClientInfo` is for inject user client info into request and view. you can use $client in View and use $request->input('client') to get current user client info
For SuperAdmin will auto inject the first client. and allow SuperAdmin select client from view.
For safe used these variable better check before use it in view
```php
@if(isset($client))
```

###Other Tools###
Google Analytics Query Tools [https://ga-dev-tools.appspot.com/query-explorer/](https://ga-dev-tools.appspot.com/query-explorer/)

###COMMIT TEST###
