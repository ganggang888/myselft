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
		$field = ['id','title','term_id','content','author','excerpt','img','link','add_time'];
		$info = $this->wchat->where("id=%d",array($id))->field($field)->find();
		if (IS_POST) {
			//修改图片或者content后删除旧的图文信息
			if ($info['img'] != I('post.img')) {
				$this->deletePic(array('old'=>$info['img'],'now'=>I('post.img')),2);
			}
			
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
		
		$term = $this->term->allLists();
		$listnav = [['list'=>'公众号文章','edit'=>'修改文章'],['list'=>'课程列表','edit'=>'修改课程']];
		$term = array_filter($term,function($vo)use($type){return $vo['type'] == $type;});
		$this->assign(compact('info','term','type','listnav'));
		$this->display();
	}

	//删除微信文章
	public function delete(int $id)
	{
		$type = I('get.type');
		$type == 1 ? '' : $type = 0;
		$this->wchat->where("id=%d",array($id))->save(array('is_delete'=>1)) ? $this->success('删除成功',U('Wchat/index',array('type'=>$type))) : $this->error('删除失败',U('Wchat/index',array('type'=>$type)));
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

	//删除指定的图文信息
	//$data = array('old'=>'3.jpg','now'=>'4.jpg');  $data = array('old'=>array('4.jpg','5.jpg'),'now'=>array('6.jpg','7.jpg'));  type1
	private function deletePic(array $data,$type)
	{
		switch ($type) {
			case 1:
				
				break;
			case 2:
				if ($data['old'] != $data['now']) {
					$img = str_replace("/info/","./",$data['old']);
					unlink($img);
				}
				break;
			default:
				# code...
				break;
		}
		//都是array获取差集
		
	}

	//活跃数量。日活图表
	public function activeDay()
	{
		$begin = I('get.begin');
		$end = I('get.end');
		$this->assign(compact('begin','end'));
		$begin ? $begin = $begin." 00:00:00" : '';
		$end ? $end = $end." 23:59:59" : '';
		if ($begin OR $end) {
			$result = $this->wchat->messageInfo($begin,$end,1);
			//得到数据后分别取出Y轴数据和曲线数据
			$info = array_column($result, 'num');
			$info = implode(',',$info);
			$x = array_column($result,'day');

			array_map(function($v)use(&$i){$i .= "'$v',";},$x);
			$x = substr($i,0,strlen($i)-1);
			$this->assign(compact('info','x'));
			
		}
		$this->display();
		
	}
	//活跃数量。月活图表
	public function activeMonth()
	{
		$begin = I('get.begin');
		$end = I('get.end');
		$ago = $this->wchat->getYear();
		$this->assign(compact('begin','end','ago'));
		$begin ? $begin = $begin."-01-01 00:00:00" : '';
		$end ? $end = $end."-12-31 23:59:59" : '';
		if ($begin OR $end) {
			$result = $this->wchat->messageInfo($begin,$end,2);
			//得到数据后分别取出X数据和曲线图数据
			$info = array_column($result,'num');
			$info = implode(',',$info);
			$x = array_column($result,'yearmonth');
			array_map(function($v)use(&$i){$i .= "'$v',";},$x);
			$x = substr($i,0,strlen($i)-1);
			$this->assign(compact('info','x'));
		}
		
		$this->display();
	}

}