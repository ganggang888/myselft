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

	<div class="wrap jj">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('Beautiful/index');?>">学员列表</a></li>
			<li class="active"><a href="javascript:;">修改信息</a></li>
		</ul>
		<div class="common-form">
			<form method="post" class="form-horizontal J_ajaxForm" action="">
				<fieldset>
					<div class="control-group">
						<label class="control-label">分类:</label>
						<div class="controls">
                        <select name="term_id">
                        <?php if(is_array($term)): foreach($term as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $info['term_id']): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
							
							<span class="must_red">*</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">名称:</label>
						<div class="controls">
                        <input type="text" name="name" value="<?php echo ($info["name"]); ?>">
							<span class="must_red">*</span>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">工号:</label>
						<div class="controls">
                        <input type="text" value="<?php echo ($info["number"]); ?>" name="number">
							<span class="must_red">*</span>
						</div>
					</div>
                    <div class="control-group">
						<label class="control-label">手机号:</label>
						<div class="controls">
                        <input type="text" value="<?php echo ($info["phone"]); ?>" name="phone">
							<span class="must_red">*</span>
						</div>
					</div>
                    <div class="control-group">
						<label class="control-label">地址:</label>
						<div class="controls">
                        <textarea name="address" rows="3"><?php echo ($info["address"]); ?></textarea>
							<span class="must_red">*</span>
						</div>
					</div>
					<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
				</fieldset>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn_submit J_ajax_submit_btn">修改</button>
					<a class="btn" onClick="history.go(-1);">返回</a>
				</div>
			</form>
		</div>
	</div>
	<script src="/info/statics/js/common.js"></script>
</body>
</html>