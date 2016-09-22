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
        	echo "GET OUT!!!";exit;
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
}