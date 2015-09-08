package controllers

import (
	"github.com/astaxie/beego"
	"github.com/twinj/uuid"
	"crypto/md5"
	"fmt"
	"encoding/hex"
	"strings"
)

type MainController struct {
	beego.Controller
}

func (c *MainController) Get() {
	//修改成自己的BeeCloud的appid和secret
	APPID := "c5d1cba1-5e3f-4ba0-941d-9b0a371fe719"
	APPSecret := "39a7a518-9ac8-4a9e-87bc-7885f33cf18c"
	
	//生成一个随机订单号
	OutTradeNo := strings.Replace(uuid.NewV4().String(), "-", "", -1)
	fmt.Println(OutTradeNo)
	
	//计算签名
	SignString := APPID + "go water" + "1" + OutTradeNo + APPSecret
	Sign := md5.New()
	Sign.Write([]byte(SignString))
	SignStr := hex.EncodeToString(Sign.Sum(nil))
	fmt.Println(SignStr)
	
	//
	c.Data["OutTradeNo"] = OutTradeNo
	c.Data["Sign"] = SignStr
	c.TplNames = "index.tpl"
}
