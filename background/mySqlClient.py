#!/usr/bin/env python
import argparse
import os
import MySQLdb

def get_db():
	env = os.getenv('SERVER_SOFTWARE')
  	if (env and env.startswith('Google App Engine/')):
		# Connecting from App Engine
		return MySQLdb.connect(
			unix_socket='/cloudsql/mip-dashboard:mip-dashboard-prd',
			db='media_impact',
			user='root')
	else:
		# Connecting from an external network.
		# Make sure your network is whitelisted
		return MySQLdb.connect(
			host='127.0.0.1',
			db='media_impact',
			port=3306,
			user='root')

def query_client_settings():
	cursor = get_db().cursor()
	clients = cursor.execute('SELECT `client_id`, `values` FROM `media_impact`.`settings` WHERE `enable_sync` = 1')
	return cursor.fetchmany(clients)

"""
	sql = ("INSERT INTO employees "
		   "(first_name, last_name, hire_date, gender, birth_date) "
		   "VALUES (%s, %s, %s, %s, %s)")
	data = [('Geert', 'Vanderkelen', tomorrow, 'M', date(1977, 6, 14)), ('Geert', 'Vanderkelen', tomorrow, 'M', date(1977, 6, 14))]
"""
def insert_mysql(sql, data):
	db = get_db()
	cursor = db.cursor()
	for row in data:
		cursor.execute(sql, row)
	db.commit()
	cursor.close()
	db.close()
