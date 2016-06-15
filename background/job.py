#!/usr/bin/python
# -*- coding: utf-8 -*-

import logging
import webapp2
from datetime import datetime
from datetime import date
from datetime import timedelta
import calendar
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
	logging.debug(dimensions + ' corn job is running at ' + unicode(datetime.now()) + ' from ' + min_date + ' to ' + max_date)

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
	
	sql_data = ('',) + (ga_data, ) + bq_data
	sql_data = (min_date, ) + sql_data + sql_data
	logging.debug('excute sql: %s' % (DIMESIONS[dimensions][1],))
	logging.debug('insert mysql data: %s' % (sql_data,))
	logging.debug('run :\n' + DIMESIONS[dimensions][1] % sql_data)
	mySqlClient.insert_mysql(DIMESIONS[dimensions][1], [sql_data])

def add_months(sourcedate, months):
	month = sourcedate.month - 1 + months
	year = int(sourcedate.year + month / 12 )
	month = month % 12 + 1
	day = min(sourcedate.day,calendar.monthrange(year,month)[1])
	return date(year,month,day)

class DailyTaskHandler(webapp2.RequestHandler):
	def get(self):
		yesterday = (date.today() - timedelta(1)).strftime('%Y-%m-%d')
		_run_custom(yesterday, yesterday, 'daily')
		self.response.out.write('ok')

class WeeklyTaskHandler(webapp2.RequestHandler):
	def get(self):
		yesterday = date.today() - timedelta(1)
		day_of_week = yesterday.weekday() # index from 0
		max_date = yesterday - timedelta(day_of_week + 2)
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

class HistoryTaskHandler(webapp2.RequestHandler):
	def get(self):
		min_date = date(2016, 1, 1)
		max_date = date(2016, 6, 15)
		#every day history
		day_count = (max_date - min_date).days
		for single_date in (min_date + timedelta(n) for n in range(day_count)):
			try:
				_run_custom(single_date.strftime('%Y-%m-%d'), single_date.strftime('%Y-%m-%d'), 'daily')
			except:
				logging.debug('run daily history %s failed' % (single_date.strftime('%Y-%m-%d'), ))

		#every week history
		min_week = min_date - timedelta(min_date.weekday() + 1)
		max_week = min_week + timedelta(6)
		while True:
			try:
				_run_custom(min_week.strftime('%Y-%m-%d'), max_week.strftime('%Y-%m-%d'), 'weekly')
			except:
				logging.debug('run weekly history %s failed' % (min_week.strftime('%Y-%m-%d'), ))

			if max_week > max_date :
				break
			min_week += timedelta(7)
			max_week += timedelta(7)
		
		#every month history
		min_month = date(min_date.year, min_date.month, 1)
		max_month = add_months(min_month, 1) - timedelta(1)
		while True:
			try:
				_run_custom(min_month.strftime('%Y-%m-%d'), max_month.strftime('%Y-%m-%d'), 'monthly')
			except:
				logging.debug('run monthly history %s failed' % (single_date.strftime('%Y-%m-%d'), ))

			if max_month > max_date :
				break
			min_month = add_months(min_month, 1)
			max_month = add_months(min_month, 1) - timedelta(1)
		
		self.response.out.write('ok')

app = webapp2.WSGIApplication([
	('/etl/daily', DailyTaskHandler),
	('/etl/weekly', WeeklyTaskHandler),
	('/etl/monthly', MonthlyTaskHandler),
	('/etl/current/day', CurrentDayTaskHandler),
	('/etl/current/week', CurrentWeekTaskHandler),
	('/etl/current/month', CurrentMonthTaskHandler),
	('/etl/history', HistoryTaskHandler),
], debug=True)
