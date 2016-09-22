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
	function _initialize() {
		parent::_initialize();
		//为了代码更加的优雅，故在此实例化model,以偏下面的代码更加优雅，简洁
		$this->term = D("Common/SubjectTerm");
		$this->termUrl = "Subject/termIndex";
		$this->adminId = get_current_admin_id();
		$this->subject = D("Common/SubjectInfo");
		$this->subjectUrl = "Subject/subjectIndex";
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
			if ($this->term->create() !== false) {
				if ($this->term->add() !== false) {
					$this->success('添加成功',U($this->termUrl))
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
		$where = " a.id > 0 ";
		$term ? $where .= " AND b.id = $term " : '';
		$name ? $where .= " AND a.name LIKE '%%'";
		$term = $this->
		$this->display();
	}
	//添加题库
	public function addSubject()
	{
		if (IS_POST) {
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
		$this->display();
	}




}