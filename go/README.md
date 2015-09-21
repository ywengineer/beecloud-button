使用go1.5， 基于Beego框架
（如需要学习Beego，请参看 [Beego](http://beego.me/docs/intro/)）

请将本文件夹拷贝至你自己的$GOPATH/src目录下

1) 运行前需要第三方库：    

```go
go get "github.com/astaxie/beego"  
go get "github.com/twinj/uuid"
```

2) 修改`controllers/default.go`文件里的`appid`和`secret`

3) 使用Beego的`Bee`工具运行项目