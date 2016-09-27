<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/info/statics/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/info/statics/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/info/statics/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/info/statics/simpleboot/font-awesome/4.2.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/info/statics/simpleboot/font-awesome/4.2.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/info/",
    JS_ROOT: "statics/js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/info/statics/js/jquery.js"></script>
    <script src="/info/statics/js/wind.js"></script>
    <script src="/info/statics/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
<body class="J_scroll_fixed">
  <div class="wrap J_check_wrap">
    <ul class="nav nav-tabs">
      <li class="active"><a href="javascript:;">注册人数</a></li>
    </ul>
<script type="text/javascript" src="http://cdn.hcharts.cn/jquery/jquery-1.8.3.min.js"></script>
  <script type="text/javascript" src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
  <script type="text/javascript" src="http://cdn.hcharts.cn/highcharts/exporting.js"></script>
    <form class="well form-search">
      <div class="search_type cc mb10">
        <div class="mb10">
          <span class="mr20">
            日期查询：
            <input type="text" name="start_time" id="start_time" class="J_date date" value="<?php echo ($begin); ?>" style="width: 125px;" autocomplete="off">
            -
            <input type="text" name="start_time" id="end_time" class="J_date date" value="<?php echo ($end); ?>" style="width: 125px;" autocomplete="off">
            <a href="javascript:;" onclick="todo()" class="btn btn-primary">搜索</a>&nbsp;&nbsp;
          </span>
          <script>
          function todo() {
            var phone = $("#phone").val();
            var start_time = $("#start_time").val();
            var end_time = $("#end_time").val();
            window.location.href='index.php?g=User&m=Teacher&a=numbers&menuid=187&begin='+start_time+'&end='+end_time;
          }
          </script>
        </div>
      </div>
    </form>
    <form class="form-horizontal J_ajaxForm" action="" method="post">
    <div id="container" style="min-width:700px;height:400px"></div>
    </form>
        
  </div>

<?php if ($_GET['begin'] && $_GET['end']) { foreach ($result as $vo) { $i = 0; $where = ''; foreach($vo['phone'] as $v) { $where .= substr($v['telephone'],0,7).","; } $while = substr($where,0,strlen($where)-1); $todayInfo = getAddressForMysql($while); foreach($todayInfo as $v) { if (strstr($v['mobilearea'],"四川")) { $i++; } } $isIp[] = $i; } } ?>



  <?php  ?>
  <script src="/info/statics/js/common.js"></script>
  <script>
    $(function() {

      $("#navcid_select").change(function() {
        $("#mainform").submit();
      });

    });
  </script>
   <?php $a = 0; ?>
  <?php if(is_array($result)): foreach($result as $key=>$vo): $a += $vo['num']; endforeach; endif; ?>
  <?php if($_SESSION['name'] != 'sichuan'): ?>总注册人数:<?php echo $a; ?><br/>
  每日平均注册数量：<?php echo $a / sizeof($result); endif; ?>
</body>
<script>
$(function () {
    $('#container').highcharts({
        title: {
            text: '注册人数统计',
            x: -20 //center
        },
        subtitle: {
            text: '注册人数统计',
            x: -20
        },
        xAxis: {
            categories: [<?php if(is_array($result)): foreach($result as $key=>$vo): ?>'<?php echo ($vo["day"]); ?>',<?php endforeach; endif; ?>
      ]
        },
        yAxis: {
            title: {
                text: '注册人数统计'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '人'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [<?php if($_SESSION['name'] != 'sichuan'): ?>{
            name: '注册人数',
            data: [<?php if(is_array($result)): foreach($result as $key=>$vo): echo ($vo["num"]); ?>,<?php endforeach; endif; ?>
      ]
        },<?php endif; ?>{
            name: '四川注册人数',
            data: [<?php if(is_array($isIp)): foreach($isIp as $key=>$value): echo ($value); ?>,<?php endforeach; endif; ?>]
        }]
    });
});
</script>
</html>