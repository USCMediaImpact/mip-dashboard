#!/usr/bin/env python

import argparse
import logging
from googleapiclient.discovery import build
from googleapiclient.errors import HttpError
from oauth2client.client import GoogleCredentials

PROJECT_ID = 'mip-dashboard'

def get_bq_result(sql):
	result = []
	credentials = GoogleCredentials.get_application_default()
	bigquery_service = build('bigquery', 'v2', credentials=credentials)
	try:
        query_request = bigquery_service.jobs()
        query_data = {
            'query': sql
        }

        query_response = query_request.query(
            projectId=PROJECT_ID,
            body=query_data).execute()

        for row in query_response['rows']:
            dataRow = ()
      		for field in row['f']:
        		dataRow += (field['v'],)
            result.append(dataRow) 

    except HttpError as err:
        logging.error('Error: {}'.format(err.content))
		raise err
	return result