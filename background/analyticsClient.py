#!/usr/bin/env python

import argparse
import sys

from apiclient.discovery import build
from oauth2client.service_account import ServiceAccountCredentials
from httplib2 import Http

service_account_email = 'account-1@methodical-bee-111016.iam.gserviceaccount.com'
scope = ['https://www.googleapis.com/auth/analytics.readonly']
p12_file_location = 'mip-analytics.p12'
api_name = 'analytics'
api_version = 'v3'

def get_service():
	credentials = ServiceAccountCredentials.from_p12_keyfile(
    	service_account_email, 
    	p12_file_location, 
    	scope)

	http_auth = credentials.authorize(Http())

	# Build the service object.
	return build(api_name, api_version, http=http)

def get_ga_result(profile_id, start_date, end_date, metrics, dimensions):
   	query = get_service().data().ga().get(
		ids='ga:' + profile_id,
		start_date=start_date,
		end_date=end_date,
		metrics=metrics,
		dimensions=dimensions,
		#sort='-ga:visits',
      	#filters='ga:medium==organic',
      	#start_index='1',
      	#max_results='25'
		).execute()

   	result = []
   	response = query.get('rows', [])
   	if response:
   		for row in response:
   			result.push(row)

   	return result