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

		<form class="well form-search">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						日期查询：
						<input type="text" name="start_time" id="start_time" class="input length_3 J_datetime date" value="<?php echo ($start_time); ?>" style="width: 109px;" autocomplete="off">
						-
						<input type="text" class="input length_3 J_datetime date" name="end_time" id="end_time" value="<?php echo ($end_time); ?>" style="width: 109px;" autocomplete="off">
						<a href="javascript:;" onclick="todo()" class="btn btn-primary">搜索</a>&nbsp;&nbsp;
					</span>
					<script>
					function todo() {
						var phone = $("#phone").val();
						var start_time = $("#start_time").val();
						var end_time = $("#end_time").val();
						window.location.href='index.php?g=User&m=Teacher&a=number&menuid=187&start_time='+start_time+'&end_time='+end_time;
					}
					</script>
				</div>
			</div>
		</form>
		<form class="form-horizontal J_ajaxForm" action="" method="post">

		<?php if(!empty($_GET['start_time']) || !empty($_GET['end_time'])): ?><h3>共点击了<span  style="color:#F00"><?php echo ($hits); ?></span>次</h3>
			<h3>共有<span style="color:#F00"><?php echo ($all); ?></span>人注册</h3><?php endif; ?>
		</form>
        <div class="pagination"><?php echo ($page); ?></div>
	</div>
	<script src="/info/statics/js/common.js"></script>
	<script>
		$(function() {

			$("#navcid_select").change(function() {
				$("#mainform").submit();
			});

		});
	</script>
</body>
</html>