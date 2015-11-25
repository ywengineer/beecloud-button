# -*- coding: utf-8 -*-
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web
import os
import hashlib

from time import time
from tornado.options import define, options

define("port", default=8088, help="run on the given port", type=int)
bc_app_id = 'c5d1cba1-5e3f-4ba0-941d-9b0a371fe719'
bc_app_secret = '39a7a518-9ac8-4a9e-87bc-7885f33cf18c'

class SpayButtonHandler(tornado.web.RequestHandler):
    def get(self):
        title = "test"
        out_trade_no = "test" + str(int(time()))
        amount = "1"
        #2 计算签名sign
        md5 = hashlib.md5()
        md5.update(bc_app_id + title + amount + out_trade_no + bc_app_secret)
        sign = md5.hexdigest()
        self.render("templates/spay-button.html", title=title, amount=amount, out_trade_no=out_trade_no, sign=sign)

def main():
    settings = {"static_path": os.path.join(os.path.dirname(__file__), "static")}
    tornado.options.parse_command_line()
    application = tornado.web.Application([(r"/", SpayButtonHandler)], **settings)
    http_server = tornado.httpserver.HTTPServer(application)
    http_server.listen(options.port)
    tornado.ioloop.IOLoop.instance().start()
if __name__ == "__main__":
	main()
