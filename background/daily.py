#!/usr/bin/python
# -*- coding: utf-8 -*-

import argparse
​
from apiclient.discovery import build
from oauth2client.client import SignedJwtAssertionCredentials
​
import httplib2
from oauth2client import client
from oauth2client import file
from oauth2client import tools
from OpenSSL import crypto
​
"""Get a service that communicates to a Google API.
  Args:
    api_name: The name of the api to connect to.
    api_version: The api version to connect to.
    scope: A list auth scopes to authorize for the application.
    key_file_location: The path to a valid service account p12 key file.
    service_account_email: The service account email address.
​
  Returns:
    A service that is connected to the specified API.
"""
def get_service(api_name, api_version, scope, key_file_location, service_account_email):
    f = open(key_file_location, 'rb')
    key = f.read()
    f.close()
    ​
    credentials = SignedJwtAssertionCredentials(service_account_email, key, scope=scope)
    ​
    http = credentials.authorize(httplib2.Http())
    ​
    # Build the service object.
    service = build(api_name, api_version, http=http)
    ​
    return service

# Use the Analytics Service Object to query the Core Reporting API
# for the number of sessions within the past seven days.
def get_results(service, profile_id, start_date, end_date):
    return service.data().ga().get(
        ids='ga:' + profile_id,
        start_date= start_date,
        end_date= end_date,
        metrics='ga:7dayUsers',
        dimensions='ga:date').execute()
​
​


def print_results(results, partner):
    if results:
        filename = partner + '-results.csv'
        f1 = open(filename, 'a')
        wr = csv.writer(f1)
        rows = results.get('rows')
        wr.writerow(['date', '7dayUsers'])
        for row in rows:
            wr.writerow(row)
    else:
        print 'No results found'
​


def run(date):
    scope = ['https://www.googleapis.com/auth/analytics.readonly']
​
	# Use the developer console and replace the values with your
	# service account email and relative location of your key file.
    service_account_email = 'account-1@methodical-bee-111016.iam.gserviceaccount.com'
    key_file_location = '3024963272a5.p12'
    profile_ids = {'TT': '103060727', 'KPCC': '104512889'}
​
	# Authenticate and construct service.
    service = get_service('analytics', 'v3', scope, key_file_location, service_account_email)
    for partner, profile_id in profile_ids.iteritems():
        print_results(get_results(service, profile_id), partner)
​
