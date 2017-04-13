<?php
//公众号文章model
namespace Common\Model;
use Common\Model\CommonModel;
class WchatModel extends CommonModel
{
	protected $_validate = array(
			array('title', 'require', '名称不能为空', 1, 'regex', 3),
			array('term_id', 'require', '请选择分类', 1, 'regex', 3),
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

	//查询日活数据或者月活数据 1日活  2月活
	public function messageInfo($begin,$end,$type)
	{
		if ($begin && $end) {
			$where = " WHERE add_time >= '$begin' AND add_time < '$end'";
		} elseif ($begin && !$end) {
			$where = " WHERE add_time >= '$begin' ";
		} elseif (!$begin && $end) {
			$where = " WHERE add_time < '$end'";
		}
		switch ($type) {
			case 1:
				$sql = "SELECT COUNT(DISTINCT `uid`) AS num,DATE(`add_time`) AS day FROM matt_app.teacher_log $where group by DATE(`add_time`)";
				break;
			case 2:
				$sql = "SELECT COUNT(DISTINCT `uid`) AS num,extract(YEAR_MONTH FROM `add_time`) AS yearMonth FROM matt_app.teacher_log $where group by extract(YEAR_MONTH FROM `add_time`)";
				break;
			default:
				break;
		}
		$result = $this->query($sql);
		return $result;
	}

	//获取最早记录年份
	public function getYear()
	{
		$year = $this->query("SELECT YEAR(`add_time`) AS year FROM matt_app.teacher_log ORDER BY add_time ASC LIMIT 0,1");
		return $year[0]['year'];
	}
}