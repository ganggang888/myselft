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
		$this->assign('month',$month);
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


	//宝宝试卷列表
	public function examinationIndex()
	{

		$month = I('get.month');
		$basic = D("Common/SubjectBasics");
		$month ? $where = array('month',$month) : $where = array();
		$count = $basic->where($where)->count();
		$page = $this->page($count,15);
		$result = $basic->where($where)->field(array('id','name','month','about','add_time'))->limit($page->firstRow,$page->listRows)->select();
		//var_dump($result);
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$result);
		$this->display();
	}

	//添加试卷
	public function addExamination()
	{
		if (IS_POST) {
			//var_dump($_POST);exit;
			$basic = D("Common/SubjectBasics");
			$_POST['basicexam'] = serialize(I('post.test_id'));
			$_POST['add_time'] = date("Y-m-d H:i:s");
			$_POST['admin_id'] = $this->adminId;
			if ($basic->create() !== false) {
				if ($basic->add($_POST) !== false) {
					$this->success('添加成功',U('Subject/examinationIndex'));
				} else {
					$this->error('添加失败',U('Subject/examinationIndex'));
				}
			} else {
				$this->error($basic->getError());
			}
		}
		$this->assign('term',$this->term->getAllTerm());
		$this->display();
	}
	//修改试卷
	public function editExamination()
	{
		if (IS_POST) {
			$basic = D("Common/SubjectBasics");
			$_POST['basicexam'] = serialize(I('post.test_id'));
			$_POST['add_time'] = date("Y-m-d H:i:s");
			$_POST['admin_id'] = $this->adminId;
			if ($basic->create() !== false) {
				if ($basic->where(array('id'=>I('post.id')))->save($_POST) !== false) {
					$this->success('修改成功',U('Subject/examinationIndex'));
				} else {
					$this->error('修改失败',U('Subject/examinationIndex'));
				}
			} else {
				$this->error($basic->getError());
			}
		}
		$id = I('get.id');
		$month = I('get.month');
		//先查看是否存在该试卷
		$find = M('subject_basics')->where(array('id'=>$id,'month'=>$month))->find();
		!$find ? $this->error('不存在该试卷，请刷新后重试') : '';
		$this->assign('info',$find);
		$this->display();
	}

	//删除一张试卷
	public function deleteExamination()
	{
		$id = I('get.id');
		M('subject_basics')->where(array('id'=>$id))->delete() ? $this->success('删除成功',U('Subject/examinationIndex')) : $this->error('删除失败',U('Subject/examinationIndex'));
	}
	//默认subject Index
	public function defaultSubject()
	{
		$month = I('get.month');
		$basic = D("Common/SubjectBasics");
		$info = $basic->where(array('month'=>$month))->field(array('basicexam'))->find();
		//var_dump($info);
		$i = '';
		$row = unserialize($info['basicexam']);
		$i = implode(',',$row);
		$i ? $where = " WHERE a.id IN ($i) " : $where = '';
		//var_dump(array_count_values($row));
		//由于IN中会存在多个相同的值，故此查询出重复项的重复次数使用union all进行拼接
		$counts = array_count_values($row);
		$union = '';
		//var_dump($where);
		/*foreach ($counts as $key=>$value) {
			if ($value != 1) {
				//var_dump($value);
				//var_dump($key);
				for ($j=1;$j<$value;$j++) {
					$union .= " union all SELECT id,name,term_id,add_time FROM ".C('DB_PREFIX')."subject_info WHERE a.id = $key ";
				}
			}
		}*/
		//var_dump($union);exit;
		if ($info) {
			$result = $this->model->query("SELECT a.id,a.name,b.name AS term_name,a.add_time FROM ".C('DB_PREFIX')."subject_info a LEFT JOIN ".C('DB_PREFIX')."subject_term b ON a.term_id = b.id $where ");
			foreach ($row as $vo) {
				//var_dump(array_filter($result, function($t) use ($vo) { return $t['id'] == $vo; }));echo "<hr/>";
				$arr[] = current(array_filter($result, function($t) use ($vo) { return $t['id'] == $vo; }));
			}

		}
		$this->assign('result',$arr);
		$this->display();
	}

	//selectInfo
	public function selectInfo()
	{
		$month = I('get.month');
		$term = I('get.term');
		$name = I('get.name');
		$where = " a.id > 0 ";
		$month ? $where .= " AND a.month = $month ":'';
		$term ? $where .= " AND a.term_id = $term " : '';
		$name ? $where .= " AND INSTR(a.`name`,'$name') " : '';
		$result = $this->model->query("SELECT a.id,a.name,b.name AS term_name,a.add_time FROM ".C('DB_PREFIX')."subject_info a LEFT JOIN ".C('DB_PREFIX')."subject_term b ON a.term_id = b.id WHERE $where");
		//$result = $this->subject->where($where)->field(array('id','name','add_time'))->select();
		//var_dump($where);
		$this->assign('result',$result);
		$this->display();
	}

}