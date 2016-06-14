#!/usr/bin/env python

import argparse
import sys
import os

from apiclient.discovery import build
from oauth2client.service_account import ServiceAccountCredentials
from httplib2 import Http

service_account_email = 'account-1@methodical-bee-111016.iam.gserviceaccount.com'
scope = ['https://www.googleapis.com/auth/analytics.readonly']
p12_file_location = os.path.join(os.path.dirname(os.path.realpath(__file__)), 'mip-analytics.p12')
api_name = 'analytics'
api_version = 'v3'

def get_service():
	credentials = ServiceAccountCredentials.from_p12_keyfile(
		service_account_email, 
		p12_file_location, 
		'notasecret',
		scope)
	return build(api_name, api_version, credentials=credentials)
	http_auth = credentials.authorize(Http())

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
