<?php if (!defined('THINK_PATH')) exit();?>
<?php if(is_array($result)): foreach($result as $key=>$vo): ?><p class="drag"><a class="btn btn-default"><?php echo ($vo["id"]); ?></a><input type="" value="<?php echo ($vo["name"]); ?>" id="title<?php echo ($vo["id"]); ?>" disabled style="color:#F00"><input type="hidden" value="<?php echo ($vo["add_time"]); ?>" id="time<?php echo ($vo["id"]); ?>" /></p>
<input type="hidden" class="one<?php echo ($vo["id"]); ?>" value="<?php echo ($vo["term_name"]); ?>" /><?php endforeach; endif; ?>

<p class="drag">
<script src="/info/tpl_admin/simpleboot/Public/js/index.js"></script>