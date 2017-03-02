<?php
//学员签到
namespace User\Controller;
use Common\Controller\AdminbaseController;
class BeautifulController extends AdminbaseController
{

	protected $student;
	protected $model;
	function _initialize() {
		$this->student = D("Common/Student");
		$this->model = M();
		parent::_initialize();
	}

	//首页
	function index()
	{
		$name = I('get.name');//名称
		$address = I('get.address');//地址
		$term_id = I('get.term_id');
		$where = '';
		$name ? $where .= " AND a.name LIKE '%$name%' " : '';
		$address ? $where .= " AND a.address LIKE '%$name%')" : '';
		$term_id ? $where .= " AND a.term_id = $term_id " : '';
		$where ? $where = preg_replace('/AND/','WHERE',$where,1)  : '';
		$num = $this->model->query("SELECT count(*) AS num FROM sp_student a LEFT JOIN sp_student_term b ON a.term_id = b.id $where");
		$count = $num[0]['num'];
		$page = $this->page($count,10);
		$result = $this->model->query("SELECT a.id,a.name,a.add_time,a.address,a.number,a.phone,b.name as term_name,b.site FROM sp_student a LEFT JOIN sp_student_term b ON a.term_id = b.id $where ORDER BY a.id DESC LIMIT ".$page->firstRow.",".$page->listRows);
		$this->assign('name',$name);
		$this->assign('address',$address);
		$this->assign('term_id',$term_id);
		$this->assign('term',$this->student->termInfo());
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$result);
		$this->display();
	}

	//添加学员
	function add()
	{
		if (IS_POST) {
			if ($this->student->create() !== false) {
				if ($this->student->add() !== false) {
					$this->success('添加成功',U('Beautiful/index'));
				} else {
					$this->error('添加失败');
				}
			} else {
				$this->error($this->student->getError());
			}
		}
		$this->assign('term',$this->student->termInfo());
		$this->display();
	}

	//修改学员信息
	function edit()
	{
		if (IS_POST) {
			$id = I('post.id');
			if ($this->student->create() !== false) {
				if ($this->student->where(array('id'=>$id))->save() !== false) {
					$this->success('修改成功',U('Beautiful/index'));
				} else {
					$this->error('修改失败');
				}
			} else {
				$this->error($this->student->getError());
			}
		}
		$id = I('get.id');
		$info = $this->student->find($id);
		$this->assign('info',$info);
		$this->assign('term',$this->student->termInfo());
		$this->display();
	}

	//删除学员信息f
	function delete()
	{
		$id = I('get.id');
		$this->student->where(array('id'=>$id))->delete() ? $this->success('删除成功') : $this->error('删除失败');
	}
	//分类列表
	function term_index()
	{
		$count = M('student_term')->count();
		$page = $this->page($count,10);
		$result = M('student_term')->limit($page->firstRow,$page->listRows)->select();
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$result);
		$this->display();
	}
	//添加分类
	function add_term()
	{
		if (IS_POST) {
			$_POST['longitude'] = getPoint($_POST['longitude']);
			$_POST['latitude'] = getPoint($_POST['latitude']);
			$_POST['add_time'] = date("Y-m-d H:i:s");
			if (M('student_term')->add($_POST) !== false) {
				$this->success('添加成功',U('Beautiful/term_index'));
			} else {
				$this->error('添加失败');
			}
		}
		$this->display();
	}

	//修改分类
	function edit_term()
	{
		if (IS_POST) {
			$_POST['longitude'] = getPoint($_POST['longitude']);
			$_POST['latitude'] = getPoint($_POST['latitude']);
			if (M('student_term')->where(array('id'=>$_POST['id']))->save($_POST) !== false) {
				$this->success('修改成功',U('Beautiful/term_index'));
			} else {
				$this->error('修改失败',U('Beautiful/term_index'));
			}
		}
		$id = I('get.id');
		$info = M('student_term')->find($id);
		$this->assign('info',$info);
		$this->display();
	}

	//删除分类
	function delete_term()
	{
		$id = I('get.id');
		M('student_term')->where(array('id'=>$id))->delete() ? $this->success('删除成功') : $this->error('删除失败');
	}

	//学员签到记录 uid,term_id,add_time,status
	public function sign()
	{
		$term_id = I('get.term_id');
		$name = I('get.name');
		$phone = I('get.phone');
		$begin = I('get.begin');
		$end = I('get.end');
		$where = '';
		$term_id ? $where .= " AND a.term_id = $term_id " : '';
		$name ? $where .= " AND b.name LIKE '%$name%' " : '';
		$phone ? $where .= " AND b.phone = $phone " : '';
		if ($begin && $end) {
			$where .= " AND a.add_time >= '$begin' && a.add_time <= '$end'";
		} elseif ($begin && !$end) {
			$where .= " a.AND add_time >= '$begin' ";
		} elseif (!$begin && $end) {
			$where .= " a.AND add_time <= '$end' ";
		}
		$where ? $where = preg_replace('/AND/','WHERE',$where,1)  : '';
		
		$num = $this->model->query("SELECT COUNT(*) AS num FROM sp_student_sign a LEFT JOIN sp_student b ON a.uid = b.id LEFT JOIN sp_student_term c ON a.term_id = c.id $where");
		$count = $num[0]['num'];
		$page = $this->page($count,20);
		$result = $this->model->query("SELECT a.id,a.add_time,b.name,a.status,b.number,b.phone,b.address,a.term_id,c.name as term_name FROM sp_student_sign a LEFT JOIN sp_student b ON a.uid = b.id LEFT JOIN sp_student_term c ON a.term_id = c.id $where ORDER BY a.id DESC LIMIT ".$page->firstRow.",".$page->listRows);
		$this->assign('term_id',$term_id);
		$this->assign('name',$name);
		$this->assign('phone',$phone);
		$this->assign('begin',$begin);
		$this->assign('end',$end);
		$this->assign('term',$this->student->termInfo());
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$result);
		$this->display();
	}
}