<?php
//游戏
namespace User\Controller;
use Common\Controller\AdminbaseController;
class GameController extends AdminbaseController
{
	protected $game;//实例化model
        
        //分类
        protected $shareType = array(
            array('id'=>1,'name'=>'推荐食谱'),
            array('id'=>0,'name'=>'推荐游戏'),
        );
        
        //菜谱以及游戏分类
        protected $storeTerm = array(
            array('id'=>2,'name'=>'游戏库'),
            array('id'=>4,'name'=>'菜谱库'),
        );
	function _initialize() {
		parent::_initialize();
		$this->game = D("Common/SubjectGame");
	}

    /*
     * 推荐首页
     * @access public
     * @param int $month 月份
     * @param int $level 级别
     * @param int $type 类型：1为食谱，2为游戏 
     * @return view:user.game.index
     */
	public function index()
	{
		$month = I('get.month');
		$level = I('get.level');
                $type = I('get.type');
		$where = array();
		$month ? $where['month'] = $month : '';
		$level ? $where['level'] = $level : '';
                $type ? $where['type'] = $type : $where['type'] = 0;
		$count = $this->game->where($where)->count();
		$page = $this->page($count,10);
		$result = $this->game->where($where)->field(array('id','month','level','admin_id','nr','add_time'))->order("add_time DESC")->limit($page->firstRow,$page->listRows)->select();
		foreach ($result as $vo) {
			$vo['nr'] = $this->game->savedTestStore($vo['nr']);
			$data[] = $vo;
		}
                $shareType = $this->shareType;
		$this->assign('page',$page->show('Admin'));
		$this->assign(compact('month','level','type','data','shareType'));
		$this->display();
	}

	//添加
	public function add()
	{
		if (IS_POST) {
			$_POST['nr'] = serialize(I('post.test_id'));
			if ($this->game->create() !== false) {
				if ($this->game->add($_POST) !== false) {
					$_POST['type'] = 0 ? $this->success('添加成功',U('Game/index')) : $this->success('添加成功',U('Game/index',array('type'=>1)));
				} else {
					$this->error('添加失败',U('Game/index'));
				}
			} else {
				$this->error($this->game->getError());
			}
		}
                $this->assign('storeTerm',$this->storeTerm);
                $this->assign('shareType',$this->shareType);
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
					$_POST['type'] = 0 ? $this->success('修改成功',U('Game/index')) : $this->success('修改成功',U('Game/index',array('type'=>1)));
				} else {
					$this->error('修改失败',U('Game/index'));
				}
			} else {
				$this->error($this->game->getError());
			}
		}
		$id = I('get.id');
		$info = $this->game->where("id = %d",array($id))->field(array('id','month','level','type','admin_id','about','nr','add_time'))->find();
		$info['nr'] = $this->game->savedTestStore($info['nr']);
		$this->assign('month',$this->game->allMonth());
		$this->assign('level',$this->game->allLevel());
                $this->assign('storeTerm',$this->storeTerm);
                $this->assign('shareType',$this->shareType);
		$this->assign('info',$info);
		$this->display();
	}

	//检索列表
	public function checkTestStore()
	{
		$name = I('get.name');
		$month = I('get.month');
                $type = I('get.type');
		$result = $this->game->selectTestStoreInfo($name,$month,$type);
		$this->assign('result',$result);
		$this->display();
	}
	//删除
	public function delete()
	{
		$id = I('get.id');
		$this->game->where("id = %d",array($id))->delete() ? $this->success('删除成功',U('Game/index')) : $this->error('删除失败',U('Game/index'));
	}

	//宝宝测评历史记录

}