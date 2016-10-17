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
class PageController extends HomeBaseController{
	protected $users = null;
	function _initialize() {
        parent::_initialize();
        $this->users = D("Common/Users");
    }
	public function index() {
		$id=$_GET['id'];
		$content=sp_sql_page($id);
		$this->assign($content);
		$smeta=json_decode($content['smeta'],true);
		$tplname=isset($smeta['template'])?$smeta['template']:"";
		
		$tplname=sp_get_apphome_tpl($tplname, "page");
		
		$this->display(":$tplname");
	}
	
	public function nav_index(){
		$navcatname="页面";
		$datas=sp_sql_pages("field:id,post_title;");
		$navrule=array(
				"action"=>"Page/index",
				"param"=>array(
						"id"=>"id"
				),
				"label"=>"post_title");
		exit( sp_get_nav4admin($navcatname,$datas,$navrule) );
	}

	public function hits()
	{
		$this->users->hits(GetIP(),time(),600);
		header("location: http://a.app.qq.com/o/simple.jsp?pkgname=com.example.mattrobotphone");
	}

	//倒入openfire用户
	public function addUsers()
	{
		$model = M();
		$info = $model->query("SELECT Telephone FROM matt_app.users ORDER BY id DESC");
		foreach ($info as $vo) {
			$row = $this->addOpenfireUsers($vo['telephone'],'','','');
			if ($row == false) {
				$data[] = $vo['telephone'];
			}
		}
		var_dump($data);
	}

	private function addOpenfireUsers($userid,$plainpwd,$uname,$email){
		error_reporting(0);
		$url = "http://xmpp.mattservice.com:9090/plugins/userService/userservice?type=add&secret=pI5w95oM&username=".$userid."&password=3d3bae6a-729c-3649-7728-57439a6773cc&name=".$uname."&email=".$email."&groups='user'";
		
		//fastcgi_finish_request();
		$ch = curl_init($url) ;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
		curl_setopt($ch, CURLOPT_TIMEOUT,1000);
		$output = curl_exec($ch) ;
		
		/*
		$curl = new curl_multi();
		$curl->setUrlList($url);
		$output=$curl->execute();
		*/
	    if(strstr($output,'OK')) {
	        return true;
	    }else{
	        return false;
	    }
	    fclose($f);
	
	}

	public function toLogin($uid)
	{
		session_start();
		$model = M();
		$result = $model->query("SELECT Telephone AS phone FROM matt_app.users WHERE id = $uid");
		if (!$result) {
			$this->AjaxReturn(0,'用户信息不存在',0,1);
		}
		$phone = $result[0]['phone'];
		$info = array('id'=>$uid,'first_name'=>'','last_name'=>'','Telephone'=>$phone,'cross_country'=>'0','country'=>'CHN','login_adapter'=>'default');
		$info = json_encode($info);
		$info = json_decode($info);
		$array = array(
			'adminlogin'=>1,
			'ADMIN_ID'=>1,
			'name'=>'admin',
			'front_end'=>array(
				'user'=>$info
			),
		);
		$_SESSION = $array;
		$teacher = $model->query("SELECT ID AS id FROM matt_app.M_Teacher WHERE UserId = $uid");
		$_SESSION['NOW_USER_ID'] = $teacher[0]['id'];
		//添加teacher表里面的uid到session

		//重置token
		$token = $this->uuid();
		$csrf = md5($token."woshishuaige");
		$select = $model->query("SELECT id FROM sp_login WHERE uid = $uid");
		$logins = D('login');
		if ($select) {
			$logins->where(array('uid'=>$uid))->save(array('token'=>$token,'csrf'=>$csrf));
		} else {
			$logins->add(array('uid'=>$uid,'token'=>$token,'csrf'=>$csrf));
		}
		echo "<script>window.location.href='http://app.mattservice.com/api/single/self-code.php';</script>";
		$this->AjaxReturn(0,0,$_COOKIE,0);
	}
	private function uuid() {
        $charid = md5(uniqid(mt_rand(), true));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
               .substr($charid, 8, 4).$hyphen
               .substr($charid,12, 4).$hyphen
               .substr($charid,16, 4).$hyphen
               .substr($charid,20,12);
       return $uuid;
    }
	//自动登录
	public function login()
	{
		if (IS_POST) {
			$csrf = $_POST['csrf'];
			$model = M();
			$row = $model->query("SELECT uid FROM sp_login WHERE csrf = '$csrf'");
			if ($row) {
				$this->toLogin($row[0]['uid']);
			} else {
				$this->AjaxReturn(0,'csrf错误',0,1);
			}
		}
	}


	//协助安卓table view登陆
	public function loginAndroid()
	{
		if ($_GET['keys'] == 'f62efd6ee04ada1817af799895e2b256') {
			$phone = $_GET['phone'];
			$model = M();
			$row = $model->query("SELECT id FROM matt_app.users WHERE Telephone = $phone");
			$this->toLogin($row[0]['id']);
		}
	}
	//ajax返回
    function AjaxReturn($errorCode,$errorMessage,$list,$result)
    {
        header("Content-type: text/html; charset=utf-8");
        $arr = array(
            'errorCode'     => $errorCode,
            'errorMessage'  => $errorMessage,
            'list'          => $list,
            'result'        => $result,
        );
        echo json_encode($arr);exit();
    }
    //存储发送的图片
    public function saveImg()
    {
    	if (IS_POST) {
    		extract($_POST);
    		if ($_POST['key'] != md5("shanghaimaite".date("Y-m-d"))) {
    			echo "GET OUT";exit;
    		}
		 	if (!$type) {
		 		$this->AjaxReturn(0,'缺少参数',0,1);
		 	}
		 	$uuid = $this->single->getUuid($this->userid);
		 	if($_FILES["upload"]["error"] == 0){
	            $path = "uploads".'/'.$_FILES["uploadmedia"]["name"];
                move_uploaded_file($_FILES["uploadmedia"]["tmp_name"],$path);
	        }
	        $thePath = "http://app.mattservice.com/info/".$path;
	        $model = M();
	        $time = date("Y-m-d H:i:s");
	        $row = $model->query("INSERT INTO matt_app.storage (uid,path,type,add_time) VALUES (999999,'$thePath',$type,'$time')");
	        $this->AjaxReturn(0,0,$thePath,0);
    	}
    }
    //苹果推送
    public function send_message()
    {
    	if ($_GET['key'] == 'zengganggangshishuaige') {
    		$model = M();/*
    		$info = $model->query("SELECT token FROM matt_app.users WHERE Telephone = '15800666222'");
    		$row = $this->tui($info[0]['token'],'您的成长册有新的内容推荐了哦！');var_dump($row);exit;*/
    		$result = $model->query("SELECT token FROM matt_app.users WHERE token != ''");
    		foreach ($result as $vo) {
    			$this->tui($vo['token'],'您的成长册有新的内容推荐了哦！');
    		}
    	}
    	
    }


    private function tui($token ,$message)
    {
        
        // Put your device token here (without spaces):
        $deviceToken = $token;

        // Put your private key's passphrase here:密语
        $passphrase = 'zczczzcc';
        // Put your alert message here:
        ////////////////////////////////////////////////////////////////////////////////
        $ctx = stream_context_create();
        if ($token_type == 1) {
            stream_context_set_option($ctx, 'ssl', 'local_cert', 'ckpad.pem');
        } else {
            stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
        }
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
            );

        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);
        return $msg;
    }

    public function tests()
    {
    	$row = $this->users->todo("SELECT * FROM ofMessageArchive LIMIT 0,5");
    	var_dump($row);
    }

    public function sendCode()
    {
    	vendor('dayu.TopSdk');
    	$code = getRandCode(4);
    	$product = '';
    	$array = array('code'=>"$code",'product'=>$product);
    	date_default_timezone_set('Asia/Shanghai'); 
    	$c = new \TopClient;
    	$c ->appkey = "23479105" ;
	    $c ->secretKey = "0b6a660ec53512587a6e239206d6a6c5" ;
	    $req = new \AlibabaAliqinFcSmsNumSendRequest;
	    $req ->setExtend( "" );
	    $req ->setSmsType( "normal" );
	    $req ->setSmsFreeSignName( "麦忒教育科技" );
	    $req ->setSmsParam(json_encode($array));
	    $req ->setRecNum( "18816978522" );
	    $req ->setSmsTemplateCode( "SMS_17545040" );
	    $resp = $c ->execute( $req );
	    var_dump($resp);
	    var_dump($resp->code);
    }
}