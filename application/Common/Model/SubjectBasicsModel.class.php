<?php
namespace Common\Model;
use Common\Model\CommonModel;
class SubjectBasicsModel extends CommonModel
{
	
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('name', 'require', '名称不能为空', 1, 'regex',3  ),
			array('about', 'require', '介绍不能为空', 1, 'regex', 2 ),
			//array('user_login', 'require', '用户名称不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
			//array('user_pass', 'require', '密码不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
			array('month','','每个月只允许有一张测评试题',0,'unique',1), // 验证user_login字段是否唯一
			//array('user_email','','邮箱帐号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_email字段是否唯一
			//array('user_email','email','邮箱格式不正确！',0,'',CommonModel:: MODEL_BOTH ), // 验证user_email字段格式是否正确
	);

}