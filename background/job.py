#!/usr/bin/python
# -*- coding: utf-8 -*-

import logging
import webapp2
from datetime import datetime
from datetime import date
from datetime import timedelta
import analyticsClient
import bigQueryClient
import mySqlClient
from query import hive
from query import mysql


class DailyTaskHandler(webapp2.RequestHandler):
	def get(self):
		logging.debug('weekly corn job is running at' + unicode(datetime.now()))
		profile_id = {'KPCC': '104512889'}
		yesterday = (date.today() - timedelta(1)).strftime('%Y-%m-%d')
		ga_data = analyticsClient.get_ga_result('104512889', 
			yesterday, 
			yesterday, 
			'ga:users',
			'ga:date')

		logging.debug('KPCC ga user: %s' % (ga_data,))

		if not ga_data :
			ga_data = ga_data[0][1]
		else :
			ga_data = ''
		
		hql = hive.data_quanlity.format(min_date=yesterday, max_date=yesterday)
		bq_data = bigQueryClient.get_bq_result(hql)

		logging.debug('KPCC bigquery result : %s' % (bq_data,))

		mySqlClient.insert_mysql(mysql.data_quanlity_daily, (yesterday, '') + (ga_data, ) + bq_data)

		self.response.out.write('ok')

app = webapp2.WSGIApplication([
	('/etl/daily', DailyTaskHandler)
], debug=True)
