#!/usr/bin/env python

import argparse
import sys
import os

from apiclient.discovery import build
from oauth2client.client import GoogleCredentials

from oauth2client.service_account import ServiceAccountCredentials
from httplib2 import Http

SCOPE = ['https://www.googleapis.com/auth/analytics.readonly']
API_NAME = 'analytics'
API_VERSION = 'v3'
#for local debug
SERVICE_ACCOUNT_EMAIL = 'account-1@methodical-bee-111016.iam.gserviceaccount.com'
JSON_FILE_LOCATION = os.path.join(os.path.dirname(os.path.realpath(__file__)), 'mip-analytics.json')
P12_FILE_LOCATION = os.path.join(os.path.dirname(os.path.realpath(__file__)), 'mip-analytics.p12')


def get_service():
	env = os.getenv('SERVER_SOFTWARE')
  	if (env and env.startswith('Google App Engine/')):
		#credentials = GoogleCredentials.get_application_default()
		credentials = ServiceAccountCredentials.from_json_keyfile_name(
			JSON_FILE_LOCATION, 
			SCOPE)
	else:
		credentials = ServiceAccountCredentials.from_p12_keyfile(
			SERVICE_ACCOUNT_EMAIL, 
			P12_FILE_LOCATION, 
			'notasecret',
			SCOPE)

		
	return build(API_NAME, API_VERSION, credentials=credentials)

	# Build the service object.
	return build(api_name, api_version, http=http_auth)

def get_ga_result(profile_id, start_date, end_date, metrics, dimensions):
	query = get_service().data().ga().get(
		ids='ga:' + profile_id,
		start_date=start_date,
		end_date=end_date,
		metrics=metrics,
		dimensions=dimensions).execute()

	result = []
	response = query.get('rows', [])
	
	if response:
		for row in response:
			result.append(row)

	return result
