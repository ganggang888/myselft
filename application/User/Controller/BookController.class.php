<?php
//宝宝成长册
namespace User\Controller;
use Common\Controller\AdminbaseController;
class BookController extends AdminbaseController
{
	protected $model = null;
	protected $controllerId = null; //当前管理员
	protected $_input = null;//成长册添加模型
	protected $bookType = null; //成长册分类模型
	function _initialize() {
		parent::_initialize();
		$this->model = M();
		$this->_input = D('input_book');
		$this->bookType = D("Common/HandbookType");
		$this->controllerId = get_current_admin_id();
	}

	//成长册列表
	public function index()
	{
		$count = $this->_input->count();
		$page = $this->page($count,20);
		$rows = $this->_input->limit($page->firstRow . ',' .$page->listRows)->select();
		$this->assign('rows',$rows);
		$this->display();
	}

	public function add()
	{
		//获取成长册分类
		$term = $this->bookType->select();
		$this->assign('term',$term);
		$this->display();
	}

	//搜索
	public function find_month()
	{
		$month = $_GET['month'];
		$type = $_GET['type'];
		$search = I('get.search');
		if ($search) {
			$rows = $this->model->query("SELECT * FROM matt_app.M_TestStore WHERE INSTR(`Title`,'$search')");
		} else {
			$_SESSION['month'] = $month;
			$_SESSION['type'] = $type;
			$rows = $this->model->query("SELECT * FROM matt_app.M_TestStore WHERE Month = $month AND Type = $type ");
		}
		$this->assign('rows',$rows);
		$this->display();
	}

	//提交自己的修改宝宝成长册申请
	public function add_post()
	{
		$age = $_POST['age']; //年龄
		$day = $_POST['day']; //天数
		$feedType = $_POST['feedType']; //订单类型
		$orderMoney = $_POST['orderMoney']; //适用订单
		$about = $_POST['about'];//介绍
		$test_id = $_POST['test_id'];//库id
		$from_time = $_POST['from_time'];//开始时间
		$to_time = $_POST['to_time'];//结束时间
		$term_id = $_POST['term']; //分类
		//将本次专家添加的数据组装到一个数组后序列化后把本次的信息存入数据库
		foreach ($test_id as $key=>$vo) {
			$data[] = array(
				'id'	=>	$vo,
				'from_time'=>	$from_time[$key],
				'to_time'	=>	$to_time[$key],
			);
		}

		$array = array(
			'about'=>$about,
			'age'=>$age,
			'day'=>$day,
			'feed_type'=>$feedType,
			'order_money'=>$orderMoney,
			'info'=>serialize($data),
			'aid'=>$this->controllerId,
			'add_time'=>date('Y-m-d H:i:s'),
			'term'=>$term_id,
		);
		$row = $this->_input->add($array);
		$this->redirect('User/book/index');
	}

	//读取成长册信息
	public function info()
	{
		$id = intval($_GET['id']);
		if (!$id) {
			$this->error('GET OUT !!!');
		}
		$info = $this->_input->find($id);
		$this->assign('info',$info);
		$this->display();
	}

	//修改自己提交的成长册信息，当审核通过后无法修改
	public function edit()
	{
		$id = intval($_GET['id']);
		if (!$id) {
			$this->error('GET OUT');
		}

		//获取所有分类信息
		$term = $this->bookType->select();
		$this->assign('term',$term);
		//读取对应id信息
		$info = $this->_input->where("id = '$id'")->find($id);
		$this->assign('info',$info);
		$this->display();
	}


	//修改提交的成长册信息
	public function edit_post()
	{
		$id = intval($_POST['id']);
		if (!$id) {
			$this->error("GET OUT");
		}
		$age = $_POST['age']; //年龄
		$day = $_POST['day']; //天数
		$feedType = $_POST['feedType']; //订单类型
		$orderMoney = $_POST['orderMoney']; //适用订单
		$about = $_POST['about'];//介绍
		$test_id = $_POST['test_id'];//库id
		$from_time = $_POST['from_time'];//开始时间
		$to_time = $_POST['to_time'];//结束时间
		$term_id = $_POST['term']; //分类
		//将本次专家添加的数据组装到一个数组后序列化后把本次的信息存入数据库
		foreach ($test_id as $key=>$vo) {
			$data[] = array(
				'id'	=>	$vo,
				'from_time'=>	$from_time[$key],
				'to_time'	=>	$to_time[$key],
			);
		}

		$array = array(
			'about'=>$about,
			'age'=>$age,
			'day'=>$day,
			'feed_type'=>$feedType,
			'order_money'=>$orderMoney,
			'info'=>serialize($data),
			'aid'=>$this->controllerId,
			'term'=>$term_id,
		);
		$row = $this->_input->where("id = '$id'")->save($array);
		if ($row) {
			$this->redirect('User/book/index');
		} else {
			$this->error('修改失败');
		}
	}

	//管理员启用专家添加的信息
	public function open()
	{
		$id = $_GET['id'];
		if (!$id) {
			$this->error('GET OUT!!');
		}

		//取出添加的数据批量插入成长册
		$info = $this->_input->find($id);
		$age = $info['age'];
		$day = $info['day'];
		$feed_type = $info['feed_type'];
		$order_money = $info['order_money'];
		$aid = $info['aid'];
		$term = $info['term'];
		$data = unserialize($info['info']);
		foreach($data as $vo) {
			$time = time();
			$array = array(
				'Month'	=>	$age,
				'Day'	=>	$day,
				'TestStore_ID'=>$vo['id'],
				'timestamp'=>time(),
				'From_Time'=>$vo['from_time'],
				'To_Time'=>$vo['To_Time'],
				'Feed_Type'=>$feed_type,
				'Order_Money'=>$order_money,
				'aid'=>$aid,
				'term'=>$term,
			);
			$do = $this->model->execute("INSERT INTO matt_app.M_Special_BabyHandbook (`Month`,`Day`,`TestStore_ID`,`timestamp`,`From_Time`,`To_Time`,`Feed_Type`,`Order_Money`,`aid`,`term`) VALUES ('$age','$day','$vo[id]','$time','$vo[from_time]','$vo[to_time]','$feed_type','$order_money','$aid','$term')");


		}
		$row = $this->_input->where("id = '$id'")->save(array('status'=>1));
		if ($row) {
			$this->success('启用成功');
		} else {
			$this->error('启用失败');
		}
	}

	//管理员禁用成长册内容
	public function close()
	{
		$id = $_GET['id'];
		if (!$id) {
			$this->error('GET OUT!!');
		}
		$info = $this->_input->find($id);
		$aid = $info['aid'];
		$term = $info['term'];
		$age = $info['age'];
		$day = $info['day'];
		$feed_type = $info['feed_type'];
		$order_money = $info['order_money'];
		if ($info['status'] == 1) {
			$sql = "DELETE FROM matt_app.M_Special_BabyHandbook WHERE aid = '$aid' AND term = '$term' AND Month = '$age' AND Day = '$day' AND Feed_Type = '$feed_type' AND Order_Money = '$order_money'";
			$row = $this->model->execute($sql);
		}
		$row = $this->_input->where("id = '$id'")->save(array('status'=>2));
		if ($row) {
			$this->success('禁用成功');
		} else {
			$this->error('禁用失败');
		}
	}
}