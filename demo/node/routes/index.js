var express = require('express');
var router = express.Router();

/* GET home page. */
router.get('/', function(req, res, next) {
	
	var appid = "c5d1cba1-5e3f-4ba0-941d-9b0a371fe719";
	var secret = "39a7a518-9ac8-4a9e-87bc-7885f33cf18c";
	var title = "node.js water"
	var amount = "1"

	var uuid = require('node-uuid');
	var outTradeNo = uuid.v4();
	outTradeNo = outTradeNo.replace(/-/g, '');

	var data = appid + title + amount + outTradeNo + secret;
	var sign = require('crypto');
	var signStr = sign.createHash('md5').update(data).digest("hex");

  res.render('index', { title: 'JSButton', OutTradeNo: outTradeNo, Sign: signStr});
});

module.exports = router;
