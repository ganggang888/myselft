<?php if (!defined('THINK_PATH')) exit();?><script src="/info/tpl_admin/simpleboot/Public/js/jquery.datetimepicker.js"></script>
<?php if(is_array($rows)): foreach($rows as $key=>$vo): ?><script>
$('#datetimepicker<?php echo ($vo["id"]); ?>').datetimepicker({
	datepicker:false,
	format:'H:i',
	step:5
});
</script>
<script>
$('#two<?php echo ($vo["id"]); ?>').datetimepicker({
	datepicker:false,
	format:'H:i',
	step:5
});
</script>
<p class="drag"><a class="btn btn-default"><?php echo ($vo["id"]); ?></a><input type="" value="<?php echo ($vo["title"]); ?>" id="title<?php echo ($vo["id"]); ?>" disabled style="color:#F00"><input type="text" id="datetimepicker<?php echo ($vo["id"]); ?>" class="one<?php echo ($vo["id"]); ?>" value="" style="width:77px; "placeholder='开始时间'/><input type="text" id="two<?php echo ($vo["id"]); ?>" class="two<?php echo ($vo["id"]); ?>" style="width:77px;" placeholder='结束时间'/><input type="hidden" value="<?php echo ($vo["createdate"]); ?>" id="time<?php echo ($vo["id"]); ?>" /></p><?php endforeach; endif; ?>

<p class="drag">
<script src="/info/tpl_admin/simpleboot/Public/js/index.js"></script>