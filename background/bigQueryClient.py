#!/usr/bin/env python

import argparse
import logging
from apiclient.discovery import build
from googleapiclient.errors import HttpError
from oauth2client.client import GoogleCredentials

API_NAME = 'bigquery'
API_VERSION = 'v2'
PROJECT_ID = 'mip-dashboard'

def get_services():
	credentials = GoogleCredentials.get_application_default()
	return build(API_NAME, API_VERSION, credentials=credentials)

def get_bq_result(sql):
	result = []
	query_request = get_services().jobs()
	query_data = {'query': sql}
	query_response = query_request.query(
		projectId=PROJECT_ID,
		body=query_data
	).execute()
	response = query.get('rows', [])
	if response :
		for row in response :
			dataRow = ()
			for field in row['f'] :
				dataRow += (field['v'],)
			result.append(dataRow)
	return result