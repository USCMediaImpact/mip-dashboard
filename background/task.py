import logging
import webapp2

​class DailyTaskHandler(webapp2.RequestHandler):
    def get(self):
        logging.debug('corn job is running at' + unicode(datetime.datetime.now()))
​
app = webapp2.WSGIApplication([
    ('/etl/daily', DailyTaskHandler)
], debug=True)