#!/usr/bin/python
# -*- coding: utf-8 -*-

import logging
from datetime import datetime
from datetime import date
from datetime import timedelta
import calendar
import analyticsClient
import bigQueryClient
import mySqlClient
from query import mysql
import json
import hashlib
from cloudStorage import download
from cloudStorage import upload
from dateutil.parser import parse
import StringIO

DIMESIONS = {
	'daily': 'ga:date',
	'weekly': 'ga:week',
	'monthly': 'ga:month',
}

def add_months(sourcedate, months):
	month = sourcedate.month - 1 + months
	year = int(sourcedate.year + month / 12 )
	month = month % 12 + 1
	day = min(sourcedate.day,calendar.monthrange(year,month)[1])
	return date(year,month,day)

def format_hive(sql, min_date, max_date, dimension):
	parse_min_date = datetime.strptime(min_date, '%Y-%m-%d')
	parse_max_date = datetime.strptime(max_date, '%Y-%m-%d')
	if dimension == 'dialy':
		prev_min_date = parse_min_date - timedelta(1)
		prev_max_date = parse_max_date - timedelta(1)
	elif dimension == 'weekly':
		prev_min_date = parse_min_date - timedelta(7)
		prev_max_date = parse_max_date - timedelta(7)
	elif dimension == 'monthly':
		prev_min_date = add_months(parse_min_date, -1)
		prev_max_date = date(parse_max_date.year, parse_max_date.month, 1) - timedelta(1)
	else:
		prev_min_date = parse_min_date - timedelta(1)
		prev_max_date = parse_max_date - timedelta(1)

	prev_min_date = prev_min_date.strftime('%Y-%m-%d')
	prev_max_date = prev_max_date.strftime('%Y-%m-%d')

	return sql.format(min_date=min_date, max_date=max_date, prev_min_date=prev_min_date, prev_max_date=prev_max_date)

def run(min_date, max_date, dimension):
	logging.debug(dimension + ' corn job is running at ' + unicode(datetime.now()) + ' from ' + min_date + ' to ' + max_date)
	client_settings = mySqlClient.query_client_settings()

	for client in client_settings:
		clientId = client[0]
		code = client[1]
		setting = json.loads(client[2])
		logging.info('----------Begin Run %s----------' % (code, ))

		notTotallySuccess = False
		logging.info('----------Begin Run %s Prepare----------' % (code, ))
		try:
			logging.info('run %s prepare' % code)
			_run_prepare(clientId, setting, min_date, max_date, dimension)
		except Exception:
			logging.error('run prepare %s failed' % code, exc_info=True)
			notTotallySuccess = True

		#query for data_users
		logging.info('----------Begin Run %s Data Users----------' % (code, ))
		try:
			logging.info('run %s data users' % code)
			logging.debug(setting['data_users_dimension'])
			if dimension in setting['data_users_dimension']:
				_run_data_users(clientId, code, setting, min_date, max_date, dimension)
		except Exception:
			logging.error('run %s data users failed' % code, exc_info=True)
			notTotallySuccess = True

		#query for data_stories
		logging.info('----------Begin Run %s Data Stories----------' % (code, ))
		try:
			logging.info('run %s data stories' % code)
			logging.debug(setting['data_users_dimension'])
			if dimension in setting['data_stories_dimension']:
				_run_data_stories(clientId, code, setting, min_date, max_date, dimension)
		except Exception:
			logging.error('run %s data stories failed' % code, exc_info=True)
			notTotallySuccess = True

		#query for data_quality
		logging.info('----------Begin Run %s Data Quality----------' % (code, ))
		try:
			logging.info('run %s data quality' % code)
			if dimension in setting['data_quality_dimension']:
				_run_data_quality(clientId, code, setting, min_date, max_date, dimension)
		except Exception:
			logging.error('run %s data quality failed' % code, exc_info=True)
			notTotallySuccess = True

		logging.info('----------End Run %s----------' % (code, ))

	if notTotallySuccess :
		raise Exception('missing failed!')

def _run_prepare(client_id, setting, min_date, max_date, dimension):
	for prepare in setting['bq_prepare']:
		if 'table' in prepare and 'sql' in prepare and prepare['table'] is not None:
			hive = format_hive(prepare['sql'], min_date, max_date, dimension)
			info = prepare['table'].split('.')
			if len(info) != 2:
				logging.error('table %s missing' % (prepare['table'], ))
				continue
			logging.debug('begin update table %s %s \n%s' % (info[0], info[1], hive))
			bigQueryClient.insert_from_query(hive, setting['bq_id'], info[0], info[1])

def _run_data_users(client_id, code, setting, min_date, max_date, dimension):
	logging.debug('run %s data user job' % code)

	hql = format_hive(setting['bq_data_users'], min_date, max_date, dimension)

	logging.debug('big query: %s', hql)

	bq_data = bigQueryClient.get_bq_result(hql, setting['bq_id'])
	if bq_data:
		bq_data = bq_data[0]
	else:
		bq_data = (0,)
		for i in range(11) :
			bq_data += (0,)

	logging.debug('bigquery result : %s' % (bq_data,))

	sql = mysql.data_users[code].format(dimension=dimension)
	sql_data = (min_date,) + bq_data + bq_data

	logging.debug('excute sql: %s' % (sql,))
	logging.debug('insert mysql data: %s' % (sql_data,))

	mySqlClient.insert_mysql(sql, [sql_data])

def _run_data_stories(client_id, code, setting, min_date, max_date, dimension):
	logging.debug(setting['bq_data_stories'])

	hql = format_hive(setting['bq_data_stories'], min_date, max_date, dimension)

	logging.debug('big query: %s', hql)

	bq_data = bigQueryClient.get_bq_result(hql, setting['bq_id'])

	logging.debug('bigquery result : %s' % len(bq_data))

	if len(bq_data) > 0 :
		sql = mysql.data_stories[code].format(dimension=dimension)
		sql_data = []
		md5 = hashlib.md5()
		csv_data = []
		limit = 100
		index = 0
		for row in bq_data:
			md5.update((row[0] or '' + '_:_' + row[1] or '').encode("utf-8"))
			path_md5 = md5.hexdigest()
			if index < limit :
				sql_data.append((min_date, path_md5,) + row + row)
			csv_data.append(row)
			index += 1

		logging.debug('excute sql: %s' % (sql,))
		logging.debug('insert mysql data: %s' % (sql_data[0],))

		mySqlClient.insert_mysql(sql, sql_data)
		_run_data_stories_csv(code, min_date, csv_data)

def _run_data_stories_csv(code, min_date, data):
	logging.debug('save to csv')
	import csv
	import config

	# with cloudstorage.open('/%s/%s/%s.csv' % (config.CSV_BUCKET, code, min_date, ), 'w') as file_obj:
	file_obj = StringIO.StringIO()
	writer = csv.writer(file_obj)
	for row in data:
		row_data = []
		for field in row:
			if field is not None :
				row_data.append(unicode(field).encode('utf-8'))
			else :
				row_data.append('')
		writer.writerow(row_data)
	file_obj.seek(0)
	upload(config.CSV_BUCKET, '%s/%s.csv' % (code, min_date, ), file_obj)

def _run_data_quality(client_id, code, setting, min_date, max_date, dimension):
	logging.debug('run ga with %s for client %s' % (client_id, setting['ga_id']))
	ga_data = analyticsClient.get_ga_result(
		setting['ga_id'],
		min_date,
		max_date,
		'ga:users',
		DIMESIONS[dimension])

	logging.debug('ga user: %s' % (ga_data,))

	if ga_data :
		ga_data = ga_data[0][1]
	else :
		ga_data = '0'

	logging.debug('ga user: %s' % (ga_data,))

	bq_data = ()
	for key in range(1,7):
		hql = format_hive(setting['bq_data_quality_t%s' % (key, )], min_date, max_date, dimension)
		logging.debug('big query: %s', hql)
		data = bigQueryClient.get_bq_result(hql, setting['bq_id'])
		logging.debug('bigquery result : %s' % (data,))
		bq_data += data[0]

	sql = mysql.data_quality[code].format(dimension=dimension)
	sql_data = (min_date, ga_data) + bq_data + (ga_data, ) + bq_data

	logging.debug('excute sql: %s' % sql)
	logging.debug('insert mysql data: %s' % (sql_data,))

	mySqlClient.insert_mysql(sql, [sql_data])

def _run_data_newsletter(file_name, code, dimension):
	logging.debug('run newsletter csv import for file: %s' % (file_name,))
	import os, csv
	try:
		os.remove('./tmp.csv')
	except OSError:
		pass

	with open('./tmp.csv', 'wb') as file_obj:
		download(bucket_name='mip-newsletter-data',
			path=file_name,
			file_obj=file_obj)
	with open('./tmp.csv', 'rb') as file_obj:
		spamreader = csv.reader(file_obj)
		sql = mysql.data_newsletter[code].format(dimension=dimension)
		db = mySqlClient.get_db()
		cursor = db.cursor()
		cursor.execute('SET NAMES utf8;')
		cursor.execute('SET CHARACTER SET utf8;')
		cursor.execute('SET character_set_connection=utf8;')
		spamreader.next()
		for row in spamreader:
			date = parse(row[3]).strftime('%Y-%m-%d %H:%M:%S')
			row[3] = date
			row[13] = float(row[13].replace('%', '')) / 100
			row[16] = float(row[16].replace('%', '')) / 100
			row.insert(0, date)
			cursor.execute(sql, row[0:22])
		db.commit()
		cursor.close()
		db.close()



