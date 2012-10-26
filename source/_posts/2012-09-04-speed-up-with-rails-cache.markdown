---
layout: post
title: "Speed up with Rails cache"
date: 2012-09-04 10:51
comments: true
categories: 
---
这是我在[Ruby Tuesday](http://ruby-china.org/topics/5281)上分享的记录，介绍Rails缓存的使用方法

#### Two Big Problems
> There are only two hard problems in Computer Science: cache
> invalidation and naming things. - Phil Karlton

Fortunately, Rails has made it perfect!

### 启用缓存
默认development模式禁用缓存，production环境启用缓存
```ruby
config.action_controller.perform_caching = true
```

### 缓存核心：`Rails.cache`
3种基本操作

* 读
`Rails.cache.write 'foo', 'bar'`

* 写
`Rails.cache.read 'foo'`

* 不存在则写，存在则读

`Rails.cache.fetch 'a_big_data' do { (1..1000000).inject(:+) }`

缓存默认是以文件形式保存，文件位置
`./tmp/cache`

### 缓存原理

```
def body_html 
  Rails.cache.fetch "#{cache_key}/body_html" do
    render(body) 
  end
end
```

### 缓存策略

* `Rails.cache`
* Fragment caching
* Action caching
* Page caching
* HTTP caching

### Fragment Caching
```
<%= cache @post do %>
  <p>
    <b>Title:</b>
    <%= @post.title %>
  </p>

  <p>
    <b>Content:</b>
    <%= @post.content %>
  </p>
<% end %>
```
手动设置过期

```
expire_fragment(:controller => 'products', :action => 'recent',
:action_suffix => 'all_products')
```

### Fragment Caching 效果
![Fragment caching result](http://rubyer.me/fragment-caching-result.png)

### Fragment Caching key生成策略
```
cache 'explicit-key'      # views/explicit-key
cache @post               # views/posts/2-1283479827349
cache [@post, 'sidebar']  # views/posts/2-2348719328478/sidebar
cache [@post, @comment]   #
views/posts/2-2384193284878/comments/1-2384971487
cache :hash => :of_things # views/localhost:3000/posts/2?hash_of_things
```

### Action Caching
```
caches_action :index, :cache_path => proc {|c| { :tag =>
Post.maximum('updated_at') } }
```

### Page Caching

```
caches_page :index
```
特点：
* 很快但无用
* 第一次访问时会在`public`目录生成静态html结尾文件，此后访问就会跳过所有validation和filter。

### HTTP Caching

##### 报文头：
```
Cache-Control: max-age=0, private, must-revalidate
```

##### 示例：
```
def show
  @post = Post.find params[:id]

  if stale? @post, :etag => @post.posted_at do
    respond_with @post
  end
end
```

```
def index
  @posts = Post.all

  if stale?(:last_modified => @posts.last.updated_at.utc)
    respond_to do |format|
      format.html # index.html.erb
      format.json { render json: @posts }
    end
  end
end
```

##### 特点：
* 如果没有修改，直接返回304，不需要返回网页内容
* 最有效的缓存方式
* 工作在协议层，更快
* 使用HTTP头(Last-Modified, ETag, If-Modified-Since, If-None-Match,
  Cache-Control)

### Tips
* 别碰swapper，除非非它不可。
* 为所有缓存使用自动过期的key。
* 经常把 `belongs to` 和 `:touch => true` 结合使用
* 使用 `Rails.cache` 来缓存查询到的数据
* 在每次部署应用后不要忘记设置ENV['RAILS_APP_VERSION']
* 一定要缓存你的assets静态文件。
* 缓存粒度一定要小，以此提高命中率

### Thanks to
<http://www.broadcastingadam.com/2012/07/advanced_caching_revised/>

<http://guides.rubyonrails.org/caching_with_rails.html>

<http://railslab.newrelic.com/scaling-rails>
