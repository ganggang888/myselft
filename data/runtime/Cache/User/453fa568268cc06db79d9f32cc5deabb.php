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
			<li class="active"><a href="<?php echo U('Wchat/index');?>"><?php echo ($listnav[$type]['list']); ?></a></li>
			<li><a href="<?php echo U('Wchat/add',array('type'=>$type));?>"><?php echo ($listnav[$type]['add']); ?></a></li>
		</ul>
		<form class="well form-search">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">文章标题：</span><input type="text" id="title" value="<?php echo ($title); ?>" />
					作者名称：<input type="text" id="author" value="<?php echo ($author); ?>"  style="width:120px"/>
                    分类名称：
                    <select name="term_id" id="term_id">
                    <option value="0">请选择分类</option>
                    <?php if(is_array($term)): foreach($term as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" <?php if($term_id == $vo['id']): ?>selected<?php endif; ?>><?php echo ($vo["term_name"]); ?></option><?php endforeach; endif; ?>
                    </select>
                    添加时间:<input type="text" value="<?php echo ($begin); ?>" class="J_date J_date" id="begin" style="width:120px">
                    -
                    <input type="text" value="<?php echo ($end); ?>" class="J_date J_date" id="end" style="width:120px">
                    <a href="javascript:;" onClick="todo()" class="btn btn-primary">搜索</a>
				  <script>
					function todo() {
						var title = $("#title").val();
						var author = $("#author").val();
						var term_id = $("#term_id").val();
						var begin = $("#begin").val();
						var end = $("#end").val();
						window.location.href='index.php?g=User&m=Wchat&a=index&menuid=185&title='+title+'&author='+author+'&begin='+begin+'&end='+end+'&term_id='+term_id;
					}
					</script>
				</div>
			</div>
		</form>
        
		<form class="form-horizontal J_ajaxForm" action="" method="post">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
                    <th>排序</th>
						<th>ID</th>
						<th>标题</th>
                        <th>分类名称</th>
                        <th>作者</th>
                        <th width="15%">摘要</th>
                        <?php if($type == 0): ?><th width="100px">链接地址</th><?php endif; ?>
                        <th>缩略图</th>
                        <th>添加时间</th>
                        <th>操作</th>
					</tr>
				</thead>
				<?php if(is_array($result)): foreach($result as $key=>$vo): ?><tr valign="middle" >
                <td><input type="text" name="info[<?php echo ($vo["id"]); ?>]" value="<?php echo ($vo["listorder"]); ?>" style="width:20px"></td>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["title"]); ?></td>
                    <th><?php echo ($vo["term_name"]); ?></th>
                    <th><?php echo ($vo["author"]); ?></th>
                    <th><?php echo ($vo["excerpt"]); ?></th>
                    <?php if($type == 0): ?><th><a href="<?php echo ($vo["link"]); ?>" target="_blank">点击查看</a></th><?php endif; ?>
                    <th><img src="<?php echo ($vo["img"]); ?>" width="130px"></th>
                    <th><?php echo ($vo["add_time"]); ?></th>
                    <td><a href="<?php echo U('Wchat/edit',array('id'=>$vo['id'],'type'=>$type));?>">修改</a> | <a href="<?php echo U('Wchat/delete',array('id'=>$vo['id'],array('type'=>$type)));?>" class="J_ajax_del">删除</a></td>
				</tr><?php endforeach; endif; ?>
			</table>
            <div class="table-actions">
            <button class="btn btn-primary btn-small J_ajax_submit_btn" type="submit" data-action="<?php echo U('Wchat/listorders');?>">排序</button>
            <input type="hidden" value="/info/index.php?g=User&m=Wchat&a=index&type=1" name="nowUrl">
            </div>
		</form>
        <div class="pagination"><?php echo ($page->show('Admin')); ?></div>
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