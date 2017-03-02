<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="initial-scale=1.0,user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black"> 
<link rel="stylesheet" type="text/css" href="/info/tpl/simplebootx/Public/sign/css/css.css">
<title>学员签到</title>
<script type="text/javascript" src="/info/tpl/simplebootx/Public/sign/js/jquery.js"></script>
<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=318bc6f55f9becafd67add7a24314bbe"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
    <script type="text/javascript">
/***************************************
由于Chrome、IOS10等已不再支持非安全域的浏览器定位请求，为保证定位成功率和精度，请尽快升级您的站点到HTTPS。
***************************************/
    var map, geolocation;
    //加载地图，调用浏览器定位服务
    map = new AMap.Map('container', {
        resizeEnable: true
    });
    map.plugin('AMap.Geolocation', function() {
        geolocation = new AMap.Geolocation({
            enableHighAccuracy: true,//是否使用高精度定位，默认:true
            timeout: 10000,          //超过10秒后停止定位，默认：无穷大
            buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
            zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
            buttonPosition:'RB'
        });
        //map.addControl(geolocation);
        geolocation.getCurrentPosition();
        AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息
        AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息
    });
    //解析定位结果
    function onComplete(data) {
        var str=['定位成功'];
        str.push('经度：' + data.position.getLng());
        str.push('纬度：' + data.position.getLat());
        str.push('精度：' + data.accuracy + ' 米');
        str.push('是否经过偏移：' + (data.isConverted ? '是' : '否'));
      	//alert(data.position.getLng());
		window.longitude=data.position.getLng();
		window.latitude = data.position.getLat();
        //document.getElementById('tip').innerHTML = str.join('<br>');
    }
    //解析定位错误信息
    function onError(data) {
        document.getElementById('tip').innerHTML = '定位失败';
    }
</script>
<script>
$(function(){
	$("#dl").bind("click",function(){
		var number = $("#number").val();
		var info = {
			number:number,
			longitude:window.longitude,
			latitude:window.latitude,
			is_ajax:1
		}
		$.ajax({
            type:"POST",
            url:"index.php?g=Portal&m=Login&a=signInfo",
            data:info,
            success: function(data){
				if (data == 1) {
					alert("学号不存在，请重新输入");
					history.go(0);
				} else if (data == 2) {
					alert("您今天已经签到过了");
					window.location.href="index.php?g=Portal&m=Login&a=signInfo";
				} else if (data == 3) {
					alert("请重新扫码至正确位置签到");
					history.go(0);
				} else if (data == 5) {
					alert("签到成功");
					window.location.href="index.php?g=Portal&m=Login&a=signInfo";
				} else {
					alert("签到失败");
				}
            }
        })
		});
	});
</script>

<style>
body{
	background:#fccb00;
background-image:url(/info/tpl/simplebootx/Public/sign/images/82u58PICwcz_1024.jpg);
background-repeat:no-repeat;

margin: 0 auto;
    width: 100%;
    max-width: 640px;
    background-size: cover;
    height: 100%;
}
#qq{

}
</style>
</head>

<body>
<div id="qq" style="max-width:500px;">
<div style="min-height:220px; color:#952121; margin:50px auto 0px auto; text-align:center"><h1>麦忒签到</h1></div>

<br>
<div style="text-align:center; width:60%; margin:0 auto"><input style="width:100%; height:40px; border:solid 1px #CCC; font-size:16px; color:#333; text-align:center" id="number" placeholder="请输入工号..." type="text"></div>

<input type="hidden" id="longitude">
<input type="hidden" id="latitude">
<div style="text-align:center; width:60%; margin:30px auto 0px auto"><input id="dl" style="width:100%; height:40px; font-size:16px; border:0px; background-color:#0C0; color:#FFF" value="确定" type="button"></div>
<div style="min-height:208px"></div>
</div>
</body>
</html>
<?php exit; ?>