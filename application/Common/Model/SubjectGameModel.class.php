<?php
namespace Common\Model;
use Common\Model\CommonModel;
class SubjectGameModel extends CommonModel
{
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('month', 'require', '月份不能为空', 1, 'regex', 3),
			array('level', 'require', '级别不能为空', 1, 'regex', 3),
			array('nr', 'require', '内容不能为空', 1, 'regex', 3),
	);

	//获取所有分类月份数组
	public function allMonth()
	{
		for($i=1;$i<=36;$i++) {
			$arr[] = $i;
		}
		return $arr;
	}

	//所有level级别数组
	public function allLevel()
	{
		$array = array(1,2,3);
		return $array;
	}

	//根据条目名称对库进行模糊查询
	public function selectTestStoreInfo($name,$month)
	{
		$where = "";
		$name ? $where .= " AND INSTR(`Title`,'$name') " : '';
		$month ? $where .= " AND Month = $month " : '';
		$where ? $where = preg_replace('/AND/','WHERE',$where,1) : '';
		$info = $this->query("SELECT ID,Title FROM matt_app.M_TestStore WHERE $where");
		return $info;
	}

	//根据数据库存储的数据将nr变成指定的数组
	public function savedTestStore($info)
	{
		$row = unserialize($info);
		$i = implode(',',$row);
		$info = $this->query("SELECT ID,Title FROM matt_app.M_TestStore WHERE ID IN($i)");
		return $info;
	}
}