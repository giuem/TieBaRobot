<?php if (!defined('IS_GIUEM')) exit();?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>贴吧机器人助手</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="stylesheet" href="//cdn.css.net/libs/amazeui/1.0.0-beta2/css/amazeui.basic.min.css"/>
  <link rel="stylesheet" href="./templates/css/main.css"/>
  <script src="./templates/js/zepto.min.js"></script>
  <script src="//cdn.css.net/libs/amazeui/1.0.0-beta2/js/amazeui.min.js"></script>
</head>
<body>
<hr class="am-article-divider"/>
<div class="am-g">
  <div class="col-md-2 my-sidebar">
    <div class="am-offcanvas" id="sidebar" data-am-scrollspy="{animation:'slide-left',delay: 200, repeat: false}">
      <div class="am-offcanvas-bar">
        <ul class="am-nav">
          <li><a href="./">首页</a></li>
          <li><a href="./index.php?action=robot">管理</a></li>
          <li><a href="./index.php?action=setting">设置</a></li>
          <li><a href="./index.php?action=logout">退出</a></li>
        </ul>
      </div>
    </div>   
  </div>
  
  <div class="col-md-8">
    <div class="am-g">
      <div class="col-sm-11 col-sm-centered">