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
			<li><a href="<?php echo U('Wchat/index',array('type'=>$type));?>"><?php echo ($listnav[$type]['list']); ?></a></li>
			<li class="active"><a href="<?php echo U('Wchat/add',array('type'=>$type));?>"><?php echo ($listnav[$type]['edit']); ?></a></li>
		</ul>
		<div class="common-form">
			<form method="post" class="form-horizontal J_ajaxForm" action="">
				<fieldset>
                <div class="control-group">
						<label class="control-label">选择分类:</label>
						<div class="controls">
                         <select name="term_id">
                         <option value="0">请选择分类</option>
							<?php if(is_array($term)): foreach($term as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" <?php if($info['term_id'] == $vo['id']): ?>selected<?php endif; ?>><?php echo ($vo["term_name"]); ?></option><?php endforeach; endif; ?>
                            </select>
							<span class="must_red">*</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">文章标题:</label>
						<div class="controls">
							<input type="text" class="input" name="title" value="<?php echo ($info["title"]); ?>" style="width:50%">
							<span class="must_red">*</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">作者:</label>
						<div class="controls">
							<input type="text" class="input" name="author" value="<?php echo ($info["author"]); ?>">
							<span class="must_red">*</span>
						</div>
					</div>
                    <div class="control-group">
						<label class="control-label">摘要:</label>
						<div class="controls">
							<textarea name="excerpt" rows="5"><?php echo ($info["excerpt"]); ?></textarea>
							<span class="must_red">*</span>
						</div>
					</div>
                    <?php if($type == 0): ?><div class="control-group">
						<label class="control-label">微信链接:</label>
						<div class="controls">
							<textarea name="link" rows="5"><?php echo ($info["link"]); ?></textarea>
							<span class="must_red">*</span>
						</div>
					</div><?php endif; ?>
                    <div class="control-group">
						<label class="control-label">缩略图:</label>
						<div class="controls">
							<div  style="text-align: left;"><input type='hidden' name='img' id='thumb' value="<?php echo ((isset($info["img"]) && ($info["img"] !== ""))?($info["img"]):''); ?>">
      <a href='javascript:void(0);' onClick="flashupload('thumb_images', '附件上传','thumb',thumb_images,'1,jpg|jpeg|gif|png|bmp,1,,,1','','','');return false;">
      
      <?php if(empty($info['img'])): ?><img src="/info/statics/images/icon/upload-pic.png" id='thumb_preview' width='135' height='113' style='cursor:hand' />
      <?php else: ?>
        <img src="<?php echo sp_get_asset_upload_path($info['img']);?>" id='thumb_preview' width='135' height='113' style='cursor:hand' /><?php endif; ?>
      
      </a>
      <!-- <input type="button" class="btn" onclick="crop_cut_thumb($('#thumb').val());return false;" value="裁减图片">  -->
            <input type="button"  class="btn" onClick="$('#thumb_preview').attr('src','/info/statics/images/icon/upload-pic.png');$('#thumb').val('');return false;" value="取消图片">
            </div>
							<span class="must_red">*</span>
						</div>
					</div>
                    <?php if($type == 1): ?><div class="control-group">
						<label class="control-label">内容:</label>
						<div class="controls">
							<div id='content_tip'></div>
              <script type="text/plain" id="content" name="content"><?php echo htmlspecialchars_decode($info['content']);?></script>
                <script type="text/javascript">
                //编辑器路径定义
                var editorURL = GV.DIMAUB;
                </script>
                <script type="text/javascript"  src="/info/statics/js/ueditor/ueditor.config.js"></script>
                <script type="text/javascript"  src="/info/statics/js/ueditor/ueditor.all.min.js"></script>
						</div>
					</div><?php endif; ?>
                    <div class="control-group">
						<label class="control-label">添加时间:</label>
						<div class="controls">
							<input type="text" name="add_time" class="input J_datetime" value="<?php echo ($info["add_time"]); ?>">
							<span class="must_red">*</span>
						</div>
					</div>
                    <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
				</fieldset>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn_submit J_ajax_submit_btn">修改</button>
					<a class="btn" href="/info/index.php/User/Wchat">返回</a>
				</div>
			</form>
		</div>
	</div>
	<script src="/info/statics/js/common.js"></script>
    <script type="text/javascript" src="/info/statics/js/content_addtop.js"></script>
    <?php if($type == 1): ?><script type="text/javascript"> 
$(function(){
	editorcontent = new baidu.editor.ui.Editor();
	editorcontent.render( 'content' );
	
})
</script>
<style>
#edui1_iframeholder{ height:200px; width:720px!important}
#edui1{ width:680px!important;}
</style><?php endif; ?>
</body>
</html>