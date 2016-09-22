<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\HomeBaseController;
/**
 * 文章列表
*/
class ListController extends HomeBaseController {

	protected $api = "https://api.sms.mob.com";
	protected $appkey = "5e2bebe5f488";
	//文章内页
	public function index() {
		$term=sp_get_term($_GET['id']);
		$tplname=$term["list_tpl"];
    	$tplname=sp_get_apphome_tpl($tplname, "list");
    	$this->assign($term);
    	$this->assign('cat_id', intval($_GET['id']));
    	$this->display(":$tplname");
	}
	
	public function nav_index(){
		$navcatname="文章分类";
		$datas=sp_get_terms("field:term_id,name");
		$navrule=array(
				"action"=>"List/index",
				"param"=>array(
						"id"=>"term_id"
				),
				"label"=>"name");
		exit(sp_get_nav4admin($navcatname,$datas,$navrule));
		
	}

	public function cha()
	{
		$model = M();
		$row = $model->query("SELECT Telephone FROM matt_app.users WHERE pid = (SELECT uuid FROM matt_app.users WHERE Telephone = '15901917777') AND `registtime` >= '2015-09-07 00:00:00' AND `registtime` <= '2015-09-13 23:59:59'");
		var_dump($row);
	}
	//根据宝宝id返回当天成长册
	public function getbook()
	{
		$model = M();
		$babyid = 855;
		$baby_info = $model->query("SELECT * FROM matt_app.M_Baby WHERE Baby_ID = '$babyid'");
		$baby_date = $baby_info[0]['baby_date'];
		$baby_date = $_GET['date'];
		$month = intval((time() - strtotime($baby_date)) / 86400 / 30);
		$month += 1;
		if ($month == 0) {
			$month = 1;
		}
		$day = ((time() - strtotime($baby_date)) / 86400 % 30) + 1;
		var_dump($month);var_dump($day);
		$sql = "SELECT a.ID AS ID,a.timestamp AS timestamp,a.TestStore_ID AS TestStore_ID,a.From_Time AS From_Time,a.To_Time AS To_Time,a.Feed_Type AS Feed_Type,b.Title AS Title FROM matt_app.M_BabyDefaultHandbook a LEFT JOIN matt_app.M_TestStore b ON a.TestStore_ID = b.ID WHERE a.Month = '$month' AND a.Day = '$day' AND a.Feed_Type = (SELECT Feed_Type FROM matt_app.M_Baby WHERE Baby_ID = '$babyid') AND a.Order_Money = (SELECT Order_Money FROM matt_app.M_Baby WHERE Baby_ID = '$babyid') AND a.Is_Deleted = 0 AND b.Title IS NOT NULL ORDER BY a.From_Time ASC";
		echo $sql;exit;
		$row = $model->query($sql);
		var_dump($row);
	}
	//获取验证时间
	public function getTime()
	{
		if (!$_SESSION['SAFE_CODE']) {
	        echo 0;exit;
	    } elseif ($_SESSION['SAFE_CODE'] && empty($_SESSION['chekType'])) {
	        $_SESSION['chekType'] = '1';
	        echo 60;exit;
	    } elseif ($_SESSION['chekType'] == '1') {
	        $_SESSION['chekType'] = '2';
	        echo 120;exit;
	    } elseif ($_SESSION['chekType'] =='2') {
	        $_SESSION['chekType'] = '5';
	        echo 180;exit;
	    } elseif ($_SESSION['chekType'] == '2') {
	        $_SESSION['chekType'] = '7';
	        echo 360;exit;
	    } elseif ($_SESSION['chekType'] == '7') {
	        echo 500;exit;
	    } else {
	    	echo 100;exit;
	    }
	}
	//发送验证码
	public function sendCode()
	{
		if (IS_POST) {
			$phone = $_POST['phone'];
			$response = $this->postRequest( $this->api . '/sms/sendmsg', array(
			    'appkey' => $this->appkey,
			    'phone' => $phone,
			    'zone' => '86',
			) );
			$info = json_decode($response);
			echo $info->status;
			exit;
		}
	}

	///验证验证码是否正确
	public function checkCode()
	{
		header("Access-Control-Allow-Origin: http://m.matteducation.com");
		if (IS_POST) {
			$phone = $_POST['phone'];
			$code = $_POST['code'];
			$response = $this->postRequest( $this->api . '/sms/checkcode', array(
			    'appkey' => $this->appkey,
			    'phone' => $phone,
			    'zone' => '86',
				'code' => $code,
			) );
			$info = json_decode($response);
			echo $info->status;
			exit;
		}
	}
	private function postRequest( $api, array $params = array(), $timeout = 30 ) {
	    $ch = curl_init();
	    curl_setopt( $ch, CURLOPT_URL, $api );
	    // 以返回的形式接收信息
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	    // 设置为POST方式
	    curl_setopt( $ch, CURLOPT_POST, 1 );
	    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
	    // 不验证https证书
	    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
	    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
	    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
	        'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
	        'Accept: application/json',
	    ) ); 
	    // 发送数据
	    $response = curl_exec( $ch );
	    // 不要忘记释放资源
	    curl_close( $ch );
	    return $response;
	}

	public function findAll()
	{
		$todo = "13678059490,13480096575,15873372282,13469009483,18373329249,18842319282,18570655073,18273395857,13796476597,18600093897,13247355581,13315339767,15863209767,15266443811,13315336645,13407509148,1832612****,1896379****,18761296375,13851649620,18373320876,18003315599,18273388505,152********,13469525753,15990349293,15594923249,15691788215,18673131787,15502582496,15776261015,13507336172,18066838277,18191555990,15251896579,18030842879,18010689184,18358526550,18921842906,15751005907,13735107053,1323105****,18706837890,13776660204,18180968336,15830622719,13099838096,18232720593,15275820077,15700355807,18857378797,15265850077,18551082205,13739445733,15183567633,18397652232,18740071261,15606255192,13786792818,18843488646,15590427801,15939885054,18227692493,18635651201,17756095541,13486690889,18712824498,1362619****,18630512717,17799155629,13381338263,15502939310,13018585043,18657336933,18754381776,18045730163,18731142387,18815087256,15057687653,18604686544,15890784545,15943420087,15601600412,1367180****,15391145656,13825879513,18090751081,13782517583,18169084361,15841959793,137********,13278048506,18006087755,13778059490,13246355582,13833276482,15844447547,13589142285,18655126320,18743065856,13716376770,15136240635,15639472286,15031434446,18295013165,18187013685,15837363682,15017398876,15766401867,13115684569,18353941132,18629088845,18292881366,13689815753,15859887363,18066832877,13974023365,18588800403,18757397145,187********,15982187868,15255302151,18231391127,18207329391,13907480849,13407518857,18711001399,1539509****,13673478938,13849397626,13574871831,15201879575,186********,15208274565,13787064091,15580064128,13390893929,18684790919,13681839675,15996929775,13519279135,15221008168,13142311497,18902291323,15802643091,13097933331,13466919924,13775517414,18781619003,15881525275,15231645322,138********,15575102737,18966552733,15378982538,13659935224,18883787381,15179356692,18814036713,18323082223,18323082229,18323082225,15218790234,13331332500,18626035142,18609517631,18883138651,18375701178,18375701155,18723373518,18325029399,18875120158,18875118358,18875219078,18223592227,18323037722,18223333048,18223333493,15730325325,13618308911,13618307955,15730357770,18325005799,18875219128,18323037775,18323037711,18202375432,13883880370,15730178994,18223333149,18223333049,13618308928,18325028599,18325005866,18875118355,18875218978,18875219116,18323037772,18202375490,18202375417,15730337648,15730337634,15730337664,15730204509,15730204506,15730099134,15730142084,15730465837,15730465839,15730465850,15730465851,15730465852,15730465853,15730465854,15730465855,15730465856,15730465857,15730465860,15730465859,15730465862,15730465863,15730465864,15730203330,15730203339,15730203377,15730203338,15730203336,15730203335,15730203331,18223077558,18723377731,13628451484,15023240492,15023240624,15086960506,15086960021,15223138210,15223137980,15223138112,15223138126,15223363403,15223363419,15223363489,15223361416,15223360428,15223366499,15223368446,15223369348,15223377846,15223377849,15223376046,15223376048,15223369949,15223370409,15223357737,15223357717,15223375872,15223358774,15223366204,15223366474,15223367549,15223369604,15223369654,15223378642,15223379040,15223370535,15223370466,15730088101,15730088263,18375856166,18375856161,18223109394,18223109146,18223084768,18223084928,18423192232,18423192269,15213326785,15213326793,15213327751,15213353130,15223138531,15223363412,15223364476,15223370749,15223368797,15223368697,15223357691,15223357625,15223376082,15223375701,15223375697,15223378225,15223360349,15223358748,15223365242,15223366494,15223369864,15223377744,15223370420,15223378742,15223378304,15223361024,15223363542,15223363814,15223367894,15223370010,18200602083,15218727412,13268651214,18786167647,15382805685,13386969810,13136297526,13259746303,15260659904,15824065508,13692248597,13400221380,13842738978,13266154592,13025554034,13590386733,13537158377,15308631242,13620988074,13530912471,13480612360,15115025531,15914625981,18824242350,13926973142,13433880605,18718934512,13428719206,15594600094,15919213281,18475362295,15718124872,13480737395,13113940680,13002649412,15667017451,15818193754,15664861427,13838484119,13046797141,15503325003,17854274285,13569975251,18723060053,13706112980,15131875295,13764923781,15069819176,15189526600,18332568889,15038126858,18702521702,15638508751,18273722030,18108461559,13617676985,15939767557,17865961537,15927737885,15069081115,13457065243,18996059717,13880897563,13921013736,15652678414,18403558434,18739352373,13212693749,14707796153,18336353825,18482001065,15040395400,18230515208,18273410945,15093126907,15732921952,14781702813,13053583605,18273720710,15216157840,18830715907,13363779206,18234886243,15138030932,18663315326,13350279147,18819440782,13346305777,13293050785,15676745126,18331100515,13713898262,18344427102,18820297371,15989753124,13590270187,18144930199,14727737400,15889664587,13174059776,15012939879,13556849507,13590295438,13418780263,13728461006,13433870655,13069341461,17806672944,13215274547,13937149806,15675566275,13280213663,13186872741,13210784909,13085790980,18266009122,15099814304,15589027944,13714688794,13267881456,15916281423,13169516554,18231673798,15774548581,13827075421,15779684385,13016101037,18738595376,18738175850,15622261972,15539735362,18037106671,18720219166,18776768849,18463728243,18130232589,15520682808,15735970954,15033075832,18335192300,18703541367,14718110873,18035403563,17835392310,15556401321,15721593796,13881959259,13275928740,17805976282,18134411670,15558092856,15009667525,13126196725,18003313687,14521430965,15131181184,13480617147,13921282564,13723015052,15617609723,18734696784,18263515963,16216128197,17662510506,18775825090,13511813469,15105959260,15638578820,15274527873,15855707675,17865176899,18397963691,15564809773,18317381948,13352464216,18896816612,18560757209,15851434455,18362983093,15737342401,13559553309,18820792835,18684950979,18838934232,15755526337,18659175854,13196332435,13271788538,13102924733,17707091850,18234076536,15034281433,18392310624,18070829420,13863278637,13838033604,15215246966,15870831820";
		$result = explode(',',$todo);
		$model = M();
		foreach ($result as $vo) {
			$info = $model->query("SELECT id FROM matt_app.users WHERE Telephone = '$vo'");
			if ($info) {
				$pid = $model->query("SELECT Telephone FROM matt_app.users WHERE uuid = (SELECT pid FROM matt_app.users WHERE Telephone = '$vo')");
			}
			
			if ($info && $pid) {
				$array[] = array(
					'phone'=>$vo,
					'info'=>'注册',
					'pid'=>$pid[0]['telephone'],
				);
			} elseif ($info && !$pid) {
				$array[] = array(
					'phone'=>$vo,
					'info'=>'注册',
					'pid'=>'无',
				);
			} elseif (!$info) {
				$array[] = array(
					'phone'=>$vo,
					'info'=>'没注册',
					'pid'=>'无',
				);
			}
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
		        ->setCellValue('A1', '注册情况')
		        ->setCellValue('A2', '手机号')
		        ->setCellValue('B2', '是否注册')
		        ->setCellValue('C2', '推荐人');

		// Miscellaneous glyphs, UTF-8  

		for ($i = 0; $i <= count($array); $i++) {
		    $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $array[$i]['phone']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $array[$i]['info']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), $array[$i]['pid']);
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
	
}
