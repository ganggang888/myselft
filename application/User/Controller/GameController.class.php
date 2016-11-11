<?php
//游戏
namespace User\Controller;
use Common\Controller\AdminbaseController;
class GameController extends AdminbaseController
{
	protected $game;//实例化model
	function _initialize() {
		parent::_initialize();
		$this->game = D("Common/SubjectGame");
	}

	//列表
	public function index()
	{
		$month = I('get.month');
		$level = I('get.level');

		$where = array();
		$month ? $where['month'] = $month : '';
		$level ? $where['level'] = $level : '';

		$count = $this->game->where($where)->count();
		$page = $this->page($count,10);
		$result = $this->game->where($where)->field(array('id','month','level','admin_id','nr','add_time'))->order("add_time DESC")->limit($page->firstRow,$page->listRows)->select();
		foreach ($result as $vo) {
			$vo['nr'] = $this->game->savedTestStore($vo['nr']);
			$data[] = $vo;
		}
		$this->assign('month',$month);
		$this->assign('level',$level);
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$data);
		$this->display();
	}

	//添加
	public function add()
	{
		if (IS_POST) {
			$_POST['nr'] = serialize(I('post.test_id'));
			if ($this->game->create() !== false) {
				if ($this->game->add($_POST) !== false) {
					$this->success('添加成功',U('Game/index'));
				} else {
					$this->error('添加失败',U('Game/index'));
				}
			} else {
				$this->error($this->game->getError());
			}
		}
		$this->assign('month',$this->game->allMonth());
		$this->assign('level',$this->game->allLevel());
		$this->display();
	}

	//修改
	public function edit()
	{
		if (IS_POST) {
			$_POST['nr'] = serialize(I('post.test_id'));
			if ($this->game->create() !== false) {
				if ($this->game->where("id = %d",array($_POST['id']))->save($_POST) !== false) {
					$this->success('修改成功',U('Game/index'));
				} else {
					$this->error('修改失败',U('Game/index'));
				}
			} else {
				$this->error($this->game->getError());
			}
		}
		$id = I('get.id');
		$info = $this->game->where("id = %d",array($id))->field(array('id','month','level','admin_id','about','nr','add_time'))->find();
		$info['nr'] = $this->game->savedTestStore($info['nr']);
		$this->assign('month',$this->game->allMonth());
		$this->assign('level',$this->game->allLevel());
		$this->assign('info',$info);
		$this->display();
	}

	//检索列表
	public function checkTestStore()
	{
		$name = I('get.name');
		$month = I('get.month');
		$result = $this->game->selectTestStoreInfo($name,$month);
		$this->assign('result',$result);
		$this->display();
	}
	//删除
	public function delete()
	{
		$id = I('get.id');
		$this->game->where("id = %d",array($id))->delete() ? $this->success('删除成功',U('Game/index')) : $this->error('删除失败',U('Game/index'));
	}

}