<?php
//测评管理
namespace User\Controller;
use Common\Controller\AdminbaseController;
class SubjectController extends AdminbaseController 
{
	protected $term = null;//分类表
	protected $termUrl = null; //定义分类首页，多次使用时避免重复书写，减少代码
	protected $adminId = null;//管理员ID
	protected $subjectUrl = null;//试题列表
	protected $subject = null; //试题
	protected $model = null; //数据库模型
	protected $scoreTerm = null; //选项分类
	function _initialize() {
		parent::_initialize();
		//为了代码更加的优雅，故在此实例化model,以偏下面的代码更加优雅，简洁
		$this->term = D("Common/SubjectTerm");
		$this->termUrl = "Subject/termIndex";
		$this->adminId = get_current_admin_id();
		$this->subject = D("Common/SubjectInfo");
		$this->subjectUrl = "Subject/subjectIndex";
		$this->scoreTerm = array(1=>array('id'=>1,'name'=>'是,否,不确定'),2=>array('id'=>2,'name'=>'能,不能,不知道'));
		$this->model = M();
	}

	//分类列表
	public function termIndex()
	{
		$count = $this->term->count();
		$page = $this->page($count,10);
		$result = $this->term->field(array('id','name','add_time'))->limit($page->firstRow,$page->listRows)->select();
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$result);
		$this->display();
	}

	//添加分类
	public function addTerm()
	{
		if (IS_POST) {
			$_POST['admin_id'] = $this->adminId;
			$_POST['add_time'] = date("Y-m-d H:i:s");
			$_POST['admin_id'] = $this->adminId;
			if ($this->term->create() !== false) {
				if ($this->term->add() !== false) {
					$this->success('添加成功',U($this->termUrl));
				} else {
					$this->error('添加失败',U($this->termUrl));
				}
			} else {
				$this->error($this->term->getError());
			}
		}
		$this->display();
	}

	//修改分类
	public function editTerm()
	{
		if (IS_POST) {
			if ($this->term->create() !== false) {
				if ($this->term->where(array('id'=>I('post.id')))->save() !== false) {
					$this->success('修改成功',U($this->termUrl));
				} else {
					$this->error('修改失败',U($this->termUrl));
				}
			} else {
				$this->error($this->term->getError());
			}
		}
		$id = I('get.id');
		$info = $this->term->where(array('id'=>$id))->field(array('id','name','add_time'))->find();
		$this->assign('info',$info);
		$this->display();
	}

	//删除某个分类
	public function deleteTerm()
	{
		$id = I('get.id');
		$this->term->where(array('id'=>$id))->delete() ? $this->success('删除成功',U($this->termUrl)) : $this->error('删除失败',U($this->termUrl));
	}

	//题库列表
	public function subjectIndex()
	{
		$term = I('get.term');
		$name = I('get.name');
		$month = I('get.month');
		if ($month && !is_int(intval($month))) {
			$this->error('请输入正确的适用月龄进行查询',U($this->subjectUrl));
		}
		$where = " a.id > 0 ";
		$term ? $where .= " AND b.id = $term " : '';
		$name ? $where .= " AND a.name LIKE '%$name%' " : '';
		$month ? $where .= " AND a.month = $month " : '';
		$num = $this->model->query("SELECT COUNT(*) AS num FROM ". C('DB_PREFIX')."subject_info a LEFT JOIN ".C('DB_PREFIX')."subject_term b ON a.term_id = b.id WHERE $where");
		$page = $this->page($num[0]['sum'],20);
		$sql = "SELECT a.name,b.name AS term_name ,a.score_term,a.id,a.month,a.careful,a.add_time FROM ". C('DB_PREFIX')."subject_info a LEFT JOIN ".C('DB_PREFIX')."subject_term b ON a.term_id = b.id WHERE $where LIMIT ".$page->firstRow.",".$page->listRows;
		$result = $this->model->query($sql);
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$result);
		$this->assign('term',$term);
		$this->assign('name',$name);
		$this->assign('allTerm',$this->term->getAllTerm());
		$this->assign('score_term',$this->scoreTerm);
		$this->display();
	}
	//添加题库
	public function addSubject()
	{
		if (IS_POST) {
			$month = intval(I('post.month'));
			if (!is_int($month) || $month > 36) {
				$this->error('请输入正确的适用月龄');
			}
			$_POST['admin_id'] = $this->adminId;
			$_POST['add_time'] = date("Y-m-d H:i:s");
			if ($this->subject->create() !== false) {
				if ($this->subject->add() !== false) {
					$this->success('添加成功',U($this->subjectUrl));
				} else {
					$this->error('添加失败',U($this->subjectUrl));
				}
			} else {
				$this->error($this->subject->getError());
			}
		}
		$this->assign('term',$this->term->getAllTerm());
		$this->assign('scoreTerm',$this->scoreTerm);
		$this->display();
	}

	//修改题库
	public function editSubject()
	{
		if (IS_POST) {
			$_POST['admin_id'] = $this->adminId;
			if ($this->subject->create() !== false) {
				if ($this->subject->where(array('id'=>I('post.id')))->save() !== false) {
					$this->success('修改成功',U($this->subjectUrl));
				} else {
					$this->error('修改失败');
				}
			} else {
				$this->error($this->subject->getError());
			}
		}
		$id = I('get.id');
		$info = $this->model->query("SELECT a.name,b.name AS term_name ,a.term_id,a.score_term,a.month,a.careful,a.score_term,a.id FROM ".C('DB_PREFIX')."subject_info a LEFT JOIN ".C('DB_PREFIX')."subject_term b ON a.term_id = b.id WHERE a.id = $id");
		$this->assign('info',$info[0]);
		$this->assign('term',$this->term->getAllTerm());
		$this->assign('scoreTerm',$this->scoreTerm);
		$this->display();
	}

	//删除题库
	public function deleteSubject()
	{
		$id = I('get.id');
		$this->subject->where(array('id'=>$id))->delete() ? $this->success('删除成功',U($this->subjectUrl)) : $this->error('删除失败',U($this->subjectUrl));
	}

}