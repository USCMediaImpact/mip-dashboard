***Prepare local dev env***
1. change .env file by you local database
2. restore database
3. install php version 5.x
4. install composer
5. use composer to install dependencies `composer install`

***Prepare local corn job env***
1. install python 2.7
2. use pip to install dependencies `pip install -t -r requirements.txt`


***PHP Larvel***
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

APP_KEY are very important. user password are base this
APP_HOST_DOMAIN are used in email template. change this will make the reset password email and invite email links changed
DB_SOCKET or DB_HOST are database address. for google engine are used DB_SOCKET for local dev most time are used DB_HOST


***Folder Structure***
├── app
│   ├── Helpers
│   │   └── FormatterHelper.php
> We can used this helper in View need this in view
> @inject('formatter', 'App\Helpers\FormatterHelper')
> for now most page are used ajax to get data. To format date we used moment.js. To format number we used new Intl ECMAScript Internationalization API, safari not support. so we add shim script 
> <script src="//cdn.polyfill.io/v2/polyfill.min.js?features=Intl.~locale.en"></script>
> this script will check brower have support Intl and add fallbacks

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
> This controller is our best important controller but have too many functions. Can splite to data-users data-stories data-newsletter data-quality controller

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
> We used this to add current user client info to $request

│   │   │   ├── ParseCurrentControllerAndAction.php
> used in side menu to get active menu

│   │   ├── Requests
│   │   │   └── Request.php
│   │   └── routes.php
│   ├── Models
> these are Eloquent Model an ORM solution [see more here](https://laravel.com/docs/5.1/eloquent)

│   │   ├── Analyses.php
│   │   ├── Client.php
│   │   ├── DataException.php
│   │   ├── Role.php
│   │   ├── Setting.php
│   │   └── User.php
│   └── Providers
│       ├── AuthServiceProvider.php 
> Regist Admin Role and SuperAdmin Role here

├── background
> these are Python backgournd corn job code

│   ├── analyticsClient.py
> get GA data helper function

│   ├── app.yaml
│   ├── appengine_config.py
│   ├── bigQueryClient.py
> get big query helper function now have issue for get too many result. e.g. SCPR data stories. some week have 50000+ records. will make corn job crash.

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
> Maybe set data menu here is bad idea

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
> we placed most of common javascript here. others are in view

│   │   └── vendor
> 3rd javascript library are here

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
> we splite these views by clients. need add new folder and views when add new client. Maybe we can add default views for normal client. try to check special view is exists otherwise used default views. this logical need add to DataController.php

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
> this is our main template. like index.html. all used css js files are here.
> Can add GA code here

|       │   ├── menu.blade.php
|       │   └── offCanvas.blade.php
> our side menu are here

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

***Others***
****for controller****
you can easy inherit AuthenticatedBaseController this base controller already added user check
```php
public function __construct()
{

    $this->middleware('auth');

}
```
or you can add this middleware by you self
other useful middleware are
```php
    $this->middleware('routeInfo');
    $this->middleware('clientInfo');
```
these middleware are all locationed at path app/Http/Middleware/
1. routeInfo are used for menu get current active menu
2. clientInfo are used get current user client info from $request

****for views****
we have 2 sections
@section('content') put html dom node here
@section('script') put this page javascript code here

speical for section script. we already add main.js in public/scripts/ for some common scripts
+ datepicker init.
+ datatable reload
+ download button event
+ tracking

For datetable reload. we used event to trigger this common javascript event. so for data page(users stories newsletter) we need add current page datatable instance to global windows variable `ReportDataTable` the property name is the table id. you can see this code
```html
<div class="panel">
    <div class="top-bar">
        @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
    </div>
    <table id="dataNewsLetter" class="report tiny hover">
    </table>
```
the main.js already add datepicker change event (means client changed display date) will trigger parent panel custom envet `change.daterange` and there have code to handle this event in main.js
```javascript
$(document).on('change.daterange', '.panel', function () {...});
```
this code is to get the table id and try to get datatable instance in gloabl variable `ReportDataTable` and try to reload data

For datepicker to set init date range. 
the main.js will check the hidden input name = 'defaultDateRange' html element value. you can used template widgets.daterange to render these html or add by your self. see more resources/views/widgets/daterange.blade.php
*****Notice*****
for stories are more speical requirment. only allow client selected one week so we do not used common daterange.

****for cron job****
+ we get big query hive sql from mysql table settings. 
+ the insert big query result to mysql sql are saved in query.py
+ for performance issue we only saved top 100 stories in mysql others are saved as csv file on google storage. the storage path in config.py that means for stage and production you must changed this value before deploy
+ history.py are quick code for run job.py history method
+ storiesClear.py seem no need used any more. this is quick code for get top 100 stories and saved to another table.






