#!/usr/bin/env python

import argparse
from apiclient.discovery import build
from oauth2client.client import SignedJwtAssertionCredentials
import httplib2
from oauth2client import client
from oauth2client import file
from oauth2client import tools
from OpenSSL import crypto

service_account_email = 'account-1@methodical-bee-111016.iam.gserviceaccount.com'
scope = ['https://www.googleapis.com/auth/analytics.readonly']
key_file_location = '3024963272a5.p12'
api_name = 'analytics'
api_version = 'v3'

def get_service():
	key_file = open(key_file_location, 'rb')
	key = key_file.read()
	key_file.close()

	credentials = SignedJwtAssertionCredentials(service_account_email, key, scope=scope)
	
	http = credentials.authorize(httplib2.Http())
	
	# Build the service object.
	return build(api_name, api_version, http=http)

def get_ga_result(profile_id, start_date, end_date, metrics, dimensions):
	result = []
   	data = get_service().data().ga().get(
		ids='ga:' + profile_id,
		start_date=start_date,
		end_date=end_date,
		metrics=metrics,
		dimensions=dimensions).execute()

   	for row in data :
   		result.push(data.get('rows'))
   		
   	return result