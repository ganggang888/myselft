<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
   <title></title>
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
   <meta name="viewport" content="width=device-width, user-scalable=no">
   <meta content="yes" name="apple-mobile-web-app-capable">
   <meta content="telephone=no" name="format-detection">
   <meta content="email=no" name="format-detection">
   <link href="/info/tpl/simplebootx/Public/css/conBase.css" rel="stylesheet">
   <style type="text/css">
   *{margin: 0;padding: 0;}
   html,body{width: 100%;height: 100%;}
     .contain{background: url(/info/tpl/simplebootx/Public/img/smog_bg.jpg) top center;background-size: cover;width: 100%;height: 95%;color: #fff;}
     .tq_box{padding: 0.57rem 0.31rem;position: relative;}
     .tq_box i{font-size: 0.48rem;font-style: normal;display: block;}
     .tq_box p{font-size: 0.2rem;line-height: 0.36rem;}
     .tq_box small{font-size: 0.12rem;display: block;}
     .rain_t{width: 1.1rem;position: absolute;left: ;top: 0.76rem;right: 0.375rem;}
     .tq_info{width: 2.9rem;font-size: 0.14rem;margin: 0 auto;background: rgba(0,0,0,0.2);border-radius: 0.125rem;}
     .tq_info p{line-height: 0.475rem;border-bottom: 1px solid #dad7d7;}
     .tq_iinner{padding: 0 0.17rem;}
     .tq_info p:last-child{border: none;}
   </style>
</head>
<body>
 <div id="wrap">
<header class="color1">
    <img src="/info/tpl/simplebootx/Public/img/home.png">
     <a href="">临港生活</a> <i> &gt; </i> <span>临港天气</span>
  </header>
 <div class="contain">
   <div class="tq_box clearfix">
     <i><?php echo ($weathers['temp24']); ?></i>
     <p><span>上海</span>&nbsp;&nbsp;<span><?php echo ($weathers['weather24']); ?></span></p>
     <small><?php echo date("Y-m-d");?></small>
     <img src="/info/tpl/simplebootx/Public/img/smog_y.png" class="rain_t">
   </div>
   <div class="tq_info">
   <div class="tq_iinner">
     <p class="clearfix"><span class="fl">风力</span><span class="fr"><?php echo ($weathers["wind24"]); ?></span></p>
     <p class="clearfix"><span class="fl">空气质量</span><span class="fr"><?php echo weatherinfo($arr['pmAuto']);?></span></p>
     <p class="clearfix"><span class="fl">PM2.5</span><span class="fr"><?php echo ($arr['pm']); ?> | 平均值<?php echo ($arr['pmAuto']); ?></span></p>
   </div>
   </div>
 </div>
 	<footer>
      <nav class="foot_nav clearfix">
         <a href="#" class="fa">用户互动</a>
      <div class="menu menu1">
        <a href="#">用户大厅</a>
        <a href="#">用户大厅</a>
        <a href="#">用户大厅</a>
        <a href="#" class="last">用户大厅</a>
      </div>
         <a href="#" class="fa">服务指南</a>
      <div class="menu menu2">
        <a href="#">用户大厅</a>
        <a href="#">用户大厅</a>
        <a href="#">用户大厅</a>
        <a href="#" class="last">用户大厅</a>
      </div>
         <a href="#" class="last fa">用户大厅</a>
      <div class="menu menu3">
        <a href="#">用户大厅</a>
        <a href="#">用户大厅</a>
        <a href="#">用户大厅</a>
        <a href="#" class="last">用户大厅</a>
      </div>
      </nav>
   </footer>
 </div>
    <script type="text/javascript" src="/info/tpl/simplebootx/Public/js/jquery-1.9.0.min.js"></script>
 <script type="text/javascript" src="/info/tpl/simplebootx/Public/js/rem.js"></script>
</body>
</html>