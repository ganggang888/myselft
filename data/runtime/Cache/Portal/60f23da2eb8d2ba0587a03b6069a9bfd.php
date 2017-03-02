<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width"/>
<meta name="viewport" content="initial-scale=1.0,user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="stylesheet" type="text/css" href="/info/tpl/simplebootx/Public/sign/css/list.css">
<link rel="stylesheet" type="text/css" href="/info/tpl/simplebootx/Public/sign/css/zepto.mdatetimer.css">
<title>记录</title>
<script type="text/javascript" src="/info/tpl/simplebootx/Public/sign/js/zepto.js"></script>
<script type="text/javascript" src="/info/tpl/simplebootx/Public/sign/js/zepto.mdatetimer.js"></script>
<script>
$(function(){
	$('.picktime').mdatetimer({
		mode : 1, //时间选择器模式：1：年月日，2：年月日时分（24小时），3：年月日时分（12小时），4：年月日时分秒。默认：1
		format : 2, //时间格式化方式：1：2015年06月10日 17时30分46秒，2：2015-05-10  17:30:46。默认：2
		years : [2000, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017], //年份数组
		nowbtn : true, //是否显示现在按钮
		onOk : function(){
			//alert('OK');
		},  //点击确定时添加额外的执行函数 默认null
		onCancel : function(){
			alert('www.sucaijiayuan.com');
		}, //点击取消时添加额外的执行函数 默认null
	});	
	$('.haha').mdatetimer({
		mode : 1, //时间选择器模式：1：年月日，2：年月日时分（24小时），3：年月日时分（12小时），4：年月日时分秒。默认：1
		format : 2, //时间格式化方式：1：2015年06月10日 17时30分46秒，2：2015-05-10  17:30:46。默认：2
		years : [2000, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017], //年份数组
		nowbtn : true, //是否显示现在按钮
		onOk : function(){
			//alert('OK');
		},  //点击确定时添加额外的执行函数 默认null
		onCancel : function(){
			alert('www.sucaijiayuan.com');
		}, //点击取消时添加额外的执行函数 默认null
	});	
});

</script>

<style>
td{border:solid #add9c0; border-width:0px 1px 1px 0px; padding:10px 0px;}
table{ width:100%;border:solid #add9c0; border-width:1px 0px 0px 1px;}
</style>
</head>

<body>

<div class="header"><p><a href="javascript:;" onClick="history.go(-1)"><span class="left">返回</span></a><span>签到记录</span></p></div>
<!--<div style="text-align:center; height:35px; margin-top:10px;"><input style="height:30px; width:100px; text-align:center" value="<?php echo date("Y-m-d");?>" class="picktime">
至
<input style="height:30px; width:100px; text-align:center" value="<?php echo date("Y-m-d");?>" class="haha" >&nbsp;&nbsp;&nbsp;<input id="cx" style="height:30px; width:50px" type="button" value="查询"></div>-->
<div style=" margin-left:10px; margin-right:10px;text-align:center" class="list"> 
	<table align="center">
    <?php if(is_array($result)): foreach($result as $key=>$vo): ?><tr>
        	<td align="center"><?php echo ($vo["add_time"]); ?></td>
            <td align="center">
            已签到
            </td>
            <td align="center"><?php echo ($vo["name"]); ?></td>
            <td align="center"><?php echo ($vo["term_name"]); ?></td>
        </tr><?php endforeach; endif; ?>
    
    </table>
</div>
</body>
</html>