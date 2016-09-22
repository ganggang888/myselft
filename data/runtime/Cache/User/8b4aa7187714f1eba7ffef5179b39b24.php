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
<script>
                function toremove()
				{
					alert(123456);
					alert($(this).parent().val());
					$(this).parent().html('dsadasdsadsa');
					$(this).parent().hide(500);
				}
                </script>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <ul class="nav nav-tabs">
    <li><a href="<?php echo U('Default/index');?>">默认成长册</a></li>
    <li class="active"><a href="javascript:;">修改成长册内容</a></li>
  </ul>
  <div class="common-form">
    <form method="post" name="myform" id="myform" class="form-horizontal J_ajaxForm" action="<?php echo U('Default/edit_post');?>">
      <fieldset style="margin-top:30px">
        <div class="control-group">
          <label class="control-label">宝宝年龄:</label>
          <div class="controls">
            <input type="text" value="<?php echo ($month); ?>" disabled="disabled" /><input type="hidden" name="month" value="<?php echo ($month); ?>" />
            <span class="must_red">*</span> </div>
        </div>
        <div class="control-group">
          <label class="control-label">天数:</label>
          <div class="controls">
            <input type="text" value="<?php echo ($day); ?>" disabled="disabled" /><input type="hidden" name="day" value="<?php echo ($day); ?>" />
            <span class="must_red">*</span> </div>
        </div>
        <div class="control-group">
          <label class="control-label">喂养方式:</label>
          <div class="controls">
            <input type="text" value="<?php echo getFeedTypeName($feed_type);?>" disabled="disabled" />
            <input type="hidden" name="feed_type" value="<?php echo ($feed_type); ?>" />
            <span class="must_red">*</span> </div>
        </div>
        <div class="control-group">
          <label class="control-label">适用订单:</label>
          <div class="controls">
            <input type="text" value="<?php echo ($order_money); ?>" disabled="disabled" />
            <input type="hidden" name="order_money" value="<?php echo ($order_money); ?>" />
            <span class="must_red">*</span> </div>
        </div>
        <div class="control-group">
          <label class="control-label">模糊查询:</label>
          <div class="controls">
            <input type="text" value="" id="search"/>
            <span class="must_red"><a href="javascript:;" id="sou">点击查询</a></span> </div>
        </div>
        <div class="control-group">
          <label class="control-label">筛选条件:</label>
          <?php $kus = array( array('id'=>1,'name'=>'行为库'), array('id'=>2,'name'=>'游戏库'), array('id'=>3,'name'=>'儿保库'), array('id'=>4,'name'=>'菜谱库'), ); ?>
          <div class="controls">
            <select id="ku">
            <option value="0">库存选择</option>
            <?php if(is_array($kus)): foreach($kus as $key=>$self): ?><option value="<?php echo ($self["id"]); ?>" <?php if($typ == $self['id']): ?>selected<?php endif; ?>><?php echo ($self["name"]); ?></option><?php endforeach; endif; ?>
            </select>
            -
            <select id="month">
            <option>月份选择</option>
            <?php for($i = 0;$i<=36;$i++) { ?>
            <option value="<?php echo ($i); ?>" <?php if($i == $mon): ?>selected<?php endif; ?>>第<?php echo ($i); ?>个月</option>
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
                <div id="dropzone">
                <?php if(is_array($result)): foreach($result as $key=>$vo): ?><div class="drop-item" id="<?php echo ($vo["id"]); ?>"><details><summary><?php echo ($vo["id"]); ?>&nbsp;&nbsp;<?php echo ($vo["title"]); ?>&nbsp;&nbsp;<?php echo ($vo["from_time"]); ?>至<?php echo ($vo["to_time"]); ?></summary><div><label>时间段：<input type="text" name="from_time[]" value="<?php echo ($vo["from_time"]); ?>">
                <input type="text" name="to_time[]" value="<?php echo ($vo["to_time"]); ?>"></label></div></details><button type="button" class="btn btn-default btn-xs remove" onClick="$(this).parent().remove()"><span class="glyphicon glyphicon-trash"></span></button>
                <input type="hidden" name="test_id[]" value="<?php echo ($vo["id"]); ?>"></input>
                
               
                </div><?php endforeach; endif; ?>
                <script src="/info/tpl_admin/simpleboot/Public/js/index.js"></script>
                </div>
                
              </div>
            </div>
          </div>
        </div>
        <!--<input type="hidden" name="post[id]" value="<?php echo ($info["id"]); ?>" />-->
      </fieldset>
      <div class="form-actions">
      <button class="btn btn-primary btn_submit J_ajax_submit_btn"type="submit">修改</button>
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
	$("#sou").click(function(){
		var searchs = $("#search").val();
		if (searchs == '') {
			alert("请输入内容");
			return false;
		}
		$("#modules").load('<?php echo U("book/find_month");?>&search='+searchs);
	})
})
</script>
<?php if($mon && $typ): ?><script>
$("#modules").load('<?php echo U("book/find_month");?>&month=<?php echo ($mon); ?>&type=<?php echo ($typ); ?>');
</script><?php endif; ?>
<script src="/info/statics/js/common.js"></script>