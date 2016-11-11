<?php
//评估
namespace User\Controller;
use Common\Controller\AdminbaseController;
class AssessController extends AdminbaseController
{
	protected $_assess; //评估model
	protected $_term; //分类model
	protected $_game;
	function _initialize() {
		parent::_initialize();
		$this->_assess = D("Common/SubjectAssess");//实例化Model
		$this->_term = D("Common/SubjectTerm");
		$this->_game = D("Common/SubjectGame");
	}

	//列表
	public function index()
	{
		$month = I('get.month');
		$term_id = I('get.term_id');
		$month ? $where["month"] = $month : '';
		$term_id ? $where["term_id"] = $term_id : '';
		$count = $this->_assess->where($where)->count();
		$page = $this->page($count,10);
		$result = $this->_assess->where($where)->field(array('id','month','term_id','level','sad','add_time'))->order("id DESC")->limit($page->firstRow,$page->listRows)->select();
		$this->assign('result',$result);
		$this->assign('month',$month);
		$this->assign('term_id',$term_id);
		$this->assign('term',$this->_term->getAllTerm());
		$this->display();
	}

	//添加
	public function add()
	{
		if (IS_POST) {
			if ($this->_assess->create() !== false) {
				if ($this->_assess->add() !== fasle) {
					$this->success('添加成功',U('Assess/index'));
				} else {
					$this->error('添加失败',U('Assess/index'));
				}
			} else {
				$this->error($this->_assess->getError());
			}
		}
		$this->assign('month',$this->_game->allMonth());
		$this->assign('level',$this->_game->allLevel());
		$this->assign('term',$this->_term->getAllTerm());
		$this->display();
	}

	//修改
	public function edit()
	{
		if (IS_POST) {
			$id = I("post.id");
			if ($this->_assess->create() !== false) {
				if ($this->_assess->where("id=%d",array($id))->save() !== false) {
					$this->success('修改成功',U('Assess/index'));
				} else {
					$this->error('修改失败，请联系管理员');
				}
			} else {
				$this->error($this->_assess->getError());
			}
		}
		$id = I('get.id');
		$find = $this->_assess->where("id=%d",array($id))->field(array('id','month','term_id','level','sad','add_time'))->find();
		$this->assign('info',$find);
		$this->assign('month',$this->_game->allMonth());
		$this->assign('level',$this->_game->allLevel());
		$this->assign('term',$this->_term->getAllTerm());
		$this->display();
	}

	//删除
	public function delete()
	{
		$id = I('get.id');
		$this->_assess->where("id=%d",array($id))->delete() ? $this->success("删除成功",U('Assess/index')) : $this->error("删除失败",U('Assess/index'));
	}
}