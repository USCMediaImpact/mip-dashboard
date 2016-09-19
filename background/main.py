#!/usr/bin/python
# -*- coding: utf-8 -*-

import logging
import webapp2
from datetime import datetime
from datetime import date
from datetime import timedelta
import calendar
import job
from dateutil.parser import parse

class DailyTaskHandler(webapp2.RequestHandler):
	def get(self):
		yesterday = (date.today() - timedelta(1)).strftime('%Y-%m-%d')
		job.run(yesterday, yesterday, 'daily')
		self.response.out.write('ok')

class WeeklyTaskHandler(webapp2.RequestHandler):
	def get(self):
		today = date.today()
		if today.weekday() == 6 :
			max_date = today - timedelta(1)
		else :
			monday = today - timedelta(today.weekday())
			max_date = monday - timedelta(2)

		min_date = (max_date - timedelta(6)).strftime('%Y-%m-%d')
		max_date = max_date.strftime('%Y-%m-%d')

		job.run(min_date, max_date, 'weekly')

		self.response.out.write('ok')

class WeeklyCustomDateTaskHandler(webapp2.RequestHandler):
	def get(self):
		min_date = self.request.get('min_date')
		max_date = self.request.get('max_date')

		job.run(min_date, max_date, 'weekly')

		self.response.out.write('ok')

class MonthlyTaskHandler(webapp2.RequestHandler):
	def get(self):
		today = date.today()
		this_month = date(today.year, today.month, 1)
		max_date = this_month - timedelta(1)
		min_date = date(max_date.year, max_date.month, 1).strftime('%Y-%m-%d')
		max_date = max_date.strftime('%Y-%m-%d')

		job.run(min_date, max_date, 'monthly')

		self.response.out.write('ok')

class HistoryTaskHandler(webapp2.RequestHandler):
	def get(self):
		min_date = parse(self.request.get('min_date'))
		max_date = parse(self.request.get('max_date'))

		#every day history
		day_count = (max_date - min_date).days
		for single_date in (min_date + timedelta(n) for n in range(day_count)):
			try:
				logging.info('run daily history %s', single_date.strftime('%Y-%m-%d'))
				job.run(single_date.strftime('%Y-%m-%d'), single_date.strftime('%Y-%m-%d'), 'daily')
				logging.info('success')
			except:
				logging.error('faild')

		#every week history
		min_week = min_date - timedelta(min_date.weekday() + 1)
		max_week = min_week + timedelta(6)
		while True:
			try:
				logging.info('run weekly history from %s to %s', min_week.strftime('%Y-%m-%d'), max_week.strftime('%Y-%m-%d'))
				job.run(min_week.strftime('%Y-%m-%d'), max_week.strftime('%Y-%m-%d'), 'weekly')
				logging.info('success')
			except:
				logging.error('faild')

			if max_week > max_date :
				break
			min_week += timedelta(7)
			max_week += timedelta(7)

		#every month history
		min_month = date(min_date.year, min_date.month, 1)
		max_month = add_months(min_month, 1) - timedelta(1)
		while True:
			try:
				logging.info('run monthly history from %s to %s', min_month.strftime('%Y-%m-%d'), max_month.strftime('%Y-%m-%d'))
				job.run(min_month.strftime('%Y-%m-%d'), max_month.strftime('%Y-%m-%d'), 'monthly')
				logging.info('success')
			except:
				logging.error('faild')

			if max_month > max_date :
				break
			min_month = add_months(min_month, 1)
			max_month = add_months(min_month, 1) - timedelta(1)

		logging.info('run history job finished')
		self.response.out.write('ok')

app = webapp2.WSGIApplication([
	# ('/etl/daily', DailyTaskHandler),
	('/etl/weekly', WeeklyTaskHandler),
	# ('/etl/monthly', MonthlyTaskHandler),
	('/etl/history', HistoryTaskHandler),
	('/etl/weekly/custom', WeeklyCustomDateTaskHandler),
], debug=True)
