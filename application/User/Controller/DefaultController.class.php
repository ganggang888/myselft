<?php
//默认成长册管理
namespace User\Controller;
use Common\Controller\AdminbaseController;
use Common\Service\InfoService;
class DefaultController extends AdminbaseController  implements InfoService
{
	protected $model = null;
	protected $controllerId;
	protected $feed_type = array(
		array('id'=>0,'name'=>'母乳喂养'),
		array('id'=>1,'name'=>'混合喂养'),
		array('id'=>2,'name'=>'人工喂养'),
		array('id'=>3,'name'=>'不分'),
	);
	protected $stotr_type = array(
		array('id'=>1,'name'=>'行为库'),
		array('id'=>2,'name'=>'游戏库'),
		array('id'=>3,'name'=>'儿保库'),
		array('id'=>4,'name'=>'菜谱库'),
	);
	protected $orderMoney = array(
		array('number'=>8866),
		array('number'=>12888),
		array('number'=>15888),
	);
	function _initialize() {
		parent::_initialize();
		$this->model = M();
		$this->controllerId = get_current_admin_id();
		$this->assign('feed_type',$this->feed_type);
		$this->assign('stotr_type',$this->stotr_type);
	}
	//默认成长册列表
	public function index()
	{
		$where = " WHERE ID > 0 ";
		$feed_type = I('get.feed_type');
		$month = I('get.month');
		$orderMoney = I('get.orderMoney');
		$where .= $feed_type ? " AND Feed_Type = $feed_type" : " AND Feed_Type = 0";
		$where .= $month ? " AND Month = $month" : "";
		$where .= $orderMoney ? " AND Order_Money = $orderMoney" : " AND Order_Money = 8866";
		$this->assign('month',$month);
		$this->assign('type',$feed_type);
		$this->assign('money',$orderMoney);
		$num = $this->model->query("SELECT COUNT(distinct Month,Day) AS num FROM matt_app.M_BabyDefaultHandbook ".$where);
		$count = $num[0]['num'];
		$page = $this->page($count,20);
		$result = $this->model->query("SELECT Month,Day,Feed_Type,Order_Money FROM matt_app.M_BabyDefaultHandbook ".$where." GROUP BY Month,Day,Feed_Type LIMIT ".$page->firstRow.",".$page->listRows);
		$this->assign('orderMoney',$this->orderMoney);
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$result);
		$this->display();
	}

	//修改成长册
	public function edit()
	{
		$month = I('get.month');
		$day = I('get.day');
		$feed_type = I('get.feed_type');
		$order_money = I('get.order_money');
		$result = $this->model->query("SELECT b.ID AS ID,a.timestamp AS timestamp,a.TestStore_ID AS TestStore_ID,a.From_Time AS From_Time,a.To_Time AS To_Time,a.Feed_Type AS Feed_Type,b.Type,a.Month,b.Title AS Title FROM matt_app.M_BabyDefaultHandbook a LEFT JOIN matt_app.M_TestStore b ON a.TestStore_ID = b.ID WHERE a.month = $month AND a.day = $day AND a.feed_type = $feed_type AND a.Order_Money = $order_money AND b.Title IS NOT NULL AND a.Is_Deleted = 0 ORDER BY a.From_Time ASC ");
		$this->assign('month',$month);
		$this->assign('day',$day);
		$this->assign('feed_type',$feed_type);
		$this->assign('order_money',$order_money);
		$this->assign('mon',$_SESSION['month']);
		$this->assign('typ',$_SESSION['type']);
		$this->assign('result',$result);
		$this->display();
	}

	//提交修改默认成长册内容
	public function edit_post()
	{
		//先生成需要添加到数据库里面的所有的东西
		$month = I('post.month');
		$day = I('post.day');
		$feed_type = I('post.feed_type');
		$order_money = I('post.order_money');
		$from_time = I('post.from_time');
		$to_time = I('post.to_time');
		$test_id = I('post.test_id');
		//先删除掉所有的东西
		$this->model->execute("DELETE FROM matt_app.M_BabyDefaultHandbook WHERE Month = $month AND Day = $day AND Feed_Type = $feed_type AND Order_Money = $order_money");
		foreach ($test_id as $key=>$vo) {
			$TestStore_ID = $vo;
			$From_Time = $from_time[$key];
			$To_Time = $to_time[$key];
			$this->model->execute("INSERT INTO matt_app.M_BabyDefaultHandbook (Month,Day,TestStore_ID,From_Time,To_Time,Feed_Type,Order_Money) VALUES ($month,$day,$TestStore_ID,'$From_Time','$To_Time',$feed_type,$order_money)");
		}
		$this->success("修改成功");
	}

	//所有库
	public function store()
	{
		$month = I('get.month');
		$title = I('get.title');
		$type = I('get.type');
		$where = " WHERE Type IN(1,2,3,4) AND IsDelete = 0 AND Title IS NOT NULL";
		$where .= $month ? " AND Month = $month" : '';
		$where .= $title ? " AND INSTR(`Title`,'$title')" : '';
		$where .= $type ? " AND Type = $type" : '';
		$num = $this->model->query("SELECT COUNT(*) AS num FROM matt_app.M_TestStore ".$where);
		$count = $num[0]['num'];
		$page = $this->page($count,20);
		$result = $this->model->query("SELECT ID,Title,Month,CreateDate,Media,Type,Content,Tool,Foodstuff,Personal,Notes FROM matt_app.M_TestStore ".$where." ORDER BY ID DESC LIMIT ".$page->firstRow.",".$page->listRows);
		$this->assign('month',$month);
		$this->assign('title',$title);
		$this->assign('type',$type);
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$result);
		$this->display();
	}

	//修改库里面的东西
	public function edit_store()
	{
		$id = I('get.id');
		$info = $this->model->query("SELECT ID,Title,Month,Type,CreateDate,Media,Content,Tool,Foodstuff,Personal,Notes FROM matt_app.M_TestStore WHERE ID =$id");
		$this->assign('info',$info[0]);
		$this->display();
	}

	//提交修改库里面的内容
	public function edit_store_post()
	{
		extract($_POST);
		!$title || !$content ? $this->error('缺少参数') : '';
		$add = '';
		if ($_FILES["uploadmedia"]["error"] == 0) {
			$info = explode('.',$_FILES["uploadmedia"]["name"]);
	 		$hou = $info[1];
            $path = "uploads".'/'.uniqid().".".$hou;
            move_uploaded_file($_FILES["uploadmedia"]["tmp_name"],$path);
            $url = "info/".$path;
            $add .= " ,Media = '$url'";
        }
        if ($type == 4) {
        	$add .= " ,Foodstuff= '$foodstuff',Tool='$tool',Personal='$personal',Notes='$notes'";
        }
		$row = $this->model->execute("UPDATE matt_app.M_TestStore SET Title = '$title',Test_Type=9999,Content='$content',Month='$month'".$add." WHERE ID = $id");
		if ($row) {
			$this->success('修改成功');
		} else {
			$this->error('修改失败');
		}
		
	}

	//添加库
	public function add_store()
	{
		$this->display();
	}

	//添加库post
	public function add_store_post()
	{
		extract($_POST);
		!$title || !$content ? $this->error('缺少参数') : '';
		if ($_FILES["uploadmedia"]["error"] == 0) {
			$info = explode('.',$_FILES["uploadmedia"]["name"]);
	 		$hou = $info[1];
            $path = "uploads".'/'.uniqid().".".$hou;
            move_uploaded_file($_FILES["uploadmedia"]["tmp_name"],$path);
            $url = "info/".$path;
        }
        $date = date("Y-m-d H:i:s");
        $row = $this->model->execute("INSERT INTO matt_app.M_TestStore (Title,Test_Type,Month,Type,Media,Content,Foodstuff,Tool,Personal,Notes,CreateDate) VALUES ('$title',9999,$month,$type,'$url','$content','$foodstuff','$tool','$personal','$notes','$date')");
        if ($row) {
        	$this->success("添加成功",U('Default/store'));
        } else {
        	$this->error("添加失败",U('Default/store'));
        }
	}

	public function store_delete()
	{
		$id = I('get.id');
		!$id ? $this->error("滚粗") : '';
		$row = $this->model->execute("UPDATE matt_app.M_TestStore SET IsDelete = 1 WHERE ID = $id");
		$row ? $this->success("删除成功") : $this->error("删除失败");
	}

}