<?php
namespace Common\Model;
use Common\Model\CommonModel;
class StudentModel extends CommonModel
{
	
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('name', 'require', '姓名不能为空', 1, 'regex', 3),
			array('number','','工号已存在',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_login字段是否唯一
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

	protected function _before_insert(&$data,$options)
	{
		$data['add_time'] = date("Y-m-d H:i:s");
	}

	//分类信息
	function termInfo()
	{
		$info = $this->query("SELECT name,id,site FROM sp_student_term");
		return $info;
	}

	/*
     *@签到
     *@param $number 工号
     *@param $longitude   经度
     *@param $latitude  纬度
     *@return int 1:学号不存在；2：今天已经签到过；3：不在园区内；4：插入失败；5：签到成功
     * 
     */
	public function sign(int $number,$longitude,$latitude)
	{
		//先检查学号是否存在
		$id = $this->where(array('number'=>$number))->getField('id');
		if (!$id) {
			return 1;
		}
		//再检查今天是否已经签到过
		$find = $this->query("SELECT COUNT(*) AS num FROM sp_student_sign a left join sp_student b on a.uid = b.id WHERE to_days(a.`add_time`) = to_days(now()) AND b.number = $number");
		if ($find[0]['num']) {
			$_SESSION['number'] = $number;
			return 2;
		}

		//检查当前经纬度是否匹配
		$addressId = $this->query("SELECT id FROM sp_student_term WHERE longitude = '$longitude' AND latitude = '$latitude'");
		if (!$addressId) {
			return 3;
		}
		$termId = $addressId[0]['id'];
		//通过后开始签到，插入数据库
		$insert = $this->execute("INSERT INTO sp_student_sign (uid,term_id,status,add_time) VALUES ($id,$termId,1,now())");
		if ($insert) {
			$_SESSION['number'] = $number;
			return 5;
		} else {
			return 4;
		}
	}

}




