<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Common\Model;
use Common\Model\CommonModel;
class SubjectFoodModel extends CommonModel
{
        //自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('month', 'require', '月份不能为空', 1, 'regex', 3),
			array('level', 'require', '级别不能为空', 1, 'regex', 3),
			array('nr', 'require', '内容不能为空', 1, 'regex', 3),
	);

	protected function _before_insert(&$data,$option)
	{
		$data['add_time'] = date("Y-m-d H:i:s");
		$data['admin_id'] = get_current_admin_id();
	}


	/*
         * 获取所有分类月份数组
         * @access public
         * @return array
         */
	public function allMonth()
	{
		for($i=1;$i<=36;$i++) {
			$arr[] = $i;
		}
		return $arr;
	}

	/*
         * 所有level级别数组
         * @access public
         * @return array
         */
	public function allLevel()
	{
		$array = array(1,2,3);
		return $array;
	}

	/*
         * 根据条目名称对库进行模糊查询
         * @access public
         * @param string $name 名称
         * @param int $month 月份
         * @return array
         */
	public function selectTestStoreInfo($name,$month)
	{
		$where = "";
		$name ? $where .= " AND INSTR(`Title`,'$name') " : '';
		$month ? $where .= " AND Month = $month " : '';
		$where ? $where = preg_replace('/AND/','WHERE',$where,1) : '';
		//var_dump("SELECT ID,Title FROM matt_app.M_TestStore WHERE $where");exit;
		$info = $this->query("SELECT ID,Title FROM matt_app.M_TestStore $where");
		return $info;
	}

	/*
         * 根据数据库存储的数据将nr变成指定的数组
         * @access public
         * @param array $info 
         * @return boolean
         */
	public function savedTestStore($info)
	{
		$row = unserialize($info);
		$i = implode(',',$row);
		//var_dump("SELECT ID,Title FROM matt_app.M_TestStore WHERE ID IN($i)");
		$info = $this->query("SELECT ID,Title FROM matt_app.M_TestStore WHERE ID IN($i) ORDER BY FIELD(`ID`,$i)");
		return $info;
	}
}