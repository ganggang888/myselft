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
			<li class="active"><a href="javascript:;">我的成长册</a></li>
			<li><a href="<?php echo U('book/add');?>">添加成长册</a></li>
		</ul>

		
		<form class="form-horizontal J_ajaxForm" action="" method="post">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th width="100">ID</th>
						<th>适用宝宝年龄</th>
                        <th>喂养方式</th>
                        <th>分类</th>
                        <th>适用订单</th>
                        <th>介绍</th>
						<th width="120">主要内容</th>
                        <th width="120">添加人</th>
                        <th width="120">状态</th>
                        <th width="120">操作</th>
					</tr>
				</thead>
				<?php if(is_array($rows)): foreach($rows as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["age"]); ?>个月<?php echo ($vo["day"]); ?>天</td>
                    <td><?php echo getFeedTypeName($vo[feed_type]);?></td>
                    <td><?php echo getHandBookName($vo[term]);?></td>
                    <td><?php echo ($vo["order_money"]); ?></td>
                    <td><?php echo ($vo["about"]); ?></td>
                    <td><a href="<?php echo U('book/info',array('id'=>$vo[id]));?>" target="_blank">查看</a></td>
                    <td><?php echo get_names($vo[aid]);?></td>
                    <td><?php echo returnStatus($vo[status]);?></td>
					<td>
                    <?php $aid = get_current_admin_id(); ?>
                    <?php if($aid == '1'): ?><a href="<?php echo U('book/open',array('id'=>$vo['id']));?>" class="J_ajax_dialog_btn" data-msg="您确定要启用吗？">启用</a>|<a href="<?php echo U('book/close',array('id'=>$vo['id']));?>" class="J_ajax_dialog_btn" data-msg="您确定要启用吗？">禁用</a>|
						<a href="<?php echo U('book/edit',array('id'=>$vo['id']));?>">修改</a>|
						<a href="<?php echo U('book/delete',array('id'=>$vo['id']));?>" class="J_ajax_del">删除</a>
                        <?php elseif($vo['aid'] == $aid): ?>
                        <a href="<?php echo U('book/edit',array('id'=>$vo['id']));?>">修改</a>|
						<a href="<?php echo U('book/delete',array('id'=>$vo['id']));?>" class="J_ajax_del">删除</a><?php endif; ?>
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