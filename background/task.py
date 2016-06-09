#!/usr/bin/python
# -*- coding: utf-8 -*-
import logging
import webapp2

class DailyTaskHandler(webapp2.RequestHandler):
    def get(self):
        logging.debug('corn job is running at' + unicode(datetime.datetime.now()))
        self.response.out.write('ok')

app = webapp2.WSGIApplication([
    ('/etl/daily', DailyTaskHandler)
], debug=True)