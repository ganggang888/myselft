<?php
namespace Common\Model;
use Common\Model\CommonModel;
class SubjectAssessModel extends CommonModel {
	
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('month', 'require', '月份不能为空', 1, 'regex', 3),
			array('level', 'require', '级别不能为空', 1, 'regex', 3),
			array('term_id', 'require', '分类id不能为空', 1, 'regex', 3),
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

	protected function _before_insert(&$data,$options)
	{
		$data['add_time'] = date("Y-m-d H:i:s");
	}
	
}