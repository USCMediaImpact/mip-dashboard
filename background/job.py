#!/usr/bin/python
# -*- coding: utf-8 -*-
import logging
import webapp2
from datetime import datetime
import analyticsClient
import bigQueryClient
import mySqlCleint
from query import hive
from query import sql


class DailyTaskHandler(webapp2.RequestHandler):
    def get(self):
        logging.debug('weekly corn job is running at' + unicode(datetime.now()))
        profile_id = {'KPCC': '104512889'}
        yesterday = (date.today() - timedelta(1)).strftime('%Y-%m-%d')
        ga_data = get_ga_result('104512889', yesterday, yesterday, 'ga:users', 'ga:date')
        
        logging.debug('KPCC ga user: %s' % (ga_data,))
        
        bg_data = get_bg_result(hive.data_quanlity.format(
        	min_date=yesterday, 
        	max_date=yesterday))

        logging.debug('KPCC bigquery result : %s' % (bg_data,))

        insert_mysql(sql.data_quanlity_daily, (yesterday, '') + ga_data + bg_data)

        self.response.out.write('ok')

app = webapp2.WSGIApplication([
    ('/etl/daily', DailyTaskHandler)
], debug=True)