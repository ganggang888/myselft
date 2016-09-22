<?php
//成长册分类
namespace User\Controller;
use Common\Controller\AdminbaseController;
class AddController extends AdminbaseController
{
	protected $users = null; //user模型
	protected $bookType = null; //成长册分类模型
	protected $controllerId = null; //当前管理员
	function _initialize() {
		parent::_initialize();
		$this->bookType = D("Common/HandbookType");
		$this->users = D("Common/Users");
		$this->controllerId = get_current_admin_id();
	}

	//成长册分类列表
	public function term()
	{
		$count = $this->bookType->count();
		$page = $this->page($count,20);
		$rows = $this->bookType->limit($page->firstRow . ',' .$page->listRows)->select();
		$this->assign('page',$page->show('Admin'));
		$this->assign('rows',$rows);
		$this->display();
	}

	//添加成长册分类
	public function add_term()
	{
		$this->display();
	}

	//处理添加的信息
	public function post_add_term()
	{
		if (IS_POST) {
			$_POST['post']['aid'] = $this->controllerId;
			if ($this->bookType->create($_POST[post])) {
				if ($this->bookType->add($_POST[post])) {
					$this->success('添加成功',U('add/term'));
				} else {
					$this->error('添加失败');
				}
			} else {
				$this->error($this->bookType->getError());
			}
		}
	}

	//修改已经添加的信息
	public function edit_term()
	{
		$id = $_GET['id'];
		$info = $this->bookType->find($id);
		$this->assign('info',$info);
		$this->display();
	}

	//处理修改的信息
	public function edit_post_term()
	{
		if (IS_POST) {
			$_POST['post']['aid'] = $this->controllerId;
			if ($this->bookType->create($_POST[post])) {
				if ($this->bookType->save($_POST[post])) {
					$this->success('修改成功',U('add/term'));
				} else {
					$this->error('您没有修改任何内容哟');
				}
			} else {
				$this->error($this->bookType->getError());
			}
		}
	}

	//删除已经添加的成长册分类
	public function delete_term()
	{
		$id = $_GET['id'];
		if ($this->bookType->where("id = '$id'")->delete() !== false) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}

	//
}