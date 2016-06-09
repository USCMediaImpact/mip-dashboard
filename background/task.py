import logging
import webapp2

class ETLWorker(webapp2.RequestHandler):
  def get(self):
    self.response.out.write('ok')

​class DailyTaskHandler(webapp2.RequestHandler):
    def get(self):
        logging.debug('corn job is running at' + unicode(datetime.datetime.now()))
        self.response.out.write('ok')
​
app = webapp2.WSGIApplication([
    ('/etl/daily', DailyTaskHandler),
    ('/_ah/start', ETLWorker)
], debug=True)