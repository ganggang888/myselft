<?php
namespace Common\Model;
use Common\Model\CommonModel;
class FriendModel extends CommonModel
{
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('friendid', 'require', '姓名不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
			array('groupid', 'require', '邮箱不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
	);

	//验证某人是否为你的育婴师或者为雇主
	public function checkUser(int $userid ,int $id)
	{
		$row = $this->query("SELECT id FROM matt_app.friend WHERE uid = $userid AND friendid = $id AND status = 1");
		if ($row) {
			return "已经建立了关系";
		}
		$result = $this->query("SELECT id FROM matt_app.friend WHERE uid = $id AND friendid = $userid AND status = 1");
		if ($result) {
			return "已经建立了关系";
		}
		$info = $this->query("SELECT id FROM matt_app.friend WHERE uid = $userid AND friendid = $id AND status = 0");
		if ($info) {
			return "您已经向他发送了好友请求，等待确认中";
		}
		return "ok";
	}

	//根据手机号获取到用户的userid
	public function getUserid(int $phone)
	{
		$row = $this->query("SELECT ID FROM matt_app.M_Teacher WHERE Telephone = $phone");
		if ($row) {
			return $row[0]['id'];
		} else {
			return false;
		}
	}

	//发送请求
	public function sendMessage(int $userid,int $phone,int $type)
	{
		$uid = $this->getUserid($phone);
		$result = $this->checkUser($userid, $uid);
		if ($result != 'ok') {
			return $this->returnInfo('',$result);
		} else {
			$info = $this->execute("INSERT INTO matt_app.friend (uid,friendid,type,add_time) VALUES ($userid,$uid,$type,now())");
			if ($info) {
				return $this->returnInfo('1',"您的请求已经成功发出");
			} else {
				return $this->returnInfo('','出错，请联系程序员');
			}
		}
	}

	//接受或者拒绝别人发出的请求
	public function checkSend(int $userid,int $phone,int $status)
	{
		$uid = $this->getUserid($phone);//获取他人uid
		$row = $this->query("SELECT id FROM matt_app.friend WHERE uid = $uid AND friendid = $userid AND status = 1");
		if ($row) {
			return $this->returnInfo('','出错，他并没有向你发送请求');
		}
		$info = $this->execute("UPDATE matt_app.friend SET status = $status WHERE uid = $uid AND friendid = $userid AND status = 0 ");
		if ($info) {
			return $this->returnInfo('1','成功');
		} else {
			return $this->returnInfo('','失败了！');
		}
	}

	//显示出我的育婴师或者雇主信息
	public function lookPeople(int $userid)
	{
		$data = $this->query("SELECT a.uid,a.friendid,a.type,a.status,a.add_time,b.Telephone FROM matt_app.friend a LEFT JOIN matt_app.M_Teacher b ON a.uid = b.ID WHERE a.friendid = $userid AND a.status = 1");
		foreach ($data as $vo) {
			if ($vo['type'] == 1) {
				$vo['type'] = 2;
			} elseif ($vo['type'] == 2) {
				$vo['type'] = 1;
			}
			$result[] = $vo;
		}
		$info = $this->query("SELECT a.uid,a.friendid,a.type,a.status,a.add_time,b.Telephone FROM matt_app.friend a LEFT JOIN matt_app.M_Teacher b ON a.uid = b.ID WHERE a.uid = $userid AND a.status = 1");
		if ($data && $info) {
			return $this->returnInfo('1',array_merge($result,$info));
		} elseif ($data) {
			return $this->returnInfo('1',$result);
		} elseif ($info) {
			return $this->returnInfo('1',$info);
		} else {
			return $this->returnInfo('','暂时没有信息哦');
		}
	}

	//发送给我的请求列表
	public function sendToMe(int $userid)
	{
		$row = $this->query("SELECT a.type,b.Telephone,a.add_time FROM matt_app.friend a LEFT JOIN matt_app.M_Teacher b ON a.uid = b.ID WHERE a.friendid = $userid AND a.status = 0");
		foreach ($row as $vo) {
			if ($vo['type'] == 1) {
				$vo['type'] = '请求添加你为他的育婴师';
			} elseif ($vo['type'] == 2) {
				$vo['type'] = '请求添加你为他的雇主';
			}
			$data[] = $vo;
		}
		if ($row) {
			return $this->returnInfo('1',$data);
		} else {
			return $this->returnInfo('','暂时没有消息');
		}
	}

	//返回信息组成数组
	private function returnInfo(int $status ,$msg)
	{
		$data = array(
			'status'=>$status,
			'msg'=>$msg
		);
		return $data;
	}
}