#!/usr/bin/env python
import argparse
import MySQLdb

"""
	sql = ("INSERT INTO employees "
		   "(first_name, last_name, hire_date, gender, birth_date) "
		   "VALUES (%s, %s, %s, %s, %s)")
	data = [('Geert', 'Vanderkelen', tomorrow, 'M', date(1977, 6, 14)), ('Geert', 'Vanderkelen', tomorrow, 'M', date(1977, 6, 14))]
"""
def insert_mysql(sql, data):
	env = os.getenv('SERVER_SOFTWARE')
  	if (env and env.startswith('Google App Engine/')):
		# Connecting from App Engine
		db = MySQLdb.connect(
			unix_socket='/cloudsql/mip-dashboard:test-mip-dashboard',
			database='media_impact',
			user='root')
	else:
		# Connecting from an external network.
		# Make sure your network is whitelisted
		db = MySQLdb.connect(
			host='127.0.0.1',
			database='media_impact',
			port=3306,
			user='root')

	cursor = db.cursor()
	for row in data:
		cursor.execute(sql, row)	