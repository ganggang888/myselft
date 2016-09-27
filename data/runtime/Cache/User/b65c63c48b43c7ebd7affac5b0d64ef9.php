<?php if (!defined('THINK_PATH')) exit(); if(is_array($result)): foreach($result as $key=>$vo): ?><div class="drop-item" id="<?php echo ($vo["id"]); ?>"><details><summary><?php echo ($vo["id"]); ?>&nbsp;&nbsp;<?php echo ($vo["name"]); ?>&nbsp;&nbsp;</summary><div><label>时间段：
</label></div></details><button type="button" class="btn btn-default btn-xs remove" onClick="$(this).parent().remove()"><span class="glyphicon glyphicon-trash"></span></button>
<input type="hidden" name="test_id[]" value="<?php echo ($vo["id"]); ?>"></input>
</div><?php endforeach; endif; ?>