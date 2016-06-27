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

def run(min_date, max_date, dimension):
	logging.debug(dimension + ' corn job is running at ' + unicode(datetime.now()) + ' from ' + min_date + ' to ' + max_date)
	client_settings = mySqlClient.query_client_settings()
	
	for client in client_settings:
		
		clientId = client[0]
		setting = json.loads(client[1])

		notTotallySuccess = False

		#query for data_users
		try:
			logging.debug(setting['data_users_dimension'])
			if dimension in setting['data_users_dimension']:
				_run_data_users(clientId, setting, min_date, max_date, dimension)
		except Exception:
			logging.error('run data users failed', exc_info=True)
			notTotallySuccess = True

		#query for data_stories
		try:
			if dimension in setting['data_stories_dimension']:
				_run_data_stories(clientId, setting, min_date, max_date, dimension)
		except Exception:
			logging.error('run data stories failed', exc_info=True)
			notTotallySuccess = True

		#query for data_quality
		try:
			if dimension in setting['data_quality_dimension']:
				_run_data_quality(clientId, setting, min_date, max_date, dimension)
		except Exception:
			logging.error('run data quality failed', exc_info=True)
			notTotallySuccess = True

	if notTotallySuccess :
		raise Exception('missing failed!')

def _run_data_users(client_id, setting, min_date, max_date, dimension):
	logging.debug('run data user job')
	parse_min_date = datetime.strptime(min_date, '%Y-%m-%d')
	parse_max_date = datetime.strptime(min_date, '%Y-%m-%d')
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

	hql = setting['bq_data_users'].format(prev_min_date=prev_min_date, min_date=min_date, prev_max_date=prev_max_date, max_date=max_date)

	logging.debug('big query: %s', hql)

	bq_data = bigQueryClient.get_bq_result(hql, setting['bq_id'])
	if bq_data:
		bq_data = bq_data[0]
	else:
		bq_data = (0,)
		for i in range(11) :
			bq_data += (0,)

	logging.debug('bigquery result : %s' % (bq_data,))

	sql = mysql.data_users.format(dimension=dimension)
	sql_data = (min_date, client_id) + bq_data + bq_data

	logging.debug('excute sql: %s' % (sql,))
	logging.debug('insert mysql data: %s' % (sql_data,))

	mySqlClient.insert_mysql(sql, [sql_data])

def _run_data_stories(client_id, setting, min_date, max_date, dimesion):
	hql = setting['bq_data_stories']

	logging.debug('big query: %s', hql)

	bq_data = bigQueryClient.get_bq_result(hql, setting['bq_id'])

	logging.debug('bigquery result : %s' % (bq_data,))

	md5 = hashlib.md5()
	md5.update(bq_data[0])
	path_md5 = md5.hexdigest()

	sql = mysql.data_stories.format(dimension=dimension)
	sql_data = (path_md5, client_id) + bq_data + bq_data

	logging.debug('excute sql: %s' % (sql,))
	logging.debug('insert mysql data: %s' % (sql_data,))

	mySqlClient.insert_mysql(sql, [sql_data])

def _run_data_quality(client_id, setting, min_date, max_date, dimension):
	logging.debug(clientId + '\n' + setting)

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

	hql = setting['bq_data_quality'].format(min_date=min_date, max_date=max_date)

	logging.debug('big query: %s', hql)

	bq_data = bigQueryClient.get_bq_result(hql, setting['bq_id'])

	logging.debug('bigquery result : %s' % (bq_data,))

	# if bq_data :
	# 	bq_data = bq_data[0]
	# else :
	# 	bq_data = (0,)
	# 	for i in range(14) :
	# 		bq_data += (0,)
	
	sql = mysql.data_quality.format(dimension=dimension)
	sql_data = (min_date, client_id, '', ga_data) + bq_data + ('', ga_data) + bq_data
	
	logging.debug('excute sql: %s' % sql)
	logging.debug('insert mysql data: %s' % (sql_data,))

	mySqlClient.insert_mysql(sql, [sql_data])