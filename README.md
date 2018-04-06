Blog
========
这是一个个人博客项目

主要是为[bybzmt/framework.php](https://github.com/bybzmt/framework.php)框架提供一个用法示例。

当然这也是可以实际使用的博客，不过为了示例框架的功能采用了一些对博客而不必要的设计。

目录结构
----
```
├── assets       资源目录(如:字体文件等)
├── config
│   ├── dev      开发环境配置
│   ├── product  生产环境配置
├── index.php    项目入口
├── library      其它与composer不兼容的库
├── src
│   ├── Admin    管理后台
│   ├── Api      app接口端
│   ├── Backend  内部(内网)接口
│   ├── Common   公共代码目录
│   ├── Console  控制台
│   ├── Wap      手机Web端
│   └── Web      Web端
├── static
│   ├── admin    后台静态文件
│   └── web      Web端静态文件
├── tests        单元测试目录
├── var          可读写目录(如:模板缓存等)
└── vendor       composer库
```
