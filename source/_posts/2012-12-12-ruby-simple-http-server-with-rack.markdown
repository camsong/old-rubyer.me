---
layout: post
title: "Ruby Simple HTTP Server with Rack"
date: 2012-12-12 14:56
comments: true
categories: 
---
最近使用JavaScript比较多，如果是静态页面，Chrome AJAX请求本地文件经常会出现。
```
XMLHttpRequest cannot load
file:///*******. 
Origin null is not allowed by Access-Control-Allow-Origin.
```
这里因为Chrome安全机制不允许AJAX加载本地文件，你可以启动Chrome时加参数`chrome.exe --allow-file-access-from-files`。
但我不喜欢这种方法，偏好启动一个web server。

Python 2.\* 的做法是`python -m SimpleHTTPServer`，Python 3.\* 的做法是`python -m http.server`，Ruby的做法也非常简单。

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
