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
    <li><a href="<?php echo U('Assess/index');?>">评估列表</a></li>
    <li class="active"><a href="javascript:;">修改</a></li>
  </ul>
  <div class="common-form">
    <form method="post" class="form-horizontal J_ajaxForm" action="">
      <fieldset style="margin-top:30px">
      
      
        <div class="control-group">
          <label class="control-label">对应分类:</label>
          <div class="controls">
           <select name="term_id">
           <?php if(is_array($term)): foreach($term as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo[id] == $info[term_id]): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
           </select>
            <select id="month" name="month" class="input">
            <option value="0">月份选择</option>
            <?php if(is_array($month)): foreach($month as $key=>$vo): ?><option value="<?php echo ($vo); ?>" <?php if($info[month] == $vo): ?>selected<?php endif; ?>>第<?php echo ($vo); ?>个月</option><?php endforeach; endif; ?>
            </select>
            <select name="level">
            <option value="0">级别选择</option>
            <?php if(is_array($level)): foreach($level as $key=>$vo): ?><option value="<?php echo ($vo); ?>" <?php if($vo == $info[level]): ?>selected<?php endif; ?>>第<?php echo ($vo); ?>级</option><?php endforeach; endif; ?>
            </select>
            <span class="must_red">级别选择，三个分数区间，对应宝宝所得的分数由小到大</span> </div>
        </div>
        
        
        <div class="control-group">
          <label class="control-label">解读:</label>
          <div class="controls">
            <textarea rows="4" name="sad"><?php echo ($info["sad"]); ?></textarea>
            <span class="must_red">*</span> </div>
        </div>
        
        
        <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
      </fieldset>
      <div class="form-actions">
      <button type="submit" class="btn btn-primary btn_submit J_ajax_submit_btn">提交</button>
      </div>
    </form>
  </div>
</div> 

<script src="/info/statics/js/common.js"></script>

</body>
</html>