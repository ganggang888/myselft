<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width"/>
<meta name="viewport" content="initial-scale=1.0,user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black"> 
<link rel="stylesheet" type="text/css" href="/info/tpl/simplebootx/Public/sign/css/list.css"/>
<title>签到</title>
<script type="text/javascript" src="/info/tpl/simplebootx/Public/sign/js/jquery.js"></script>
<script>
$(function(){
	var as=document.body.clientHeight;
	document.getElementById("haha").style.height=as;
	$("#qd").bind("click",function(){
		alert("签到成功"+as);
		});
	});
	window.onload=function modify(){ 
	var s=document.body.clientHeight;
	
	}
</script>
<style>
body{
	overflow-x:hidden;
	overflow-y:hidden;
	background-image:url(/info/tpl/simplebootx/Public/sign/images/info.jpg);
	background-size:100% 100%;
	background-repeat:no-repeat;}
</style>
</head>

<body>
	<div id="haha" style="width:100%;height:100%; min-height:380px">
		<div class="header">
			<p>
				<a onclick="history.go(-1)">
					<span class="left">返回</span>
				</a>
                <?php if($_SESSION['number']): ?><span><?php echo ($_SESSION['number']); ?></span>
                <?php else: ?>
                <span>签到</span><?php endif; ?>
				
				<a href="<?php echo U('Portal/Login/sign_history');?>">
					<span class="right">记录</span>
				</a>
			</p>
		</div>
		<div style="text-align:center" class="text">
		<div style="height:150px">
    		
    	</div>
    	<div><input style="width:70%; max-width:400px; height:40px; background-color:#4D99C8; border:0px; color:#FFF; font-size:24px" id="qd" type="button" <?php if($_SESSION['number']): ?>value="已签到" <?php else: ?>value="已签到"<?php endif; ?> <?php if($_SESSION['number']): ?>disabled="disabled"<?php endif; ?>/>
        </div>
	</div>
	<div id="haa" style="min-height:270px"></div>
	</div>
</body>
</html>

<?php exit; ?>