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
			<li class="active"><a href="javascript:;">库管理</a></li>
            <li><a href="<?php echo U('Default/add_store');?>">添加内容</a></li>
		</ul>

		<form class="well form-search">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						库：
                    <select name="stotr_type" id="stotr_type">
                        <?php if(is_array($stotr_type)): foreach($stotr_type as $key=>$v): ?><option value="0">全部</option>
                        <option value="<?php echo ($v["id"]); ?>" <?php if($v['id'] == $type): ?>selected="selected"<?php endif; ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
                        </select>  --
						月份：
                        <select name="month" id="month">
                        <option value="0">选择月份</option>
                        <?php
 for($i=1;$i<=36;$i++) { ?>
                        <option value="<?php echo ($i); ?>" <?php if($i == $month): ?>selected="selected"<?php endif; ?>><?php echo ($i); ?>月</option>
                        <?php
 } ?>
                        </select>
                          --
                          名称检索：
                          <input type="text" id="title" value="<?php echo ($title); ?>" />
						<a href="javascript:;" onClick="todo()" class="btn btn-primary">搜索</a>
					</span>
				  <script>
					function todo() {
						var stotr_type = $("#stotr_type").val();
						var month = $("#month").val();
						var title = $("#title").val();
						window.location.href='index.php?g=User&m=Default&a=store&menuid=185&type='+stotr_type+'&month='+month+"&title="+title;
					}
					</script>
				</div>
			</div>
		</form>
        <?php $ku = array('1'=>'行为库','2'=>'游戏库','3'=>'儿宝库','4'=>'菜谱库'); ?>
		<form class="form-horizontal J_ajaxForm" action="" method="post">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th width="100">ID</th>
						<th>名称</th>
                        <th>库名称</th>
                        <th>适用月龄</th>
                        <th>上传视频名称</th>
                        <th>创建时间</th>
                        <th width="120">操作</th>
					</tr>
				</thead>
				<?php if(is_array($result)): foreach($result as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["title"]); ?></td>
                    <th><?php echo ($ku[$vo['type']]); ?></th>
                    <td><?php echo ($vo["month"]); ?></td>
                    <td><?php echo ($vo["media"]); ?></td>
                    <td><?php echo ($vo["createdate"]); ?></td>
                    <td>
                    <a href="<?php echo U('User/Default/edit_store',array('id'=>$vo['id']));?>">修改</a> | <a href="<?php echo U('User/Default/store_delete',array('id'=>$vo['id']));?>" class="J_ajax_del">删除</a>
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