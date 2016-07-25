#!/usr/bin/env python
import argparse
import os
import MySQLdb

def get_db():
	env = os.getenv('SERVER_SOFTWARE')
  	if (env and env.startswith('Google App Engine/')):
		# Connecting from App Engine
		db = MySQLdb.connect(
			unix_socket='/cloudsql/mip-dashboard:mip-dashboard-prd',
			db='media_impact',
			user='root')
	else:
		# Connecting from an external network.
		# Make sure your network is whitelisted
		db = MySQLdb.connect(
			host='127.0.0.1',
			db='media_impact',
			port=3306,
			user='root')
	db.set_character_set('utf8')
	return db;

def query_client_settings():
	cursor = get_db().cursor()
	cursor.execute('SET NAMES utf8;')
	cursor.execute('SET CHARACTER SET utf8;')
	cursor.execute('SET character_set_connection=utf8;')
	query = (''' SELECT '''
			 '''     `a`.`id`, `a`.`code`, `b`.`values` '''
			 ''' FROM '''
			 '''     `clients` AS `a` '''
			 '''         INNER JOIN '''
			 '''     `settings` AS `b` ON `a`.`id` = `b`.`client_id` '''
			 ''' WHERE '''
			 '''     `b`.`enable_sync` = 1; ''')
	clients = cursor.execute(query)
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
	cursor.execute('SET NAMES utf8;')
	cursor.execute('SET CHARACTER SET utf8;')
	cursor.execute('SET character_set_connection=utf8;')
	for row in data:
		cursor.execute(sql, row)
	db.commit()
	cursor.close()
	db.close()
