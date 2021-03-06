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
			<li class="active"><a href="javascript:;">签到记录</a></li>
		</ul>

		<form class="well form-search">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						姓名： 
						<input type="text" name="name" id="name" style="width: 200px;" value="<?php echo ($name); ?>" placeholder="请输入姓名..."> --
						手机号：
						<input type="text" name="phone" id="phone" style="width: 200px;" value="<?php echo ($phone); ?>" placeholder="请输入手机号...">
						 --
                        分类：
						<select name="term_id" id="term_id">
                        <option value="0">请选择</option>
                        <?php if(is_array($term)): foreach($term as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>"<?php if($vo['id'] == $term_id): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                        日期查询：
						<input type="text" name="start_time" id="start_time" class="J_date date" value="<?php echo ($begin); ?>" style="width: 80px;" autocomplete="off">
						-
						<input type="text" class="J_date date" name="end_time" id="end_time" value="<?php echo ($end); ?>" style="width: 80px;" autocomplete="off">
						<a href="javascript:;" onClick="todo()" class="btn btn-primary">搜索</a>&nbsp;&nbsp;
					</span>
					<script>
					function todo() {
						var name = $("#name").val();
						var phone = $("#phone").val();
						var term_id = $("#term_id").val();
						var begin = $("#start_time").val();
						var end = $("#end_time").val();
						window.location.href='index.php?g=User&m=Beautiful&a=sign&menuid=185&name='+name+'&phone='+phone+'&term_id='+term_id+"&begin="+begin+"&end="+end;
					}
					</script>
				</div>
			</div>
		</form>
		<form class="form-horizontal J_ajaxForm" action="" method="post">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th>姓名</th>
                        <th>所属分类</th>
						<th>工号</th>
                        <th>手机号</th>
                        <th>地址</th>
                        <th>签到时间</th>
					</tr>
				</thead>
				<?php if(is_array($result)): foreach($result as $key=>$vo): ?><tr>
					<td><?php echo ($vo["name"]); ?></td>
                    <td><?php echo ($vo["term_name"]); ?></td>
                    <td><?php echo ($vo["number"]); ?></td>
					<td>
					<?php echo ($vo["phone"]); ?>
					</td>
                    <td><?php echo ($vo["address"]); ?></td>
                    <td><?php echo ($vo["add_time"]); ?></td>
				</tr><?php endforeach; endif; ?>
			</table>
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