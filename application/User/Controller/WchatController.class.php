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
		$type = I('get.type');
		$type == 1 ? '' : $type = 0;
		$where = array('is_delete'=>0,'type'=>$type);
		$count = $this->term->where($where)->count();
		$page = $this->page($count,15);
		$result = $this->term->where($where)->limit($page->firstRow,$page->listRows)->field(array('id','term_name','about','add_time'))->select();
		$listnav = [['list'=>'公众号分类','add'=>'添加分类'],['list'=>'课程分类','add'=>'添加分类']];
		$this->assign(compact('page','result','type','listnav'));
		$this->display();
	}

	//添加公众号分类
	public function add_term()
	{
		$type = I('get.type');
		$type == 1 ? '' : $type = 0;
		if (IS_POST) {
			if ($this->term->create() !== false) {
				if ($this->term->add() !== false) {
					$this->success('添加成功',U('Wchat/term',array('type'=>$type)));
				} else {
					$this->error('添加失败',U('Wchat/term',array('type'=>$type)));
				}
			} else {
				$this->error($this->term->getError());
			}
		}
		
		$listnav = [['list'=>'公众号分类','add'=>'添加分类'],['list'=>'课程分类','add'=>'添加分类']];
		$this->assign(compact('type','listnav'));
		$this->display();
	}

	//修改公众号分类
	public function edit_term(int $id)
	{
		$type = I('get.type');
		$type == 1 ? '' : $type = 0;
		if (IS_POST) {
			if ($this->term->create() !== false) {
				if ($this->term->save() !== false) {
					$this->success('添加成功',U('Wchat/term',array('type'=>$type)));
				} else {
					$this->error('修改失败',U('Wchat/term',array('type'=>$type)));
				}
			} else {
				$this->error($this->term->getError());
			}
		}
		$info = $this->term->where("id=%d",array($id))->field(array('id','about','term_name','add_time'))->find();
		$listnav = [['list'=>'公众号分类','edit'=>'修改分类'],['list'=>'课程分类','edit'=>'修改分类']];
		$this->assign(compact('info','id','listnav','type'));
		$this->display();
	}

	//删除某一个分类
	public function delete_term(int $id)
	{
		!$id ? $this->error("GET OUT") : '';
		$type = I('get.type');
		$this->term->where("id=%d",array($id))->save(array('is_delete'=>1)) ? $this->success('删除成功',U('Wchat/term',array('type'=>$type))) : $this->error('删除失败',U('Wchat/term',array('type'=>$type)));
	}

	//微信文章列表
	public function index()
	{
		$type = I('get.type');
		$type == 1 ? '' : $type = 0;
		$title = trim(I('get.title'));
		$author = trim(I('get.author'));
		$term_id = I('get.term_id');
		$begin = I('get.begin');
		$end = I('get.end');
		$this->assign(compact('begin','end'));
		$begin ? $begin = $begin." 00:00:00" : '';
		$end ? $end = $end." 23:59:59" : '';	
		$where['a.is_delete'] = 0;
		$where['b.type'] = $type;
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
		$term = array_filter($term,function($vo)use($type){return $vo['type'] == $type;});
		$listnav = [['list'=>'公众号文章','add'=>'添加文章'],['list'=>'课程列表','add'=>'添加课程']];
		$this->assign(compact('title','author','term_id','page','result','term','listnav','type'));
		$this->display();
	}

	//添加微信文章
	public function add()
	{
		$type = I('get.type');
		$type == 1 ? '' : $type = 0;
		if (IS_POST) {
			if ($this->wchat->create() !== false) {
				if ($this->wchat->add() !== false) {
					$this->success('添加成功',U('Wchat/index',array('type'=>$type)));
				} else {
					$this->error('添加失败',U('Wchat/index',array('type'=>$type)));
				}
			} else {
				$this->error($this->wchat->getError());
			}
		}
		$listnav = [['list'=>'公众号文章','add'=>'添加文章'],['list'=>'课程列表','add'=>'添加课程']];
		$term = $this->term->allLists();
		$term = array_filter($term,function($vo)use($type){return $vo['type'] == $type;});
		$this->assign(compact('term','listnav','type'));
		$this->display();
	}

	//修改微信文章
	public function edit(int $id)
	{
		$type = I('get.type');
		$type == 1 ? '' : $type = 0;
		if (IS_POST) {
			if ($this->wchat->create() !== false) {
				if ($this->wchat->save() !== false) {
					$this->success('修改成功',U('Wchat/index',array('type'=>$type)));
				} else {
					$this->error('修改失败',U('Wchat/index',array('type'=>$type)));
				}
			} else {
				$this->error($this->wchat->getError());
			}
		}
		$field = ['id','title','term_id','content','author','excerpt','img','link','add_time'];
		$info = $this->wchat->where("id=%d",array($id))->field($field)->find();
		$term = $this->term->allLists();
		$listnav = [['list'=>'公众号文章','edit'=>'修改文章'],['list'=>'课程列表','edit'=>'修改课程']];
		$term = array_filter($term,function($vo)use($type){return $vo['type'] == $type;});
		$this->assign(compact('info','term','type','listnav'));
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

	//课程分类
	public function education_term_index()
	{
		$where = ['is_delete'=>0,'type'=>1];
		$count = $this->term->where($where)->count();
		$page = $this->page($page,15);
		$result = $this->term->where($where)->field(array('id','term_name','about','add_time'))->limit($page->firstRow,$page->listRows)->select();
		$this->assign(compact('page','result'));
		$this->display();
	}

	//添加课程分类
	public function education_term_add()
	{
		if (IS_POST) {
			if ($this->term->create() !== false) {
				if ($this->term->add() !== false) {
					$this->success('添加成功',U('Wchat/education_term_index'));
				} else {
					$this->error('添加失败',U('Wchat/education_term_index'));
				}
			} else {
				$this->error($this->term->getError());
			}
		}
		$this->display();
	}

	//修改课程分类
	public function education_term_edit(int $id)
	{
		if (IS_POST) {
			if ($this->term->create() !== false) {
				if ($this->term->save() !== false) {
					$this->success('修改成功',U('Wchat/education_term_index'));
				} else {
					$this->error('修改失败',U('Wchat/education_term_index'));
				}
			} else {
				$this->error($this->term->getError());
			}
		}
		$info = $this->term->where("id=%d",array($id))->field(array('id','term_name','about','add_time'))->find();
		$this->assign(compact('info'));
		$this->display();
	}

}