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
<link rel='stylesheet prefetch' href='http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css'>
<link rel="stylesheet" href="/info/tpl_admin/simpleboot/Public/css/css.css" media="screen" type="text/css" />

<link rel="stylesheet" type="text/css" href="/info/tpl_admin/simpleboot/Public/css/jquery.datetimepicker.css"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <ul class="nav nav-tabs">
    <li><a href="<?php echo U('Game/index');?>">游戏列表</a></li>
    <li class="active"><a href="javascript:;">添加推荐游戏</a></li>
  </ul>
  <div class="common-form">
    <form method="post" class="form-horizontal J_ajaxForm" action="">
      <fieldset style="margin-top:30px">
      
      
        <div class="control-group">
          <label class="control-label">对应分类:</label>
          <div class="controls">
          <select name="type" class="input">
            <option value="0">分类选择</option>
            <?php if(is_array($shareType)): foreach($shareType as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
            </select>
            <select id="month" name="month" class="input">
            <option value="0">月份选择</option>
            <?php if(is_array($month)): foreach($month as $key=>$vo): ?><option value="<?php echo ($vo); ?>">第<?php echo ($vo); ?>个月</option><?php endforeach; endif; ?>
            </select>
            <select name="level">
            <option value="0">级别选择</option>
            <?php if(is_array($level)): foreach($level as $key=>$vo): ?><option value="<?php echo ($vo); ?>">第<?php echo ($vo); ?>级</option><?php endforeach; endif; ?>
            </select>
            <span class="must_red">级别选择，三个分数区间，对应宝宝所得的分数由小到大</span> </div>
        </div>
        
        
        <div class="control-group">
          <label class="control-label">说明:</label>
          <div class="controls">
            <textarea rows="4" name="about"></textarea>
            <span class="must_red">*</span> </div>
        </div>
        <div class="control-group">
          <label class="control-label">筛选条件:</label>
          <div class="controls">
            
            <select id="months">
            <option value="0">月份选择</option>
            <?php for($i = 1;$i<=36;$i++) { ?>
            <option value="<?php echo ($i); ?>">第<?php echo ($i); ?>个月</option>
            <?php } ?>
            </select>
            -
            <select id="type">
            <?php if(is_array($storeTerm)): foreach($storeTerm as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
            </select>
            <input type="text" name="search" id="name" class="input" placeholder="请输入检索名称" style="height:auto">
            <span class="must_red"><a href="javascript:;" id="jian">检索</a></span> </div>
        </div>
        <div class="control-group">
          <div class="container">
            <div class="form-group"> 
              <!--<input class="form-control" type="text" name="name" placeholder="Title" />--> 
            </div>
            <!--<details>
              <div class="form-group">
                <input class="form-control" type="text" name="blah" placeholder="Additional details here" />
              </div>
            </details>-->
            <div class="row">
              <div class="col-sm-6">
                <div id="modules">
                </div>
              </div>
              <div class="col-sm-6">
                <div id="dropzone"></div>
                <!--<button class="btn btn-primary pull-right">Save</button>-->
              </div>
            </div>
          </div>
        </div>
        <!--<input type="hidden" name="post[id]" value="<?php echo ($info["id"]); ?>" />-->
      </fieldset>
      <div class="form-actions">
      <button type="submit" class="btn btn-primary btn_submit J_ajax_submit_btn">提交</button>
      </div>
    </form>
  </div>
</div> 

<script src="/info/statics/js/common.js"></script>
<script src='/info/tpl_admin/simpleboot/Public/js/jquery_and_jqueryui.js'></script>

<script src="/info/tpl_admin/simpleboot/Public/js/index.js"></script>

</body>
</html>

<style>
.nav-tabs>.active>a, .nav-tabs>.active>a:hover, .nav-tabs>.active>a:focus {
	color: #95a5a6;

	cursor: default;
	background-color: #fff;
	border: 1px solid #ddd;
	border-bottom-color: transparent;
}
.active {
	outline: 0px solid red;
}
#adds {
	height: 36px!important
}
</style>
<script>
$(function(){
  $("#jian").click(function(){
	var month = $("#months option:selected").val();
	var name = $("#name").val();
	var type = $("#type").val();
	$("#modules").load('<?php echo U("Game/checkTestStore");?>&month='+month+'&name='+name+'&type='+type);
  });
})
</script>