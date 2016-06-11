#!/usr/bin/python
# -*- coding: utf-8 -*-
import logging
import webapp2
from datetime import datetime
import analyticsClient
import bigQueryClient
import mySqlCleint

class DailyTaskHandler(webapp2.RequestHandler):
    def get(self):
        logging.debug('weekly corn job is running at' + unicode(datetime.now()))
        profile_id = {'KPCC': '104512889'}
        today = date.today().isoformat()
        ga_data = get_ga_result('104512889', today, today, 'ga:users', 'ga:date')
        print ga_data
        # insert_mysql((
        # 	"insert into "
        # 	), ga_data)
        self.response.out.write('ok')

app = webapp2.WSGIApplication([
    ('/etl/daily', DailyTaskHandler)
], debug=True)