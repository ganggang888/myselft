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
<style type="text/css">
.col-auto { overflow: auto; _zoom: 1;_float: left;}
.col-right { float: right; width: 210px; overflow: hidden; margin-left: 6px; }
.table th, .table td {vertical-align: middle;}
.picList li{margin-bottom: 5px;}
</style>
</head>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <ul class="nav nav-tabs">
     <li><a href="<?php echo U('Default/store');?>">所有库</a></li>
     <li class="active"><a href="javascript:;"  target="_self">修改库</a></li>
  </ul>
  <form name="myform" id="myform" action="<?php echo u('Default/edit_store_post');?>" method="post" class="form-horizontal J_ajaxForms" enctype="multipart/form-data">
  
  <div class="col-auto">
    <div class="table_full">
      <table class="table table-bordered">
            
            <tr>
              <th width="80">标题</th>
              <td><input type='text' name='title' id='keywords' value='<?php echo ($info["title"]); ?>' style='width:400px'   class='input' placeholder='请输入关键字'> </td>
            </tr>
            <tr>
              <th width="80">适用月龄</th>
              <td><input type='text' name='month' id='source' value='<?php echo ($info["month"]); ?>' style='width:400px'   class='input' placeholder='请输入适用月龄'></td>
            </tr>
            <?php if($info['type'] == 4): ?><tr>
              <th width="80">食材准备</th>
              <td><textarea name='foodstuff' id='description'   style='width:98%;height:50px;' placeholder='请填写食材'><?php echo ($info["foodstuff"]); ?></textarea><span class="must_red">*</span></td>
            </tr>
            <tr>
              <th width="80">用具准备</th>
              <td><textarea name='tool' id='description'   style='width:98%;height:50px;' placeholder='请填写用具准备'><?php echo ($info["tool"]); ?></textarea><span class="must_red">*</span></td>
            </tr>
            <tr>
              <th width="80">个人准备</th>
              <td><textarea name='personal' id='description'   style='width:98%;height:50px;' placeholder='请填写个人准备'><?php echo ($info["personal"]); ?></textarea><span class="must_red">*</span></td>
            </tr>
            <tr>
              <th width="80">注意事项</th>
              <td><textarea name='notes' id='description' style='width:98%;height:50px;' placeholder='请填写注意事项'><?php echo ($info["notes"]); ?></textarea><span class="must_red">*</span></td>
            </tr><?php endif; ?>
            <tr>
              <th width="80">上传多媒体</th>
              <td><input type="file" name="uploadmedia" id="uploadmedia"><span class="must_red">已上传多媒体:<?php echo ($info["media"]); ?></span></td>
            </tr>
            <tr>
              <th width="80">内容</th>
              <td><div id='content_tip'></div>
              <script type="text/plain" id="content" name="content"><?php echo ($info["content"]); ?></script>
                <script type="text/javascript">
                //编辑器路径定义
                var editorURL = GV.DIMAUB;
                </script>
                <script type="text/javascript"  src="/info/statics/js/ueditor/ueditor.config.js"></script>
                <script type="text/javascript"  src="/info/statics/js/ueditor/ueditor.all.min.js"></script>
				</td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>
  <input type="hidden" name="type" value="<?php echo ($info["type"]); ?>" />
  <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
  <div class="form-actions">
        <button class="btn btn-primary btn_submit J_ajax_submit_btn"type="submit">修改</button>
        <a class="btn" href="javascript:;" onclick="history.go(-1)">返回</a>
  </div>
 </form>
</div>
<script type="text/javascript" src="/info/statics/js/common.js"></script>
<script type="text/javascript" src="/info/statics/js/content_addtop.js"></script>
<script type="text/javascript"> 
$(function(){
	editorcontent = new baidu.editor.ui.Editor();
	editorcontent.render( 'content' );
})
</script>
</body>
</html>