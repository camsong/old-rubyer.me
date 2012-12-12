---
layout: post
title: "Ruby Simple HTTP Server with Rack"
date: 2012-12-12 14:56
comments: true
categories: 
---
实现在任意目录启动一个http server并把当前目录做为web根目录，类似Python的SimpleHTTPServer。

2步搞定：

### 1. 安装rack，并新建rack配置文件config.ru：

```
gem install rack
echo "run Rack::Directory.new('')" > ~/config.ru
```

### 2. 切换到任意目录运行

```
rackup ~/config.ru
```

如果觉得命令长就建立alias `alias rp='rackup ~/config.ru'`

默认启动的server端口是9292，`-p [port_num]`可以修改端口号，打开浏览器即可当前目录的文件列表：
<http://locahost:9292/>

###Appendix: rack and rackup

####Rack:
Rack provides a minimal, modular and adaptable interface for developing web applications in Ruby. By wrapping HTTP requests and responses in the simplest way possible, it unifies and distills the API for web servers, web frameworks, and software in between (the so-called middleware) into a single method call.

####Rackup:
Rackup is a useful tool for running Rack applications, which uses the Rack::Builder DSL to configure middleware and build up applications easily.


### Thanks to
<http://blog.samsonis.me/2010/02/rubys-python-simplehttpserver/>

<https://github.com/rack/rack>
