<?php
namespace Portal\Controller;
use Common\Controller\HomeBaseController;
class FriendController extends HomeBaseController
{

	protected $friend = null;
	protected $userid = null;
	function _initialize() {
        parent::_initialize();
        $this->friend = D('Common/friend');
        $this->userid = $_SESSION['NOW_USER_ID'];
        if (empty($this->userid)) {
        	//echo "GET OUT!!!";exit;
        }
    }

    /**
	 * 向某个人发送请求成为他的育婴师或者雇主
	 * @param $phone
     * @param $type   1为添加育婴师请求 2为添加雇主请求
     * url :/info/index.php?g=portal&m=friend&a=sendStatus
	 */
    public function sendStatus()
    {
    	extract($_POST);
        $row = $this->friend->sendMessage($this->userid,$phone,$type);
        if ($row['status']) {
            $this->AjaxReturn(0,'成功',0,0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,0);
        }
    }

    /**
     * 别人向你发送的请求列表
     * url :/info/index.php?g=portal&m=friend&a=statusList
     */
    public function statusList()
    {
        extract($_POST);
        $model = M();
        $row = $this->friend->sendToMe($this->userid);
        if ($row['status']) {
            $this->AjaxReturn(0,0,$row['msg'],0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }

    /**
     * 接受或者拒绝别人要求的添加请求
     * url :/info/index.php?g=portal&m=friend&a=returnStatus
     * @param $phone
     * @param $status 1为答应 2为拒绝
     */
    public function returnStatus()
    {
        extract($_POST);
        $row = $this->friend->checkSend($this->userid,$phone,$status);
        if ($row['status']) {
            $this->AjaxReturn(0,0,$row['msg'],0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }

    /**
     * 查看我的育婴师或者雇主信息
     * url :/info/index.php?g=portal&m=friend&a=myPeople
     */
    public function myPeople()
    {
        $row = $this->friend->lookPeople($this->userid);
        if ($row['status']) {
            foreach ($row['msg'] as $vo) {
                if ($vo['type'] == '1') {
                    $vo['type'] = '是你的育婴师';
                } elseif ($vo['type'] == '2') {
                    $vo['type'] = '是你的雇主';
                }
                $data[] = $vo;
            }
            $this->AjaxReturn(0,0,$data,0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
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

    //批量注册用户
    public function res()
    {
        $result = M('needs')->where("registtime > '2016-10-13'")->field(array('Telephone','password','registtime'))->order("id DESC")->select();
        array_map(function($vo){
            $this->postRequest("http://app.mattservice.com/?keys=JYTgTUN5o8t4",array('phone'=>$vo['telephone'],'password1'=>$vo['password'],'registtime'=>$vo['registtime']));
        },$result);

    }

    /**
     * 发起一个post请求到指定接口
     * 
     * @param string $api 请求的接口
     * @param array $params post参数
     * @param int $timeout 超时时间
     * @return string 请求结果
     */
    function postRequest( $api, array $params = array(), $timeout = 30 ) {
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
}