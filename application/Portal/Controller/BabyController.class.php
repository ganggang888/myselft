<?php
namespace Portal\Controller;
use Common\Controller\HomeBaseController;
class BabyController extends HomeBaseController
{

    protected $baby = null;
    protected $userid = null;
    function _initialize() {
        parent::_initialize();
        $this->baby = D('Common/classify');
        $this->userid = $_SESSION['NOW_USER_ID'];
        if (empty($this->userid)) {
            //echo "GET OUT!!!";exit;
        }
    }

    /**
     * 将宝宝分享或者关联给一个人,发送请求
     * url:/info/index.php?g=portal&m=baby&a=sendStatus
     * @param $phone    手机号
     * @param $babyid   宝宝id
     * @param $type     1为分享 2为关联
     */
    public function sendStatus()
    {
        extract($_POST);
        if (!$phone || !$babyid || !$type) {
            $this->AjaxReturn(0,'缺少参数',0,1);
        }
        $row = $this->baby->sendMessage($this->userid,$phone,$this->DoMcrypt($babyid,0),$type);
        if ($row['status']) {
            $this->AjaxReturn(0,$row['msg'],0,0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }

    /**
     * 显示别人发送给你的请求列表
     * url:/info/index.php?g=portal&m=baby&a=sendList
     */
    public function sendList()
    {
        extract($_POST);
        $row = $this->baby->shareList($this->userid,$type);
        if ($row['status']) {
            $this->AjaxReturn(0,0,$row['msg'],0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }
    /**
     * 同意或者拒绝别人发送给你的请求
     * url:/info/index.php?g=portal&m=baby&a=giveStatus
     * @param $phone    手机号
     * @param $id       请求id
     * @param $status   1为同意 2为拒绝
     */
    public function giveStatus()
    {
        extract($_POST);
        if (!$phone || !$id || !$status) {
            $this->AjaxReturn(0,'缺少参数',0,1);
        }
        $row = $this->baby->agreeOrNo($this->userid,$phone,$id,$status);
        if ($row['status']) {
            $this->AjaxReturn(0,$row['msg'],0,0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }


    /**
     * 别人分享或者关联给你的宝宝
     * url:/info/index.php?g=portal&m=baby&a=someoneShareForYou
     * @param $type  1为分享  2为关联
     */
    public function someoneShareForYou()
    {
        $type = $_POST['type'];
        if (empty($type)) {
            $this->AjaxReturn(0,'缺少参数',0,1);
        }
        $row = $this->baby->getShareBaby($this->userid,$type);
        if ($row['status']) {
            $this->AjaxReturn(0,0,$row['msg'],0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }


    /**
     * 解除别人分享或者关联给你的宝宝
     * url:/info/index.php?g=portal&m=baby&a=removeOther
     * @param $phone  手机号
     * @param $babyid 宝宝id
     */
    public function removeOther()
    {
        extract($_POST);
        if (!$phone || !$babyid) {
            $this->AjaxReturn(0,'缺少参数',0,1);
        }
        $row = $this->baby->removeOtherBaby($this->userid,$phone,$this->DoMcrypt($babyid,0));
        if ($row['status']) {
            $this->AjaxReturn(0,$row['msg'],0,0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }

    /**
     * 查看你分享或者关联给别人的宝宝
     * url:/info/index.php?g=portal&m=baby&a=youShareList
     * @param $type 1为分享 2为关联
     */
    public function youShareList()
    {
        extract($_POST);
        if (!$type) {
            $this->AjaxReturn(0,'缺少参数',0,1);
        }
        $row = $this->baby->youShareBaby($this->userid,$type);
        if ($row['status']) {
            $this->AjaxReturn(0,0,$row['msg'],0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }

    /**
     * 解除你自己分享或者关联给别人的宝宝
     * url:/info/index.php?g=portal&m=baby&a=removeSelfShare
     * @param $phone  手机号
     * @param $babyid 宝宝id
     */
    public function removeSelfShare()
    {
        extract($_POST);
        if (!$phone || !$babyid) {
            $this->AjaxReturn(0,'缺少参数',0,1);
        }
        $row = $this->baby->removeSelfBaby($this->userid,$phone,$babyid);
        if ($row['status']) {
            $this->AjaxReturn(0,$row['msg'],0,0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }

    /**
     * 评价一个育婴师
     * url:/info/index.php?g=portal&m=baby&a=teacherScore
     * @param $phone  手机号
     * @param $score 积分
     * @param $content 内容
     */
    public function teacherScore()
    {
        extract($_POST);
        if (!$phone || !$score || !$content) {
            $this->AjaxReturn(0,'缺少参数',0,1);
        }
        $row = $this->baby->message($this->userid,$phone,$score,$content);
        if ($row['status']) {
            $this->AjaxReturn(0,0,$row['msg'],0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }

    /**
     * 读取育婴师的评价
     * url:/info/index.php?g=portal&m=baby&a=readScore
     * @param $phone  手机号
     */
    public function readScore()
    {
        extract($_POST);
        if (!$phone) {
            $this->AjaxReturn(0,'缺少参数',0,0);
        }
        $row = $this->baby->scoreForPhone($phone);
        if ($row['status']) {
            $this->AjaxReturn(0,0,$row['msg'],0);
        } else {
            $this->AjaxReturn(0,$row['msg'],0,1);
        }
    }

    /**
     * 发送验证码
     * url:/info/index.php?g=portal&m=baby&a=sendCode
     * @param $phone  手机号
     * @param $key 宝宝id
     */
    public function sendCode()
    {
        $info = md5("woshishuaige".date("Y-m-d"));
        if ($_POST['key'] == $info) {
            $api = 'https://api.sms.mob.com';
            $appkey = '5e2bebe5f488';
             
            $phone = $_POST['phone'];
            // 发送验证码
            $response = $this->postRequest( $api . '/sms/sendmsg', array(
                'appkey' => $appkey,
                'phone' => $phone,
                'zone' => '86',
            ) );
            $info = json_decode($response);
            if ($info->status == 200) {
                $this->AjaxReturn(0,'成功',0,0);
            } else {
                $this->AjaxReturn(0,'发送失败',0,1);
            }
            echo $info;exit;
        }
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


    /*
     * 加密
     * @param $str 要加密的字符串
     * @param $action 执行的动作,1:加密;0:解密
     */
    public function DoMcrypt($str,$action){
        $key = ">.t;GHl=oV/_6V!(aHPj#;>t=uKn)j;Z";
        $cipher = MCRYPT_RIJNDAEL_128;
        $mode = MCRYPT_MODE_ECB;
        //$iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher, $mode),MCRYPT_RAND);
        if($action ==1){
            $value = mcrypt_encrypt($cipher, $key, $str, $mode);
            return base64_encode($value);
        }else if ($action == 0){
            $str = base64_decode($str);
            return trim(mcrypt_decrypt($cipher, $key, $str, $mode));
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
}