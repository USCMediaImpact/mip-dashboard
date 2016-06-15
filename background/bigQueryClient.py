#!/usr/bin/env python

import argparse
import logging
import uuid
from apiclient.discovery import build
from googleapiclient.errors import HttpError
from oauth2client.client import GoogleCredentials

API_NAME = 'bigquery'
API_VERSION = 'v2'
PROJECT_ID = 'tonal-studio-119521'
NUM_RETRIES = 5

def get_services():
	credentials = GoogleCredentials.get_application_default()
	return build(API_NAME, API_VERSION, credentials=credentials)

def get_bq_result(sql):
	query_request = get_services().jobs()
	job_id = uuid.uuid4()
	job_data = {
		'jobReference': {
			'projectId': PROJECT_ID,
			'job_id': str(uuid.uuid4())
		},
		'configuration': {
			'query': {
				'query': sql,
				'priority': 'INTERACTIVE'
			}
		}
	}
	#insert new query job
	job = bigquery.jobs().insert(
		projectId=PROJECT_ID,
		body=job_data).execute(num_retries=NUM_RETRIES)

	#query job status
	job_request = bigquery.jobs().get(
		projectId=job['jobReference']['projectId'],
		jobId=job['jobReference']['jobId'])

	while True:
		job_status = job_request.execute(num_retries=2)

		if job_status['status']['state'] == 'DONE':
			if 'errorResult' in job_status['status']:
				raise RuntimeError(job_status['status']['errorResult'])
			logging.debug('big query job done')
			break

		time.sleep(1)

	#get result
	page_token = None
	result = []
	while True:
		page = bigquery.jobs().getQueryResults(
				pageToken=page_token,
				**job['jobReference']).execute(num_retries=2)

		data = page.get('rows', [])
		if data :
			for row in data :
				dataRow = ()
				for field in row['f'] :
					dataRow += (field['v'],)
				result.append(dataRow)

		page_token = page.get('pageToken')
			if not page_token:
				break
	
	return result
