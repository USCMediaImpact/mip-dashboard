##Dashboard
This is base Backbone + React for the all new front-end application.

###Prepare
1. Make sure you have installed Node 5.x Browser and Grunt
2. The grunt-sass task requires you to have Ruby Sass and Compass installed
2. run `npm install` and `bower install`
3. run `grunt watch` when you begin work
4. run `grunt debug` to only build for local test
5. run `grunt release` to compile all files to dist folder

###Develop
you can use nignx or any web server to host app folder

###Release Test
you can run google cloud command to test the compiled static website after you run `grunt release`
`dev_appserver.py dist`


###Others
1. we have replace underscore by lodash see more [here](https://lodash.com/docs)
2. This project deployed to google app engine with python. the dashboard.py is to read index.html and send this file to response to mock a static website.

###Deploy
use `gcloud preview app deploy dist/app.yaml`
