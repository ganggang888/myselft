<?php
//公众号分类model
namespace Common\Model;
use Common\Model\CommonModel;
/**
* 
*/
class WchatTermModel extends CommonModel
{
	
	protected $_validate = array(
			array('term_name', 'require', '分类名称不能为空', 1, 'regex', 3),
	);

	//操作一些数据
	protected function _before_insert(&$data,$option)
	{
		$data['add_time'] = date("Y-m-d H:i:s");
		$data['update_time'] = date("Y-m-d H:i:s");
		$data['admin_id'] = get_current_admin_id();
	}

	//操作数据
	protected function _before_update(&$data,$option)
	{
		$data['update_time'] = date("Y-m-d H:i:s");
		$data['admin_id'] = get_current_admin_id();
	}

	//获取所有分类列表
	public function allLists()
	{
		$field = array('id','term_name');
		$result = $this->where(array('is_delete'=>0))->field($field)->select();
		return $result;
	}
}