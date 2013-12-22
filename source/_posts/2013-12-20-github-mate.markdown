---
layout: post
title: "GitHub Mate: 单文件下载更方便"
date: 2013-12-20 20:31:55 +0800
comments: true
categories: 
---

GitHub 不是 Dropbox，GitHub 侧重的是代码分享，而不是文件存储，比如曾经有的文档上传功能也被砍掉了 [Goodbye, Uploads](https://github.com/blog/1302-goodbye-uploads)。 而且 GitHub 还有意让文件下载复杂化，每次想下载一个文件都要右键Raw，然后目标另存为，请问还能做的再麻烦一点吗。
Git 本身不像 SVN 支持目录或文件克隆，所以对于体积大的项目，又是网速差的环境只能==了。

所以才写了 [GitHub Mate](https://chrome.google.com/webstore/detail/github-mate/baggcehellihkglakjnmnhpnjmkbmpkf) 这个 Chrome 插件，完全开源：[源码](https://github.com/camsong/chrome-github-mate)

目前源码逻辑很简单，有两个功能，一个是点击文件图标下载文件，实现逻辑在[这里](https://github.com/camsong/chrome-github-mate/blob/master/script.js#L3)。
主要有3步：

1. 在 document 上代理所有文件图标上的 click 事件，这样不用分别绑定事件，而且支持 GitHub 的 Pjax。
2. 根据点击的图标计算出文件的实际下载路径。
3. 添加一个匿名`a`标签，并使用HTML5的 `download` 属性来设置下载路径，模拟点击`a`标签开始下载。


另一个功能是显示未读通知（notification）数，通过添加一个定时器去抓取notification页面，方法很原始。其实对于我这种不太活跃的所谓“开源爱好者“没啥用处，基本不会有什么通知，以后会做成可选的，节省资源。

未来考虑支持文件夹下载，初步想法是找类似 Heroku, Nitrous.io 这种免费的服务来做后台自动clone仓库，并打包文件夹，然后回传地址，这样做实现起来不难，关键是对于大项目延迟会比较大，小项目下载文件夹速度提升又不大，还需要在考虑考虑。

不得不说，Chrome 插件体系设计实在太简洁了，而且对插件(extension)和应用(app)都有很好的支持和细分。
[官方文档](https://developer.chrome.com/extensions/getstarted.html) 示例都非常赞。有人说Chrome web store未来会替代Google Play和App Store，目前我只能“呵呵”，以后逆袭也不一定。





