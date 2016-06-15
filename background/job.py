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

DIMESIONS = {
	'daily': ('ga:date', mysql.data_quanlity_daily),
	'weekly': ('ga:week', mysql.data_quanlity_weekly),
	'monthly': ('ga:month', mysql.data_quanlity_monthly),
}

KPPC_GA_ID = '104512889'

def _run_custom(min_date, max_date, dimensions):
	logging.debug(dimensions + ' corn job is running at' + unicode(datetime.now()) + ' for ' + min_date + ' to ' + max_date)

	ga_data = analyticsClient.get_ga_result(
		KPPC_GA_ID, 
		min_date, 
		max_date, 
		'ga:users',
		DIMESIONS[dimensions][0])

	logging.debug('ga user: %s' % (ga_data,))

	if ga_data :
		ga_data = ga_data[0][1]
	else :
		ga_data = '0'
	
	logging.debug('ga user: %s' % (ga_data,))

	hql = hive.data_quanlity.format(min_date=min_date, max_date=max_date)

	logging.debug('big query: %s', hql)

	bq_data = bigQueryClient.get_bq_result(hql)

	logging.debug('bigquery result : %s' % (bq_data,))

	if bq_data :
		bq_data = bq_data[0]
	else :
		bq_data = (0,)
		for i in range(14) :
			bq_data += (0,)
	
	sql_data = (min_date, '') + (ga_data, ) + bq_data
	sql_data += sqldata
	logging.debug('need insert mysql data : %s' % (sql_data,))

	mySqlClient.insert_mysql(DIMESIONS[dimensions][1], sql_data)

class DailyTaskHandler(webapp2.RequestHandler):
	def get(self):
		yesterday = (date.today() - timedelta(1)).strftime('%Y-%m-%d')
		_run_custom(yesterday, yesterday, 'daily')
		self.response.out.write('ok')

class WeeklyTaskHandler(webapp2.RequestHandler):
	def get(self):
		today = date.today() - timedelta(1)
		day_of_week = today.weekday() # index from 0
		max_date = date.today() - timedelta(day_of_week + 2)
		min_date = (max_date - timedelta(6)).strftime('%Y-%m-%d')
		max_date = max_date.strftime('%Y-%m-%d')

		_run_custom(min_date, max_date, 'weekly')

		self.response.out.write('ok')

class MonthlyTaskHandler(webapp2.RequestHandler):
	def get(self):
		today = date.today()
		this_month = date(today.year, today.month, 1)
		max_date = this_month - timedelta(1)
		min_date = date(max_date.year, max_date.month, 1).strftime('%Y-%m-%d')
		max_date = max_date.strftime('%Y-%m-%d')

		_run_custom(min_date, max_date, 'monthly')

		self.response.out.write('ok')

class CurrentDayTaskHandler(webapp2.RequestHandler):
	def get(self):
		today = date.today().strftime('%Y-%m-%d')
		_run_custom(today, today, 'daily')
		self.response.out.write('ok')

class CurrentWeekTaskHandler(webapp2.RequestHandler):
	def get(self):
		today = date.today()
		day_of_week = today.weekday() # index from 0
		max_date = today.strftime('%Y-%m-%d')
		min_date = (today - timedelta(day_of_week + 1)).strftime('%Y-%m-%d')

		_run_custom(min_date, max_date, 'weekly')

		self.response.out.write('ok')

class CurrentMonthTaskHandler(webapp2.RequestHandler):
	def get(self):
		today = date.today()
		min_date = date(today.year, today.month, 1).strftime('%Y-%m-%d')
		max_date = today.strftime('%Y-%m-%d')

		_run_custom(min_date, max_date, 'monthly')

		self.response.out.write('ok')

app = webapp2.WSGIApplication([
	('/etl/daily', DailyTaskHandler),
	('/etl/weekly', WeeklyTaskHandler),
	('/etl/monthly', MonthlyTaskHandler),
	('/etl/current/day', CurrentDayTaskHandler),
	('/etl/current/week', CurrentWeekTaskHandler),
	('/etl/current/month', CurrentMonthTaskHandler),
], debug=True)
