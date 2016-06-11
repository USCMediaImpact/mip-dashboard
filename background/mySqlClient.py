#!/usr/bin/env python
import argparse

import mysql.connector

CONFIG = {
  'user': 'root',
  'host': '127.0.0.1',
  'database': 'media_impact',
  'raise_on_warnings': True,
}

"""
	sql = ("INSERT INTO employees "
           "(first_name, last_name, hire_date, gender, birth_date) "
           "VALUES (%s, %s, %s, %s, %s)")
    data = [('Geert', 'Vanderkelen', tomorrow, 'M', date(1977, 6, 14)), ('Geert', 'Vanderkelen', tomorrow, 'M', date(1977, 6, 14))]
"""
def insert_mysql(sql, data):
	cnx = mysql.connector.connect(**CONFIG)
	cursor = cnx.cursor()
	for row in data:
		cursor.execute(sql, row)
	cnx.close()