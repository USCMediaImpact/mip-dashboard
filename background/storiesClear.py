import mySqlClient
from query import mysql
import logging

def reduce_stories(code) :
	logging.info('begin run %s stories clearup')
	db = mySqlClient.get_db()
	cursor = db.cursor()
	cursor.execute('SET NAMES utf8;')
	cursor.execute('SET CHARACTER SET utf8;')
	cursor.execute('SET character_set_connection=utf8;')
	query = cursor.execute('select date from %s_data_stories_weekly group by date' % (code,))
	all_date = cursor.fetchmany(query)
	for date in all_date:
		logging.info('clear up %s date' % date[0])
		try:
			sql = "INSERT INTO SCPR_data_stories_weekly_top100 SELECT * FROM SCPR_data_stories_weekly WHERE date = '%s' ORDER BY Pageviews DESC LIMIT 100" % date
			cursor.execute(sql)
			db.commit()
		except:
			logging.error('Failed clear up %s date %s' % (code, date[0]))
			db.rollback()
	db.close()
	logging.info('run %s stories clearup completed')