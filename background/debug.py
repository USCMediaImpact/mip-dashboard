import logging
import logging_config
import job

job.run('2016-06-25', '2016-06-25', 'weekly')

# import bigQueryClient

# sql = ('''SELECT '''
# 	'''  date, '''
# 	'''  IF(database_donors IS NULL  '''
# 	'''    AND ea = 'Intent' '''
# 	'''    AND ec = 'Support', cid, NULL) AS donor_cid '''
# 	'''FROM '''
# 	'''  (SELECT '''
# 	'''    a.donor_cid AS database_donors,  '''
# 	'''    TIMESTAMP(b.year + '-' + b.month + '-' + b.day) AS date, '''
# 	'''    b.ec AS ec, b.ea AS ea, b.cid AS cid '''
# 	'''   FROM '''
# 	'''    [test.donors] a '''
# 	'''    FULL OUTER JOIN EACH '''
# 	'''    (SELECT '''
# 	'''        hits.eventInfo.eventCategory AS ec, '''
# 	'''        hits.eventInfo.eventAction AS ea, '''
# 	'''        fullVisitorId AS cid, '''
# 	'''        /*Date*/ '''
# 	'''        REGEXP_EXTRACT(date, '(^[0-9]{4})') as year, '''
# 	'''        REGEXP_EXTRACT(date, '^[0-9]{4}([0-9]{2})') as month, '''
# 	'''        REGEXP_EXTRACT(date, '([0-9]{2}$)') as day       '''
# 	'''     FROM (TABLE_DATE_RANGE([test.ga_sessions_], TIMESTAMP('2016-06-26'), TIMESTAMP('2016-07-02') )) '''
# 	'''     WHERE fullVisitorId IS NOT NULL '''
# 	'''       AND hits.eventInfo.eventAction = 'Intent' '''
# 	'''       AND hits.eventInfo.eventCategory = 'Support') b '''
# 	'''     ON b.cid = a.donor_cid) '''
# 	'''WHERE '''
# 	'''  IF(database_donors IS NULL  '''
# 	'''    AND ea = 'Intent' '''
# 	'''    AND ec = 'Support', cid, NULL) IS NOT NULL '''
# 	'''GROUP BY date, donor_cid '''
# 	'''ORDER BY date desc ''')
# pid = 'mip-dashboard'
# dataset = 'test'
# datatable = 'donors'

# bigQueryClient.insert_from_query(sql, pid, dataset, datatable)