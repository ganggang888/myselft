<?php
//宝宝
namespace User\Controller;
use Common\Controller\AdminbaseController;
class BabyController extends AdminbaseController
{
	protected $users = null;
	protected $model = null;
	function _initialize() {
		parent::_initialize();
		$this->users = D("Common/Users");
		$this->model = M();
	}
	public function index()
	{
		//宝宝名称搜索
		if ($_GET['name']) {
			$name = $_GET['name'];
			$where = "AND INSTR(`Baby_Name`,'$name') ";
			$this->assign('name',$name);
		}

		//根据手机号搜索宝宝的妈妈
		if ($_GET['phone']) {
			$phone = $_GET['phone'];
			$where .= " AND a.BandMother IN(SELECT ID FROM matt_app.M_Teacher WHERE INSTR(`Telephone`,'$phone')) ";
			$this->assign('phone',$phone);
		}
		$all = $this->model->query("SELECT COUNT(*) AS num FROM matt_app.M_Baby a LEFT JOIN matt_app.M_Teacher b ON a.BandMother = b.ID LEFT JOIN matt_chat.sp_handbook_type c ON a.handbook_type = c.id WHERE a.IsDelete != 1 ".$where);
		//$all = $this->users->todo("SELECT COUNT(*) AS num FROM M_Baby WHERE IsDelete !=1 ".$where);
		$count = $all[0]['num'];
		$page = $this->page($count,20);
		$rows = $this->model->query("SELECT a.Baby_Name AS name,a.Baby_Date AS birthday,a.HandbookDays AS handbookdays,a.Baby_ID AS id,b.Telephone AS phone,c.name AS term FROM matt_app.M_Baby a LEFT JOIN matt_app.M_Teacher b ON a.BandMother = b.ID LEFT JOIN matt_chat.sp_handbook_type c ON a.handbook_type = c.id WHERE a.IsDelete != 1 ".$where ." LIMIT ".$page->firstRow . ",".$page->listRows);
		/*$rows = $this->users->todo("SELECT * FROM M_Baby WHERE IsDelete !=1 ".$where." ORDER BY Baby_ID DESC LIMIT ".$page->firstRow.",".$page->listRows);*/
		$this->assign('page',$page->show('Admin'));
		$this->assign('rows',$rows);
		$this->display();
	}

	//测试mysql负载
	public function test()
	{
		$count = $this->users->todo("show variables like 'long_query_time'");
		var_dump($count);
	}
	//删除宝宝信息
	public function delete()
	{
		$id = intval($_GET['id']);
		if (!$id) {
			$this->error('get out');
		}
		$model = M();
		$row = $model->execute("DELETE FROM matt_app.M_Baby WHERE Baby_ID = $id");
		$this->success('成功');
		
	}

	//给宝宝成长册分类
	public function give()
	{
		$id = intval($_GET['id']);
		$model = M();
		$bookType = D("Common/HandbookType");
		$term = $bookType->select();
		$info = $model->query("SELECT * FROM matt_app.M_Baby WHERE Baby_ID = '$id'");
		$this->assign('term',$term);
		$this->assign('info',$info['0']);
		$this->display();
	}

	//提交请求
	public function give_post()
	{
		$model = M();
		if (IS_POST) {
			$id = intval($_POST['id']);
			if (!$id) {
				$this->error('GET OUT');
			}
			$term = $_POST['term'];
			$row = $model->execute("UPDATE matt_app.M_Baby SET handbook_type = '$term' WHERE Baby_ID = '$id'");
			if ($row) {
				$this->success('分配成功',U('baby/index'));
			} else {
				$this->error('未提交任何修改');
			}
		}
	}
}