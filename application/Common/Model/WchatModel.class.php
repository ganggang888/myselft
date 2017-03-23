<?php
//公众号文章model
namespace Common\Model;
use Common\Model\CommonModel;
class WchatModel extends CommonModel
{
	protected $_validate = array(
			array('title', 'require', '名称不能为空', 1, 'regex', 3),
			array('term_id', 'require', '请选择分类', 1, 'regex', 3),
			array('link', 'require', '请输入跳转的微信网址', 1, 'regex', 3),
			array('img', 'require', '请务必上传缩略图', 1, 'regex', 3),
	);

	//操作一些数据
	protected function _before_insert(&$data,$option)
	{
		!$data['add_time'] ? $data['add_time'] = date("Y-m-d H:i:s") : '';
		$data['update_time'] = date("Y-m-d H:i:s");
		$data['admin_id'] = get_current_admin_id();
	}

	//操作数据
	protected function _before_update(&$data,$option)
	{
		$data['update_time'] = date("Y-m-d H:i:s");
		$data['admin_id'] = get_current_admin_id();
	}
}