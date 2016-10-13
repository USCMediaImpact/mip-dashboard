### Media Impact Project Dashboard ###
The Media Impact Project (MIP) Dashboard is an open-source dashboard used together with the MIP Data Repository as part of the Media Impact Project's Media Impact Measurement System.  

The MIP Dashboard and the following documentation were created for use on the Google Cloud Platform. Some additional work may be required to run the MIP Dashboard on other platforms. 

### Overview ###

The MIP Dashboard application is a PHP based application that runs on Google App Engine using the popular Laravel Framework for modular, agile feature development. The MIP Dashboard uses a Google Cloud based MYSQL database for application performance and cost reasons. The Dashboard includes automated ETL processes that run queries to extract the necessary data from the data source and enter it into the MYSQL database to be available for the Dashboard to display. Currently, there the dashboard has connectors for pulling data from BigQuery and Google Analytics into MYSQL and displaying on the Dashboard. 



###Prepare Environment###
1. Install PHP5.x, python, gcloud sdk, git, ssh, etc. 
2. Install Composer (https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx )
3. Run command 'composer install'
4. Create new .env file base .env.local for your local development env. you need modify some settings speical database settings. e.g. DB_HOST DB_DATABASE DB_USERNAME DB_PASSWORD
5. For you fist run our project. you need run `php artisan migrate` to init database. and add your user account into /database/seeds/DatabaseSeeder.php
e.g.
```php
DB::table('users')->insert([
    'name' => 'john.admin',
    'email' => 'john.admin@mediaimpactproject.org',
    'password' => bcrypt('admin123!@#'),
]);

$user = App\Models\User::where('email', '=', 'john.admin@mediaimpactproject.org')->first();
$role = App\Models\Role::where('name', '=', 'SuperUser')->first();
$user->roles()->attach($role);
$user->save();
```
then run `php artisan db:seed` to add you account and role
6. Create .env file base .env.local and change to you need settings. BTW you can use `MAIL_DRIVER=log` for debug emails. do not need real email smtp service. you can check the log file in path `/storage/logs/laravel.log` to see what you send content in email.
7. Install library `composer install`
8. We used PHP intl see need run `brew install php55-intl` to install this extensions. need restart php-fpm or just restart you computer.BTW in project folder php.ini for google engine already add this extensions.

###Background Services###
The Background Services deployed to Google App Engine are use corn for python. The sourcecode are in the folder `background`

1. Edit mysqlClient.py for your local mysql passwd setting
2.  Add:  passwd='passwordgoeshere')  to  mysqlClient.py file in the Background folder  
3. Add the mip-analytics.p12 file to the background folder
4. Run ‘gcloud.cmd auth login’ to generate the necessary files for using the google cloud services & api

####Run the background debug to install/test/check for dependencies####
1. open the python interpreter
2. run the command 'Import debug'
3. run the command 'debug.run_last_week()'


The background folder is a separate Google App Engine Application that you will need to deploy separately to App Engine.

Because we used some third party libraries, you will need to install these libraries into folder `lib`
for Mac. There have a issue for Homebrew [see more here](http://stackoverflow.com/questions/24257803/distutilsoptionerror-must-supply-either-home-or-prefix-exec-prefix-not-both)

Create a pip config file `~/.pydistutils.cfg` with these content
```
[install]
prefix=
```
Then run the pip install command `pip install -t lib -r requirements.txt`

See more [here](https://cloud.google.com/appengine/docs/python/tools/using-libraries-python-27)


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

###GCloud Library###
there was 2 kind of google libaray. one is gogole api libaray another is this. for now seem this library is not good enough but we have more options. keep eyes on these.
https://googlecloudplatform.github.io
(PHP)[https://googlecloudplatform.github.io/google-cloud-php/#/]
(Python)[https://googlecloudplatform.github.io/gcloud-python/#/]

###Deploying to Google Cloud ###

This application currently runs on the Google Cloud Platform. If you wish to run this on a different platform, you will need to make adjustments accordingly.

####Google Cloud Platform Setup####
1. Create a project. 
2. Make sure you have enabled billing for this project.
3. Enable all necessary APIs.
4. create and save your .p12/json key for project. NOTE: Do not commit your keys.
5. create an SQL database for the project. set the root password. 

####Before Deploying####
1. Adjust SQL and BigQuery settings  (stage, live, dev, etc.)
2. Adjust yaml settings for deployment (stage, live, dev, etc.). NOTE: remember that version names can only have one dash in them. stick to version names without dashes for ease of use.
3. run the command to deploy the project 'appcfg.py -A project-name update ./'


### State of Project ###
The MIP Dashboard is freely available for use under the Apache 2.0 open source license. ​The Media Impact Project is a division of the Norman Lear Center at the USC Annenberg School for Communication and Journalism. It is funded by a grant from the Bill & Melinda Gates Foundation, with additional funding from the John S. and James L. Knight Foundation and the Open Society Foundations.
The MIP Dashboard is currently not in active development as of December 2016. 