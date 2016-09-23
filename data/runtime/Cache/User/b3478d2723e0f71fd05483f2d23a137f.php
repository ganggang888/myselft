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
    <li><a href="<?php echo U('book/index');?>">我的成长册</a></li>
    <li class="active"><a href="javascript:;">添加成长册</a></li>
  </ul>
  <div class="common-form">
    <form method="post" name="myform" id="myform" class="form-horizontal J_ajaxForm" action="<?php echo U('book/add_post');?>">
      <fieldset style="margin-top:30px">
        <div class="control-group">
          <label class="control-label">宝宝年龄:</label>
          <div class="controls">
            <select name="age">
              <?php $num = 36; ?>
              <?php for($i = 1;$i<=36;$i++) { ?>
              <option value="<?php echo ($i); ?>">第<?php echo ($i); ?>个月</option>
              <?php } ?>
            </select>
            <span class="must_red">*</span> </div>
        </div>
        <div class="control-group">
          <label class="control-label">天数:</label>
          <div class="controls">
            <select name="day" id="day">
            <?php for($i = 1;$i<=30;$i++) { ?>
            <option value="<?php echo ($i); ?>">第<?php echo ($i); ?>天</option>
            <?php } ?>
            </select>
            <span class="must_red">*</span> </div>
        </div>
        <div class="control-group">
          <label class="control-label">喂养方式:</label>
          <div class="controls">
            <select name="feedType" id="feedType">
              <option value="0">母乳喂养</option>
              <option value="1">混合喂养</option>
              <option value="2">人工喂养</option>
            </select>
            <span class="must_red">*</span> </div>
        </div>
        <div class="control-group">
          <label class="control-label">适用订单:</label>
          <div class="controls">
            <select name="orderMoney" id="orderMoney">
              <option value="8866" >8866</option>
              <option value="12888" >12888</option>
              <option value="15888" >15888</option>
            </select>
            <span class="must_red">*</span> </div>
        </div>
        <div class="control-group">
          <label class="control-label">成长册分类:</label>
          <div class="controls">
            <select name="term" id="orderMoney">
            <?php if(is_array($term)): foreach($term as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
            </select>
            <span class="must_red">*</span> </div>
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
            <select id="ku">
            <option value="0">库存选择</option>
            <option value="1">行为库</option>
            <option value="2">游戏库</option>
            <option value="3">儿保库</option>
            <option value="4">菜谱库</option>
            </select>
            -
            <select id="month">
            <option>月份选择</option>
            <?php for($i = 1;$i<=36;$i++) { ?>
            <option value="<?php echo ($i); ?>">第<?php echo ($i); ?>个月</option>
            <?php } ?>
            </select>
            
            <span class="must_red">*</span> </div>
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
      <button class="btn btn-primary btn_submit J_ajax_submit_btn"type="submit">提交</button>
      </div>
    </form>
  </div>
</div> 
</body>
</html><script src='http://codepen.io/assets/libs/fullpage/jquery_and_jqueryui.js'></script>
<script src="/info/tpl_admin/simpleboot/Public/js/index.js"></script>
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
	$("#month").change(function(){
		if ($("#ku").val() == 0) {
			alert('请选择库');
			return false;
		}
		var type = $("#ku").val();
		var month = $("#month").val();
		$("#modules").load('<?php echo U("book/find_month");?>&month='+month+'&type='+type);		
	});
})
</script>

<script src="/info/statics/js/common.js"></script>