###Prepare local dev env###

1.  change .env file by you local database
2.  restore database
3.  install php version 5.x
4.  install composer
5.  use composer to install dependencies `composer install`

###Prepare local corn job env###
1.  install python 2.7
2.  use pip to install dependencies `pip install -t -r requirements.txt`


###PHP Larvel###
About Larvel See more [here](https://laravel.com/docs/5.1)
.env file for production
>   APP_ENV=production
>   APP_DEBUG=false
>   APP_KEY=62f43402267d11e68992067471b6547d
>   APP_HOST_DOMAIN=http://app.mediaimpactproject.org
>   APP_LOG=syslog
>   
>   DB_CONNECTION=cloudsql
>   DB_SOCKET=/cloudsql/mip-dashboard:us-central1:mip-dashboard-live2
>   DB_HOST=
>   DB_DATABASE=media_impact
>   DB_USERNAME=root
>   DB_PASSWORD=uscmip2016!
>   
>   MAIL_DRIVER=gae
>   
>   FILESYSTEM=gae
>   SESSION_DRIVER=memcached
>   CACHE_DRIVER=memcached
>   QUEUE_DRIVER=gae
>   
>   CACHE_SERVICES_FILE=false
>   CACHE_CONFIG_FILE=false
>   CACHE_ROUTES_FILE=false
>   CACHE_COMPILED_VIEWS=false
>   
>   REDIS_HOST=127.0.0.1
>   REDIS_PASSWORD=
>   REDIS_PORT=6379

APP_KEY is very important as the user password is based on this. 
APP_HOST_DOMAIN is used in email template. Change this in order to set the reset password email and invite email links to have the correct domain.
DB_SOCKET or DB_HOST are database address. For Google App Engine use DB_SOCKET. For local dev, you will likely use DB_HOST.


###Folder Structure###
app > Helpers > FormatterHelper.php
> We can use this helper in View. We need the following line in the view
> @inject('formatter', 'App\Helpers\FormatterHelper')
> for now, most page use ajax to get data. To format dates, we used moment.js. To format numbers, we use new Intl ECMAScript Internationalization API, which Safari does not support. So we add shim script 
> <script src="//cdn.polyfill.io/v2/polyfill.min.js?features=Intl.~locale.en"></script>
> This script will check brower have support Intl and add fallbacks

│   ├── Http
│   │   ├── Controllers
│   │   │   ├── AnalysesController.php
│   │   │   ├── Auth << for Admin user
│   │   │   │   ├── AccountController.php
│   │   │   │   ├── AuthController.php
│   │   │   │   └── PasswordController.php
│   │   │   ├── AuthenticatedBaseController.php
│   │   │   ├── Controller.php
│   │   │   ├── DashboardController.php
│   │   │   ├── DataController.php 
> This controller is our most important controller. One area of improvement would be to split to multiple controllers for each of the data-tabs: data-users data-stories data-newsletter data-quality controller

│   │   │   ├── DataExceptionController.php
│   │   │   ├── MetricsController.php
│   │   │   ├── ReportsController.php
│   │   │   ├── StorageController.php
│   │   │   └── SuperAdmin << for SuperAdmin user
│   │   │       ├── AccountController.php
│   │   │       ├── ClientController.php
│   │   │       ├── MaintainController.php
│   │   │       └── SettingController.php
│   │   ├── Middleware
│   │   │   ├── InjectClientInfo.php
> We use this to add current user client info to $request

│   │   │   ├── ParseCurrentControllerAndAction.php
> This is used in the side menu to get active menu

│   │   ├── Requests
│   │   │   └── Request.php
│   │   └── routes.php
│   ├── Models
> These are Eloquent Model, an ORM solution [see more here](https://laravel.com/docs/5.1/eloquent)

│   │   ├── Analyses.php
│   │   ├── Client.php
│   │   ├── DataException.php
│   │   ├── Role.php
│   │   ├── Setting.php
│   │   └── User.php
│   └── Providers
│       ├── AuthServiceProvider.php 
> Register Admin Role and SuperAdmin Role here

├── background
> These are Python background cron jobs and related code.

│   ├── analyticsClient.py
> This calls GA data helper function

│   ├── app.yaml
│   ├── appengine_config.py
│   ├── bigQueryClient.py
> This calls big query helper function. One area to improve is where the weekly stories data has too many records for the cron job. e.g. SCPR data stories. some week have 50000+ records. might make the cron job crash, so this should be refactored.

│   ├── cloudStorage.py
> access google cloud storage helper functions

│   ├── config.py
│   ├── cron.yaml
│   ├── debug.py
│   ├── history.py
│   ├── job.py
> cron job main functions

│   ├── logging_config.py
│   ├── main.py
> cron job web access entry

│   ├── mySqlClient.py
> mysql database access helper function

│   ├── query.py
> mysql sql config e.g. insert into mysql sql for clients

│   ├── requirements.txt
│   ├── storiesClear.py
│   └── storiesClear.pyc
├── config
│   ├── app.php
│   ├── auth.php
│   ├── broadcasting.php
│   ├── cache.php
│   ├── compile.php
│   ├── database.php
│   ├── filesystems.php
│   ├── mail.php
│   ├── menu.php
│   ├── queue.php
│   ├── services.php
│   ├── session.php
│   ├── view.php
├── database
├── public
│   ├── css
│   ├── fonts
│   ├── images
│   └── scripts
│   │   ├── main.js
> Most of the common javascript is here. The rest is in the View

│   │   └── vendor
> 3rd party javascript libraries are here

├── resources
|   └── views
|       ├── analyses
|       │   └── index.blade.php
|       ├── auth
|       │   ├── account.blade.php
|       │   ├── login.blade.php
|       │   ├── password.blade.php
|       │   └── reset.blade.php
|       ├── chart.blade.php
|       ├── dashboard
|       │   └── users.blade.php
|       ├── dashboard.blade.php
|       ├── data
> We split these views by clients. To add a new client, add a new folder and views. One area of improvement would be to add a default view and check if a special view exists otherwise use standard view. This logic improvement would need be upgraded within DataController.php

|       │   ├── SCPR
|       │   │   ├── content.blade.php
|       │   │   ├── donations.blade.php
|       │   │   ├── newsletter.blade.php
|       │   │   ├── quality.blade.php
|       │   │   ├── stories.blade.php
|       │   │   └── users.blade.php
|       │   ├── TT
|       │   │   ├── content.blade.php
|       │   │   ├── donations.blade.php
|       │   │   ├── newsletter.blade.php
|       │   │   ├── quality.blade.php
|       │   │   ├── stories.blade.php
|       │   │   └── users.blade.php
|       │   └── WW
|       │       ├── content.blade.php
|       │       ├── donations.blade.php
|       │       ├── newsletter.blade.php
|       │       ├── quality.blade.php
|       │       ├── stories.blade.php
|       │       └── users.blade.php
|       ├── dataException
|       │   └── index.blade.php
|       ├── debug.blade.php
|       ├── emails
|       │   ├── password.blade.php
|       │   └── welcome.blade.php
|       ├── errors
|       │   └── 503.blade.php
> we can add more error pages here e.g. not authed error page. 404 not found page etc.

|       ├── layouts
|       │   ├── checkbox.blade.php
|       │   ├── footer.blade.php
|       │   ├── frame.blade.php
|       │   ├── header.blade.php
|       │   ├── main.blade.php
> This is our main template. like index.html. All the rest of the css and js files are here.
> This is where we could add a GA tracking code.

|       │   ├── menu.blade.php
|       │   └── offCanvas.blade.php
> This is where the side menu is.

|       ├── metrics
|       │   ├── content.blade.php
|       │   ├── donations.blade.php
|       │   └── users.blade.php
|       ├── reports
|       │   ├── content.blade.php
|       │   ├── donations.blade.php
|       │   └── users.blade.php
|       ├── superAdmin
|       │   ├── accounts.blade.php
|       │   ├── clients.blade.php
|       │   ├── maintain.blade.php
|       │   └── settings.blade.php
|       └── widgets
|           ├── datafrom.blade.php
|           ├── daterange.blade.php
|           ├── dropdownbutton.blade.php
|           └── resultGroup.blade.php
├── storage
├── tests
└── vendor

###Others###
####for controller####
You can easy inherit AuthenticatedBaseController this base controller already added user check
```php
public function __construct()
{

    $this->middleware('auth');

}
```
or you can add this middleware by yourself
other useful middleware are
```php
    $this->middleware('routeInfo');
    $this->middleware('clientInfo');
```
These middleware are all locationed at path app/Http/Middleware/
1. routeInfo are used by the menu to get current active menu
2. clientInfo are used to get current user client info from $request

####for views####
We have 2 sections
@section('content') put html dom node here
@section('script') put this page javascript code here

speical for section script. we already add main.js in public/scripts/ for some common scripts
+ datepicker init.
+ datatable reload
+ download button event
+ tracking

For datetable reload. we used an event to trigger this common javascript event. For data page(users stories newsletter) we needed add current page datatable instance to global windows variable `ReportDataTable` the property name is the table id. you can see it in this code
```html
<div class="panel">
    <div class="top-bar">
        @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
    </div>
    <table id="dataNewsLetter" class="report tiny hover">
    </table>
```
The main.js already adds the datepicker change event (meaning, the client changed displayed date), this will trigger parent panel custom event `change.daterange` and there is code to handle this event in main.js
```javascript
$(document).on('change.daterange', '.panel', function () {...});
```
this code is to get the table id and try to get datatable instance in gloabl variable `ReportDataTable` and try to reload data

For datepicker to set init date range, the main.js will check the hidden input name = 'defaultDateRange' html element value. you can use template widgets.daterange to render these html or add by yourself. See more: resources/views/widgets/daterange.blade.php
*****Notice*****
For stories, we only allow the client to select one week so we do not use a common daterange.

####for cron job####
+ we get big query hive sql from mysql table settings. 
+ the insert big query result to mysql sql is saved in query.py
+ for performance issue we only saved top 100 stories in mysql others are saved as csv file on google storage. the storage path in config.py that means for stage and production you must changed this value before deploy
+ history.py are quick code for run job.py history method
+ storiesClear.py this is a quick script code for get top 100 stories and save to another table.






