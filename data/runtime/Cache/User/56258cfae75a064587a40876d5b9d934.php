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
			<li class="active"><a href="javascript:;">游戏列表</a></li>
    <li><a href="<?php echo U('Game/add');?>">添加推荐游戏</a></li>
		</ul>

		<form class="well form-search">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">月龄：<input type="text" id="month" value="<?php echo ($month); ?>" />
						<a href="javascript:;" onClick="todo()" class="btn btn-primary">搜索</a>
					</span>
				  <script>
					function todo() {
						var month = $("#month").val();
						window.location.href='index.php?g=User&m=Game&a=index&menuid=185&month='+month;
					}
					</script>
				</div>
			</div>
		</form>
		<form class="form-horizontal J_ajaxForm" action="" method="post">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th width="100">ID</th>
						<th>适用月龄</th>
                        <th>说明</th>
                        <th>级别</th>
                        <th>条目</th>
                      <th>创建时间</th>
                        <th width="120">操作</th>
					</tr>
				</thead>
				<?php if(is_array($result)): foreach($result as $key=>$vo): ?><tr valign="middle" >
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["month"]); ?></td>
                    <th><?php echo ($vo["about"]); ?></th>
                    <td><?php echo ($vo["level"]); ?></td>
                    <th>
                    <?php if(is_array($vo[nr])): foreach($vo[nr] as $key=>$v): ?><li><?php echo ($v["title"]); ?></li><?php endforeach; endif; ?>
                  </th>
                  <td><?php echo ($vo["add_time"]); ?></td>
                    <td><a href="<?php echo U('Game/edit',array('id'=>$vo['id']));?>">修改</a> | <a href="<?php echo U('Game/delete',array('id'=>$vo['id']));?>" class="J_ajax_del">删除</a>
					</td>
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