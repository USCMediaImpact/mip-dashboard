import logging
import logging_config
import job

from datetime import datetime
from datetime import date
from datetime import timedelta
import calendar
#---------single debug ---------#
def unite_test():
	job.run('2016-06-25', '2016-06-25', 'weekly')

#---------big query debug ---------#
def big_query_test():
	import bigQueryClient

	sql = ('''SELECT '''
		'''  date, '''
		'''  IF(database_donors IS NULL  '''
		'''    AND ea = 'Intent' '''
		'''    AND ec = 'Support', cid, NULL) AS donor_cid '''
		'''FROM '''
		'''  (SELECT '''
		'''    a.donor_cid AS database_donors,  '''
		'''    TIMESTAMP(b.year + '-' + b.month + '-' + b.day) AS date, '''
		'''    b.ec AS ec, b.ea AS ea, b.cid AS cid '''
		'''   FROM '''
		'''    [test.donors] a '''
		'''    FULL OUTER JOIN EACH '''
		'''    (SELECT '''
		'''        hits.eventInfo.eventCategory AS ec, '''
		'''        hits.eventInfo.eventAction AS ea, '''
		'''        fullVisitorId AS cid, '''
		'''        /*Date*/ '''
		'''        REGEXP_EXTRACT(date, '(^[0-9]{4})') as year, '''
		'''        REGEXP_EXTRACT(date, '^[0-9]{4}([0-9]{2})') as month, '''
		'''        REGEXP_EXTRACT(date, '([0-9]{2}$)') as day       '''
		'''     FROM (TABLE_DATE_RANGE([test.ga_sessions_], TIMESTAMP('2016-06-26'), TIMESTAMP('2016-07-02') )) '''
		'''     WHERE fullVisitorId IS NOT NULL '''
		'''       AND hits.eventInfo.eventAction = 'Intent' '''
		'''       AND hits.eventInfo.eventCategory = 'Support') b '''
		'''     ON b.cid = a.donor_cid) '''
		'''WHERE '''
		'''  IF(database_donors IS NULL  '''
		'''    AND ea = 'Intent' '''
		'''    AND ec = 'Support', cid, NULL) IS NOT NULL '''
		'''GROUP BY date, donor_cid '''
		'''ORDER BY date desc ''')
	pid = 'mip-dashboard'
	dataset = 'test'
	datatable = 'donors'

	bigQueryClient.insert_from_query(sql, pid, dataset, datatable)

#---------histry task ---------#
# run history task
def run_history():
	min_date = date(2016, 4, 17)
	max_date = datetime.now().date()
	#every day history
	# day_count = (max_date - min_date).days
	# for single_date in (min_date + timedelta(n) for n in range(day_count)):
	# 	try:
	# 		logging.info('run daily history %s', single_date.strftime('%Y-%m-%d'))
	# 		job.run(single_date.strftime('%Y-%m-%d'), single_date.strftime('%Y-%m-%d'), 'daily')
	# 		logging.info('success')
	# 	except:
	# 		logging.error('faild')

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
	# min_month = date(min_date.year, min_date.month, 1)
	# max_month = add_months(min_month, 1) - timedelta(1)
	# while True:
	# 	try:
	# 		logging.info('run monthly history from %s to %s', min_month.strftime('%Y-%m-%d'), max_month.strftime('%Y-%m-%d'))
	# 		job.run(min_month.strftime('%Y-%m-%d'), max_month.strftime('%Y-%m-%d'), 'monthly')
	# 		logging.info('success')
	# 	except:
	# 		logging.error('faild')

	# 	if max_month > max_date :
	# 		break
	# 	min_month = add_months(min_month, 1)
	# 	max_month = add_months(min_month, 1) - timedelta(1)

	logging.info('run history job finished')

unite_test()