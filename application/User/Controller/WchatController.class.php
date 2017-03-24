<?php
//公众号微信文章
namespace User\Controller;
use Common\Controller\AdminbaseController;
class WchatController extends AdminbaseController
{
	protected $term; //分类
	protected $wchat; //微信文章
	function _initialize() {
		parent::_initialize();
		$this->term = D("Common/WchatTerm");
		$this->wchat = D("Common/Wchat");
	}

	//分类首页
	public function term()
	{
		$count = $this->term->where(array('is_delete'=>0))->count();
		$page = $this->page($count,15);
		$result = $this->term->where(array('is_delete'=>0))->limit($page->firstRow,$page->listRows)->field(array('id','term_name','about','add_time'))->select();
		$this->assign(compact('page','result'));
		$this->display();
	}

	//添加公众号分类
	public function add_term()
	{
		if (IS_POST) {
			if ($this->term->create() !== false) {
				if ($this->term->add() !== false) {
					$this->success('添加成功',U('Wchat/term'));
				} else {
					$this->error('添加失败',U('Wchat/term'));
				}
			} else {
				$this->error($this->term->getError());
			}
		}
		$this->display();
	}

	//修改公众号分类
	public function edit_term(int $id)
	{
		if (IS_POST) {
			if ($this->term->create() !== false) {
				if ($this->term->save() !== false) {
					$this->success('添加成功',U('Wchat/term'));
				} else {
					$this->error('修改失败',U('Wchat/term'));
				}
			} else {
				$this->error($this->term->getError());
			}
		}
		$info = $this->term->where("id=%d",array($id))->field(array('id','about','term_name','add_time'))->find();
		$this->assign(compact('info','id'));
		$this->display();
	}

	//删除某一个分类
	public function delete_term(int $id)
	{
		!$id ? $this->error("GET OUT") : '';
		$this->term->where("id=%d",array($id))->save(array('is_delete'=>1)) ? $this->success('删除成功',U('Wchat/term')) : $this->error('删除失败',U('Wchat/term'));
	}

	//微信文章列表
	public function index()
	{
		$title = trim(I('get.title'));
		$author = trim(I('get.author'));
		$term_id = I('get.term_id');
		$begin = I('get.begin');
		$end = I('get.end');
		$this->assign(compact('begin','end'));
		$begin ? $begin = $begin." 00:00:00" : '';
		$end ? $end = $end." 23:59:59" : '';	
		$where['a.is_delete'] = 0;
		$title ? $where['a.title'] = array('like',"%$title%") : '';
		$author ? $where['a.author'] = $author : '';
		$term_id ? $where['a.term_id'] = $term_id : '';
		if ($begin && $end) {
			$where['a.add_time'] = array('EGT',$begin);
			$where['a.add_time'] = array('LT',$end);
		} elseif ($begin && !$end) {
			$where['a.add_time'] = array('EGT',$begin);
		} elseif (!$begin && $end) {
			$where['a.add_time'] = array('LT',$end);
		}
		$join = "".C('DB_PREFIX').'wchat_term as b on a.term_id = b.id';
		$count = $this->wchat->alias('a')->join($join,'LEFT')->where($where)->count();
		$field = array('a.id','a.title','a.author','a.excerpt','a.img','a.link','a.add_time','a.admin_id','b.term_name','a.listorder');
		$order = array('listorder'=>'ASC');
		$page = $this->page($count,15);
		$result = $this->wchat->alias('a')->join($join,'LEFT')->where($where)->field($field)->order($order)->limit($page->firstRow,$page->listRows)->select();
		$term = $this->term->allLists();
		$this->assign(compact('title','author','term_id','page','result','term'));
		$this->display();
	}

	//添加微信文章
	public function add()
	{
		if (IS_POST) {
			if ($this->wchat->create() !== false) {
				if ($this->wchat->add() !== false) {
					$this->success('添加成功',U('Wchat/index'));
				} else {
					$this->error('添加失败',U('Wchat/index'));
				}
			} else {
				$this->error($this->wchat->getError());
			}
		}
		$term = $this->term->allLists();
		$this->assign(compact('term'));
		$this->display();
	}

	//修改微信文章
	public function edit(int $id)
	{
		if (IS_POST) {
			if ($this->wchat->create() !== false) {
				if ($this->wchat->save() !== false) {
					$this->success('修改成功',U('Wchat/index'));
				} else {
					$this->error('修改失败',U('Wchat/index'));
				}
			} else {
				$this->error($this->wchat->getError());
			}
		}
		$field = ['id','title','term_id','author','excerpt','img','link','add_time'];
		$info = $this->wchat->where("id=%d",array($id))->field($field)->find();
		$term = $this->term->allLists();
		$this->assign(compact('info','term'));
		$this->display();
	}

	//删除微信文章
	public function delete(int $id)
	{
		$this->wchat->where("id=%d",array($id))->save(array('is_delete'=>1)) ? $this->success('删除成功',U('Wchat/index')) : $this->error('删除失败',U('Wchat/index'));
	}

	//文章排序
	public function listorders()
	{
		$info = I('post.info');
		$nowUrl = $_POST['nowUrl'];
		$caseThen = '';
		$i = '';
		foreach ($info as $key=>$vo) {
			$caseThen .= " WHEN $key THEN $vo \n";
			$i .= "$key,";
		}
		$i ? $i = substr($i,0,strlen($i)-1) : '';
		if ($i && $caseThen) {
			$sql = " UPDATE sp_wchat SET listorder = CASE id $caseThen \n END \n WHERE ID IN($i)";

			$try = M()->execute($sql);
		}
		$this->success('排序成功',$nowUrl);
		
	}
}