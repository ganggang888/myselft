<?php
/*
专家管理
*/
namespace User\Controller;
use Common\Controller\AdminbaseController;
class TeacherController extends AdminbaseController 
{
	protected $classify = null;
	protected $users = null;
	protected $model = null;
	function _initialize() {
		parent::_initialize();
		$this->users = D("Common/Users");
		$this->classify = D("Common/Classify");
		$this->model = M();
	}
	public function term()
	{
		$count = $this->classify->count();
		$page = $this->page($count,10);
		$all = $this->classify->limit($page->firstRow . ',' . $page->listRows)->select();
		$this->assign('page',$page->show('Admin'));
		$this->assign('all',$all);
		$this->display();
	}

	public function addTerm()
	{
		$this->display();
	}

	public function add_term_post()
	{
		if (IS_POST) {
			if ($this->classify->create()) {
				if ($this->classify->add()) {
					$this->success('添加成功');
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->classify->getError());
			}
		}
	}

	public function edit_term()
	{
		$id = $_GET['id'];
		$info = $this->classify->find($id);
		$this->assign('info',$info);
		$this->display();
	}

	public function edit_term_post()
	{
		if (IS_POST) {
			if ($this->classify->create()) {
				if ($this->classify->save() !== false) {
					$this->success('修改成功');
				} else {
					$this->error('修改失败');
				}
			} else {
				$this->error($this->classify->getError());
			}
		}
	}

	public function delete_term()
	{
		$id = $_GET['id'];
		if ($this->classify->where("id = '$id'")->delete() !== false) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}
	public function index()
	{
		$test = S('test');
if(!$test){
     $test = '缓存信息';
     S('test',$test,300);
     echo $test.'-来自数据库';
}else{
     echo $test.'-来自memcached';
}
		$count = $this->users->where("user_type != 1")->cache(true)->count();
		var_dump($count);
		$page = $this->page($count,10);
		$all = $this->model->cache(true)->query("SELECT a.id AS uid,a.user_login AS user_login,b.name AS name FROM sp_users a LEFT JOIN sp_classify b ON a.term = b.id WHERE user_type != 1 LIMIT ".$page->firstRow.",".$page->listRows);
		$this->assign('page',$page->show('Admin'));
		$this->assign('all',$all);
		$this->display();
	}
	public function add_user()
	{
		$all = $this->classify->select();
		$this->assign('all',$all);
		$this->display();
	}

	public function add_user_post()
	{
		if (IS_POST) {
			if ($_POST[post]['user_pass'] != $_POST['re']) {
				$this->error('两次密码输入不一致');
			}
			$user = $_POST[post]['user_login'];
			$pass = $_POST[post]['user_pass'];
			$_POST['post']['user_pass'] = sp_password($_POST['post']['user_pass']);
			if ($this->users->create($_POST[post])) {
				if ($this->users->add($_POST[post])) {
					$url = "http://xmpp.mattservice.com:9090/plugins/userService/userservice?type=add&secret=pI5w95oM&username=".$user."&password=".$pass."&name=".$user."&email=".$user."&groups='user'";
		
					//fastcgi_finish_request();
					$ch = curl_init($url) ;
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
					curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
					curl_setopt($ch, CURLOPT_TIMEOUT,1000);
					$output = curl_exec($ch) ;
					$this->success('添加成功',U('teacher/index'));
				}
			} else {
				$this->error($this->users->getError());
			}
		}
	}

	public function edit_user()
	{
		$id = intval($_GET['id']);
		$all = $this->classify->select();
		$this->assign('all',$all);
		$info = $this->users->find($id);
		$this->assign('info',$info);
		$this->display();
	}

	public function edit_user_post()
	{
		if (IS_POST) {
			$_POST[post]['user_pass'] = sp_password($_POST[post]['user_pass']);
			if ($this->users->create($_POST[post])) {
				if ($this->users->save($_POST[post]) !== false) {
					$this->success('修改成功',U('teacher/index'));
				} else {
					$this->error('修改失败',U('teacher/index'));
				}
			}
		}
	}

	public function delete()
	{
		$id = intval($_GET['id']);
		if ($this->users->where("id = '$id'")->delete() !== false) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}

	//读取育婴师记录
	public function log()
	{
		$where = "WHERE id > 0 ";
		$phone = I('get.phone');
		$start_time = I('get.start_time');
		$end_time = I('get.end_time');
		if ($phone) {
			$where .= " AND uid IN(SELECT ID FROM matt_app.M_Teacher WHERE INSTR(`Telephone`,$phone))";
			$this->assign('phone',$phone);
		}
		if ($start_time && $end_time) {
			$where .= " AND add_time >= '$start_time' AND add_time <= '$end_time' ";
			$this->assign('start_time',$start_time);
			$this->assign('end_time',$end_time);
		} elseif ($start_time) {
			$where .= " AND add_time >= '$start_time'";
			$this->assign('start_time',$start_time);
		} elseif ($end_time) {
			$where .= " AND add_time <= '$end_time'";
			$this->assign('end_time',$end_time);
		}
		$count = $this->model->query("SELECT COUNT(*) AS num FROM matt_app.teacher_log ".$where."ORDER BY id DESC");
		$num = $count[0]['num'];
		$page = $this->page($num,15);
		$info = $this->model->query("SELECT id,action,uid,baby_id,type,info,add_time FROM matt_app.teacher_log ".$where."ORDER BY id DESC LIMIT ".$page->firstRow.",".$page->listRows);
		$this->assign('page',$page->show('Admin'));
		$this->assign('info',$info);
		$this->display();
	}

	//将育婴师log记录到出道excel
	public function daochu()
	{
		$where = "WHERE id > 0 ";
		$phone = I('get.phone');
		$start_time = I('get.start_time');
		$end_time = I('get.end_time');
		if ($phone) {
			$where .= " AND uid IN(SELECT ID FROM matt_app.M_Teacher WHERE INSTR(`Telephone`,$phone))";
		}
		if ($start_time && $end_time) {
			$where .= " AND add_time >= '$start_time' AND add_time <= '$end_time' ";
		} elseif ($start_time) {
			$where .= " AND add_time >= '$start_time'";
		} elseif ($end_time) {
			$where .= " AND add_time <= '$end_time'";
		}
		$all = $this->model->query("SELECT id,action,uid,baby_id,type,info,add_time FROM matt_app.teacher_log ".$where." ORDER BY id DESC");
		if (!$all) {
			$this->error('没有信息');
			exit;
		}
		foreach($all as $vo) {
			$result[] = array('phone'=>getPhone($vo['uid']),'action'=>logAction($vo['action']),'info'=>todoAction($vo['action'],$vo['baby_id'],0,$vo['info']),'add_time'=>$vo['add_time']);
		}
		define('IN_ECS', true);
		vendor('phpexcel.init');
		vendor('phpexcel.PHPExcel');
		vendor('phpexcel.PHPExcel');
		vendor('phpexcel.PHPExcel.Writer.Excel5');
		vendor('phpexcel.PHPExcel.Writer.Excel2007');
		//创建一个excel对象
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("ctos")
		        ->setLastModifiedBy("ctos")
		        ->setTitle("Office 2007 XLSX Test Document")
		        ->setSubject("Office 2007 XLSX Test Document")
		        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		        ->setKeywords("office 2007 openxml php")
		        ->setCategory("Test result file");

		//set width  
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);

		//设置行高度  
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);

		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);

		//set font size bold  
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		//设置水平居中  
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		//  
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');

		// set table header content  
		$objPHPExcel->setActiveSheetIndex(0)
		        ->setCellValue('A1', '育婴师操作记录')
		        ->setCellValue('A2', '手机号')
		        ->setCellValue('B2', '所做事件')
		        ->setCellValue('C2', '具体内容')
		        ->setCellValue('D2', '时间');

		// Miscellaneous glyphs, UTF-8  

		for ($i = 0; $i <= count($result); $i++) {
		    $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $result[$i]['phone']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $result[$i]['action']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), $result[$i]['info']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), $result[$i]['add_time']);
		}


		// Rename sheet  
		$objPHPExcel->getActiveSheet()->setTitle('事件');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet  
		$objPHPExcel->setActiveSheetIndex(0);
		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="操作记录.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

	//注册人数
	public function number()
	{
		$model = M();
		$where = " WHERE id > 0";
		$start_time = I('get.start_time');
		$end_time = I('get.end_time');
		$data = " WHERE id > 0 ";
		if ($start_time && $end_time) {
			$data .= " AND add_time >= '$start_time' AND add_time <= '$end_time'";
			$where .= " AND registtime >= '$start_time' AND registtime <= '$end_time' ";
			$this->assign('start_time',$start_time);
			$this->assign('end_time',$end_time);
		} elseif ($start_time) {
			$data .= " AND add_time >= '$start_time'";
			$where .= " AND registtime >= '$start_time'";
			$this->assign('start_time',$start_time);
		} elseif ($end_time) {
			$data .= " AND add_time >= '$start_time'";
			$where .= " AND registtime <= '$end_time'";
			$this->assign('end_time',$end_time);
		}
		$hits = $model->query("SELECT COUNT(*) AS num FROM sp_hits ".$data);//查询点击次数
		$this->assign('hits',$hits[0]['num']);
		$num = $model->query("SELECT COUNT(*) AS num FROM matt_app.users".$where);
		$count = $num[0]['num'];
		$this->assign('all',$count);
		$this->display();
	}


	//显示所有的成长册内容
	public function handbook()
	{
		$month = I('get.month');
		$day = I('get.day');
		$feed_type = I('get.feed_type');
		$order_money = I('get.order_money');
		$model = M();
		$where .= "WHERE a.ID > 0";
		$where .= " AND a.Month > 24 AND b.Title != '' AND a.Feed_Type = 3 AND a.Order_Money = 8866 ORDER BY a.Month,a.Day,a.From_Time ASC";
		/*if ($month) {
			$where .= " AND a.Month = '$month'";
			$this->assign('month',$month);
		}
		if ($day) {
			$where .= " AND a.Day = '$day'";
			$this->assign('day',$day);
		}
		if ($feed_type) {
			$where .= " AND a.Feed_Type = '$feed_type'";
		}
		if ($order_money) {
			$where .= " AND a.Order_Money = '$order_money'";
		}*//*
		$num = $model->query("SELECT COUNT(*) AS num FROM M_BabyDefaultHandbook a LEFT JOIN M_TestStore b ON a.TestStore_ID = b.ID ".$where);
		$count = $num[0]['num'];
		$page = $this->page($count,20);*/
		$result = $model->query("SELECT a.Month AS month,a.Day AS day,a.From_Time AS from_time,To_Time AS to_time,Feed_Type AS feed_type,Order_Money AS order_money,b.Title AS title,b.Media AS media FROM matt_app.M_BabyDefaultHandbook a LEFT JOIN matt_app.M_TestStore b ON a.TestStore_ID = b.ID ".$where);
		foreach($result as $vo) {
			if ($vo['feed_type'] == 0) {
				$name = '母乳喂养';
			} elseif ($vo['feed_type'] == 1) {
				$name = '混合喂养';
			} elseif ($vo['feed_type'] == 2) {
				$name = '人工喂养';
			} else {
				$name = '不分';
			}
			$data[] = array(
				'month'=>$vo['month'],
				'day'=>$vo['day'],
				'from_time'=>$vo['from_time'],
				'to_time'=>$vo['to_time'],
				'title'=>$vo['title'],
				'order_money'=>$vo['order_money'],
				'feed_type'=>$name,
			);
		}
		define('IN_ECS', true);
		vendor('phpexcel.init');
		vendor('phpexcel.PHPExcel');
		vendor('phpexcel.PHPExcel');
		vendor('phpexcel.PHPExcel.Writer.Excel5');
		vendor('phpexcel.PHPExcel.Writer.Excel2007');
		//创建一个excel对象
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("ctos")
		        ->setLastModifiedBy("ctos")
		        ->setTitle("Office 2007 XLSX Test Document")
		        ->setSubject("Office 2007 XLSX Test Document")
		        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		        ->setKeywords("office 2007 openxml php")
		        ->setCategory("Test result file");

		//set width  
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);

		//设置行高度  
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);

		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);

		//set font size bold  
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		//设置水平居中  
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		//  
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');

		// set table header content  
		$objPHPExcel->setActiveSheetIndex(0)
		        ->setCellValue('A1', '成长册')
		        ->setCellValue('A2', '月份')
		        ->setCellValue('B2', '天数')
		        ->setCellValue('C2', '喂养方式')
		        ->setCellValue('D2', '订单价格')
		        ->setCellValue('E2', '标题')
		        ->setCellValue('F2', '开始时间')
		        ->setCellValue('G2', '结束时间');

		// Miscellaneous glyphs, UTF-8  

		for ($i = 0; $i <= count($data); $i++) {
		    $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $data[$i]['month']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $data[$i]['day']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), $data[$i]['feed_type']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), $data[$i]['order_money']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 3), $data[$i]['title']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 3), $data[$i]['from_time']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 3), $data[$i]['to_time']);
		}


		// Rename sheet  
		$objPHPExcel->getActiveSheet()->setTitle('成长册');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet  
		$objPHPExcel->setActiveSheetIndex(0);
		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="成长册.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}


	//显示登陆日志
	public function loginLog()
	{
		$model = M();
		$phone = I('get.phone');
		$where = " WHERE a.id > 1";
		if ($phone) {
			$where .= " AND b.Telephone LIKE '%$phone%'";
			$this->assign('phone',$phone);
		}
		$num = $model->query("SELECT COUNT(*) AS num FROM matt_app.M_login a LEFT JOIN matt_app.users b ON a.uid = b.id ".$where ." ORDER BY a.id DESC");
		$count = $num[0]['num'];
		$page = $this->page($count,20);
		$result = $model->query("SELECT a.ip,a.add_time,b.Telephone FROM matt_app.M_login a LEFT JOIN matt_app.users b ON a.uid = b.id ".$where." ORDER BY a.id DESC LIMIT ".$page->firstRow ."," .$page->listRows);
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$result);
		$this->display();
	}

	//点图统计时间段注册人数
	public function numbers()
	{

		if ($_GET['begin'] && $_GET['end']) {
			$begin = I('get.begin');
			$end = I('get.end');
			if ($begin > $end) {
				$this->error("开始时间不能大于结束时间");
			}
			if (!$begin || !$end) {
				$this->error("请输入时间段信息");
			}
			if ($end > date("Y-m-d")) {
				$this->error('不能超过今天');
			}
			//最多允许查询40天
			if (strtotime($end) - strtotime($begin) >= 3456000) {
				$this->error("最多允许查询40天");
			}
			//算出差集
			$j=0;
			$model = M();
			$this->assign('begin',$begin);
			$this->assign('end',$end);
			for($i = strtotime($begin); $i <= strtotime($end); $i += 86400) 
			{
				$start = date("Y-m-d",$i)." 00:00:00";
				$to = date("Y-m-d",$i)." 23:59:59";
				$phone  = $model->query("SELECT Telephone FROM matt_app.users WHERE registtime >= '$start' AND registtime <= '$to'");
				$num = $model->query("SELECT COUNT(*) AS num FROM matt_app.users WHERE registtime >= '$start' AND registtime <= '$to'");
				$data[] = array(
						'day'=>date("Y-m-d",$i),
						'num'=>intval($num[0]['num']),
						'phone'=>$phone,
					);
				
			}
	        $this->assign('result',$data);
		}
        $this->display();
	}

	//查询手机号推荐的人
	public function hadtui()
	{
		if ($_GET['phone']) {
			$phone = $_GET['phone'];
			if (strlen($phone) != '11') {
				$this->error('请输入正确的手机号码');
			}
			$begin = I('get.begin');
			$end = I('get.end');
			if ($begin > $end) {
				$this->error("开始时间不能大于结束时间");
			}
			$this->assign();
			$model = M();
			$where = " WHERE pid = (SELECT uuid FROM matt_app.users WHERE Telephone = '$phone')";
			if ($begin) {
				$where .= " AND  registtime >= '$begin' ";
				$this->assign('begin',$begin);
			}
			$this->assign('phone',$phone);
			if ($end) {
				$where .= " AND registtime <= '$end'";
				$this->assign('end',$end);
			}
			$all = $model->query("SELECT COUNT(*) AS num FROM matt_app.users ".$where);
			$num = $all[0]['num'];
			$this->assign('num',$num);
			$page = $this->page($num,20);
			$result = $model->query("SELECT Telephone,registtime FROM matt_app.users ".$where." LIMIT ".$page->firstRow.",".$page->listRows);
			$this->assign('page',$page->show('Admin'));
			$this->assign('result',$result);

		}
		$this->display();
	}


	//显示所有的成长册内容
	public function results()
	{
		$model = M();
		$result = $model->query("SELECT Title FROM matt_app.M_TestStore WHERE Type = 4");
		
		define('IN_ECS', true);
		vendor('phpexcel.init');
		vendor('phpexcel.PHPExcel');
		vendor('phpexcel.PHPExcel');
		vendor('phpexcel.PHPExcel.Writer.Excel5');
		vendor('phpexcel.PHPExcel.Writer.Excel2007');
		//创建一个excel对象
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("ctos")
		        ->setLastModifiedBy("ctos")
		        ->setTitle("Office 2007 XLSX Test Document")
		        ->setSubject("Office 2007 XLSX Test Document")
		        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		        ->setKeywords("office 2007 openxml php")
		        ->setCategory("Test result file");

		//set width  
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);

		//设置行高度  
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);

		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);

		//set font size bold  
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
		//$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);

		//$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		//设置水平居中  
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		//  
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');

		// set table header content  
		$objPHPExcel->setActiveSheetIndex(0)
		        ->setCellValue('A1', '营养厨房');

		// Miscellaneous glyphs, UTF-8  

		for ($i = 0; $i <= count($result); $i++) {
		    $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $result[$i]['title']);
		}


		// Rename sheet  
		$objPHPExcel->getActiveSheet()->setTitle('营养厨房');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet  
		$objPHPExcel->setActiveSheetIndex(0);
		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="营养厨房.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

	//根据成长册信息查询内容
	public function selectHandbook()
	{
		$title = I('get.title');
		if ($title) {
			$model = M();
			$info = $model->query("SELECT ID,Title,Media FROM matt_app.M_TestStore WHERE INSTR(`Title`,'$title')");
			$this->assign('info',$info);
			$this->assign('title',$title);
		}
		$this->display();
	}

	//显示出某个成长册内容
	public function oneInfo()
	{
		$id = I('get.id');
		if ($id) {
			$model = M();
			$row = $model->query("SELECT ID,Title,Media,Content FROM matt_app.M_TestStore WHERE ID = $id");
			$this->assign('info',$row[0]);
			$this->display();
		}
	}

	//显示所有的聊天记录
	public function allChat()
	{
		$search = I('get.search');
		$start_time = I('get.start_time');
		$end_time = I('get.end_time');
		$type = I('get.type');   //1查询发送者  2为查询接收者
		$where = " WHERE messageID > 0 ";
		if ($search && $type == 1) {
			$where .= " AND INSTR(`fromJID`,'$search')";
		} elseif ($search && $type == 2) {
			$where .= " AND INSTR(`toJID`,'$search')";
		}
		$this->assign('search',$search);
		$this->assign('type',$type);
		//判断时间
		if ($start_time && $end_time) {
			$begin = strtotime($start_time).'000';
			$end = strtotime($end_time).'000';
			$where .= " AND sentDate >= $begin AND sentDate < $end";
		} elseif ($start_time) {
			$begin = strtotime($start_time).'000';
			$where .= " AND sentDate >= $begin ";
		} elseif ($end_time) {
			$end = strtotime($end_time).'000';
			$where .= " AND sentDate < $end";
		}
		$this->assign('start_time',$start_time);
		$this->assign('end_time',$end_time);
		$num = $this->users->todo("SELECT COUNT(*) AS num FROM ofMessageArchive" .$where);
		$count = $num[0]['num'];
		$page = $this->page($count,50);
		$result = $this->users->todo("SELECT messageID,fromJID,toJID,body,sentDate FROM ofMessageArchive ".$where." ORDER BY sentDate DESC");
		$this->assign('page',$page->show('Admin'));
		$this->assign('result',$result);
		$this->display();
	}
}