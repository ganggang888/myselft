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
			<li class="active"><a href="javascript:;">短信列表</a></li>
			<li ><a href="<?php echo U('User/giveMessage');?>">添加短信</a></li>
		</ul>

		<form class="well form-search">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						操作者： 
						<input type="text" name="name" id="name" style="width: 200px;" value="<?php echo ($data["name"]); ?>" placeholder="请输入操作者..."> --
                        内容： 
						<input type="text" name="content" id="content" style="width: 200px;" value="<?php echo ($data["content"]); ?>" placeholder="请输入内容...">--
						日期查询：
						<input type="text" name="begin" id="begin" class="J_date date" value="<?php echo ($data["begin"]); ?>" style="width: 80px;" autocomplete="off">
						-
						<input type="text" class="J_date date" name="end" id="end" value="<?php echo ($data["end"]); ?>" style="width: 80px;" autocomplete="off">
						<a href="javascript:;" onclick="todo()" class="btn btn-primary">搜索</a>&nbsp;&nbsp;
					</span>
					<script>
					function todo() {
						var name = $("#name").val();
						var content = $("#content").val();
						var begin = $("#begin").val();
						var end = $("#end").val();
						window.location.href='index.php?g=Admin&m=User&a=messageIndex&menuid=185&name='+name+'&name='+name+'&content='+content+'&begin='+begin+'&end='+end;
					}
					</script>
				</div>
			</div>
		</form>
		<form class="form-horizontal J_ajaxForm" action="" method="post">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th >ID</th>
						<th>操作者</th>
                        <th>备注</th>
                        <th>内容</th>
                        <th>状态</th>
                        <th>发布时间</th>
                        <?php if(get_current_admin_id() == 1): ?><th>操作</th><?php endif; ?>
					</tr>
				</thead>
				<?php if(is_array($result)): foreach($result as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["name"]); ?></td>
                    <td><?php echo ($vo["about"]); ?></td>
                    <td><?php echo ($vo["content"]); ?></td>
                    <td>
                    <?php if($vo['status'] == 0): ?>待处理
                    <?php elseif($vo['status'] == 1): ?>
                    已发送
                    <?php elseif($vo['status'] == 2): ?>
                    已被管理员取消，请联系管理员<?php endif; ?>
                    </td>
                    <td><?php echo ($vo["add_time"]); ?></td>
                    <?php if(get_current_admin_id() == 1): ?><td><a href="<?php echo U('User/messageStatus',array('id'=>$vo['id'],'status'=>1));?>" class="J_ajax_dialog_btn" data-msg="确定同意吗？">同意并进行群发</a>
                    |
                    <a href="<?php echo U('User/messageStatus',array('id'=>$vo['id'],'status'=>2));?>" class="J_ajax_dialog_btn" data-msg="确定取消吗？">取消</a>
                    </td><?php endif; ?>
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