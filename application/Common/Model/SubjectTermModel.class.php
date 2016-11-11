<?php
namespace Common\Model;
use Common\Model\CommonModel;
class SubjectTermModel extends CommonModel 
{
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('name', 'require', '分类名称不能为空！', 1, 'regex', 3),
	);

	//获取所有分类列表
	public function getAllTerm()
	{
		$result = $this->field(array('name','id'))->select();
		//$data[] = $this->field(array('name','id'))->find();
		foreach ($result as $vo) {
			$data[] = $vo;
		}
		return $data;
	}
}