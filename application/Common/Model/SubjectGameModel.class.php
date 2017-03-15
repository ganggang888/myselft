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

	//操作一些数据
	protected function _before_insert(&$data,$option)
	{
		$data['add_time'] = date("Y-m-d H:i:s");
		$data['admin_id'] = get_current_admin_id();
	}

	//根据数组

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
        
	/*
         * 根据条目名称对库进行模糊查询
         * @access public
         * @param string $name 名称
         * @param int $month 月份
         * @param int $type 类型
         * @return array
         */
        public function selectTestStoreInfo($name,$month,$type)
	{
		$where = "";
		$name ? $where .= " AND INSTR(`Title`,'$name') " : '';
		$month ? $where .= " AND Month = $month " : '';
                $type ? $where .= " AND Type = $type" : '';
		$where ? $where = preg_replace('/AND/','WHERE',$where,1) : '';
		//var_dump("SELECT ID,Title FROM matt_app.M_TestStore WHERE $where");exit;
		$info = $this->query("SELECT ID,Title FROM matt_app.M_TestStore $where");
		return $info;
	}

	//根据数据库存储的数据将nr变成指定的数组
	public function savedTestStore($info)
	{
		$row = unserialize($info);
		$i = implode(',',$row);
		//var_dump("SELECT ID,Title FROM matt_app.M_TestStore WHERE ID IN($i)");
		$info = $this->query("SELECT ID,Title FROM matt_app.M_TestStore WHERE ID IN($i) ORDER BY FIELD(`ID`,$i)");
		return $info;
	}

	//根据题目ID给题目加上名称
	public function getNames($info = "")
	{
		$info = unserialize($info);
		$i = 1;
		$result = array_map(function($v)use($i){$v['listorder'] = $i;$i++;return $v;},$info);
		$i = '';
		//var_dump($result);exit;
		foreach ($result as $vo) {
			$i .= $vo['id'].',';
		}
		$i = substr($i,0,strlen($i)-1);

		//开始查询所有出现的名称
		$i ? $find = $this->query("SELECT id,name,unscramble FROM matt_chat.sp_subject_info WHERE id IN ($i)") : '';

		//var_dump($find);exit;
		$info = array();
		foreach ($find as $value) {
			//var_dump($value);exit;
			$info[$value['id']] = $value;
		}
		foreach ($result as $vo) {

			$vo['name'] = $info[$vo['id']]['name'];
			$vo['score'] ? '' : $vo['unscramble'] = $info[$vo['id']]['unscramble'];
			//$vo['unscramble'] = $info[$vo['id']]['name'];
			$data[] = $vo;
		}
		return $data;
	}
}