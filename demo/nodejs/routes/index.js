var express = require('express');
var uuid = require('node-uuid');
var router = express.Router();
var sign = require('crypto');
var appid = "c244e073-bc37-43ce-974c-ae889ecaa273";
var secret = "263db492-172d-49e2-a5b0-322ba5d04515";
var titleMap = {
    30: "GouQi Service For 30 Days",
    90: "GouQi Service For 90 Days",
    365: "GouQi Service For 365 Days",
};
var priceMap = {
    30: 1100,
    90: 3000,
    365: 11000
};
/* GET home page. */
router.get('/', function(req, res, next) {
    var days = req.query.days || 30;
    var passport = req.query.name;
    var title = titleMap[days];
    var amount = '' + priceMap[days];
    var outTradeNo = uuid.v4().replace(/-/g, '');
    var data = appid + title + amount + outTradeNo + secret;
    var signStr = sign.createHash('md5').update(data, 'utf8').digest("hex");
    res.render('index', { title: 'JSButton', OutTradeNo: outTradeNo, Sign: signStr, days: days, passport: passport, title: title, amount: amount });
});

module.exports = router;
