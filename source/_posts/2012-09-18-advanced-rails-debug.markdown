---
layout: post
title: "Advanced Rails debug"
date: 2012-09-18 18:32
comments: true
categories: 
---
这是我在[Ruby Tuesday](http://ruby-china.org/topics/5582)上分享的记录，介绍几种调试Rails程序的方法

###1. ruby-debug, ruby-debug19
没人维护，bug多。Ruby 1.9.3后不推荐使用

###2. debugger
ruby 1.9.3后的选择，功能强大，缺点是没有语法高亮。

####项目中引用debugger方法
修改Gemfile

```ruby
group :development, :test do
  gem 'debugger'
end
```

在需要设置断点的地方：

```
require 'debugger'; debugger
```

####查看源文件方法
```
list       #往后翻看代码
list- [n]  #往前翻看代码
list=      #查看当前运行处代码
```
list可以缩写为l

####查看方法栈（stack frames）
```
backtrace/where               # 查看
frame [n]                     # 跳到指定stack frames
up [n] (缩写u) 和 down [n]     # 在stack frames层里来回切换
```

####执行控制
```
next           # 执行下一行，遇到方法调用不进入。
step           # 执行一步，遇到方法调用进入。
continue/c     # 执行到下一个断点。
finish [n]     # 执行到指定的frame才返回。注意编号是从下往上数。
```

####其它常用命令
```
help
info
```

###3. pry
替代irb，我最喜欢的是语法高亮。

```
help
cd ..
whereami
self
ls
show-doc
show-method
edit-method
```

####pry-nav
pry只是替代erb，并不能调试rails，加上rails后即可pry-nav。
增加3种基本调试指令。

```
step
next
continue
```

####pry-stack_explorer
Pry的插件，用于查看方法栈

```
(pry) main: 0> show-stack

Showing all accessible frames in stack (5 in total):
--
=> #0 [method] gamma
   #1 [method] beta
   #2 [method] alpha
```

####使用pry调试rails项目
修改Gemfile

```
group :development, :test do
  gem 'pry'
  gem 'pry-nav'
  gem 'pry-stack_explorer' # 如果不查看方法栈，可以省略
end
```

在需要设置断点的地方：

```
binding.pry
```

###4. 参考
<https://github.com/cldwalker/debugger>

<https://github.com/pry/pry>

<https://github.com/pry/pry-stack_explorer>

<http://guides.rubyonrails.org/debugging_rails_applications.html>
