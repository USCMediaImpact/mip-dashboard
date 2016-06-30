#!/usr/bin/env python

import argparse
import logging
import uuid
import time
from apiclient.discovery import build
from googleapiclient.errors import HttpError
from oauth2client.client import GoogleCredentials

API_NAME = 'bigquery'
API_VERSION = 'v2'
NUM_RETRIES = 5

def get_services():
	credentials = GoogleCredentials.get_application_default()
	return build(API_NAME, API_VERSION, credentials=credentials)

def get_bq_result(sql, pid):
	bigquery = get_services()
	query_request = bigquery.jobs()
	job_id = uuid.uuid4()
	job_data = {
		'jobReference': {
			'projectId': pid,
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
		projectId=pid,
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

def insert_from_query(sql, pid, dataset, datatable):
	bigquery = get_services()
	job_data = {
		'jobReference': {
			'projectId': pid,
			'jobId': str(uuid.uuid4())
		},
		'configuration': {
			'query': {
				'query': sql,
				'destinationTable': {
					'projectId': pid,
					'datasetId': dataset,
					'tableId': datatable,
				},
				'writeDisposition': "WRITE_APPEND"
			}
		}
	}
	#insert new query job
	job = bigquery.jobs().insert(
		projectId=pid,
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
