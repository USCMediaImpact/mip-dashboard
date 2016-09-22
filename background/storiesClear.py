import mySqlClient
from query import mysql

def reduce_stories(code) :
	db = mySqlClient.get_db()
	cursor = db.cursor()
	cursor.execute('SET NAMES utf8;')
	cursor.execute('SET CHARACTER SET utf8;')
	cursor.execute('SET character_set_connection=utf8;')
	query = cursor.execute('select date from %s_data_stories_weekly' % (code,))
	all_date = cursor.fetchmany(query)
	print all_date
	for date in all_date:
		print date[0]
		try:
			sql = "INSERT INTO SCPR_data_stories_weekly_top100 SELECT * FROM SCPR_data_stories_weekly WHERE date = '%s' ORDER BY Pageviews DESC LIMIT 100" % date
			cursor.execute(sql)
			db.commit()
		except:
			db.rollback()
	db.close()

reduce_stories('SCPR')