<?php
namespace Common\Model;
use Common\Model\CommonModel;
class ClassifyModel extends CommonModel
{
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('name', 'require', '分类名称不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
	);

	//向某个人发送分享宝宝或关联宝宝请求
	public function sendMessage(int $userid,int $phone,int $babyid,int $type)
	{
		$peopleId = $this->getUserid($phone);
		$row = $this->query("SELECT id FROM matt_app.M_baby_share WHERE babyId = $babyid AND type = $type AND status = 0 AND uid = $userid AND pid = $peopleId");
		if ($row) {
			return $this->returnInfo('','请等待对方同意');
		}
		$row = $this->query("SELECT id FROM matt_app.M_baby_share WHERE babyId = $babyid AND type = $type AND status = 1 AND uid = $userid AND pid = $peopleId");
		if ($row) {
			return $this->returnInfo('','对方的宝宝已经与您有关系了哦');
		}
		$info = $this->execute("INSERT INTO matt_app.M_baby_share (babyId,uid,pid,type,addtime) VALUES ($babyid,$userid,$peopleId,$type,now())");
		if ($info) {
			return $this->returnInfo('1','成功');
		} else {
			return $this->returnInfo('','失败了哦');
		}
	}

	//显示别人发送给你的宝宝关联或者分享请求
	public function shareList(int $userid,int $type)
	{
		$row = $this->query("SELECT a.id,a.addtime,a.type,b.Telephone,c.Baby_Name,c.Baby_Date FROM matt_app.M_baby_share a LEFT JOIN matt_app.M_Teacher b ON a.uid = b.ID LEFT JOIN matt_app.M_Baby c ON a.babyId = c.Baby_ID WHERE a.pid = $userid AND a.type = $type AND a.status = 0");

		if ($row) {
			foreach ($row as $vo) {
				$vo['type'] = ($vo['type'] == 1 ? '请求与你分享' : '请求与你关联');
				$data[] = $vo;
			}
			return $this->returnInfo('1',$data);
		} else {
			return $this->returnInfo('','没有人哦');
		}
	}

	//同意或者拒绝别人发送给你的宝宝关联或者分享请求
	public function agreeOrNo(int $userid,int $phone,int $id,int $status)
	{
		$peopleId = $this->getUserid($phone);
		$row = $this->query("SELECT id,uid,pid FROM matt_app.M_baby_share WHERE uid = $peopleId AND pid = $userid AND id = $id AND status = 0");
		if (!$row) {
			return $this->returnInfo('','出错了哦');
		}
		$result = $this->execute("UPDATE matt_app.M_baby_share SET status = $status,addtime = now() WHERE uid = $peopleId AND pid = $userid AND id = $id");
		if ($result) {
			return $this->returnInfo('1','成功');
		} else {
			return $this->returnInfo('','失败');
		}
	}


	//别人分享或者关联给你的宝宝
	public function getShareBaby(int $userid,int $type)
	{
		$row = $this->query("SELECT a.addtime,b.Telephone,c.Baby_Name,c.Baby_ID FROM matt_app.M_baby_share a LEFT JOIN matt_app.M_Teacher b ON a.uid = b.ID LEFT JOIN matt_app.M_Baby c ON a.babyId = c.Baby_ID WHERE a.pid = $userid AND a.type = $type AND a.status = 1");
		if ($row) {
			return $this->returnInfo('1',$row);
		} else {
			return $this->returnInfo('','没有信息哦');
		}
	}

	//解除别人分享给你的宝宝
	public function removeOtherBaby(int $userid,int $phone,int $babyid)
	{
		$peopleId = $this->getUserid($phone);
		$row = $this->execute("UPDATE matt_app.M_baby_share SET status = 3,addtime = now() WHERE pid = $userid AND uid = $peopleId AND babyId = $babyid");
		if ($row) {
			return $this->returnInfo('1','解除关系成功');
		} else {
			return $this->returnInfo('0','传入的信息不正确');
		}
	}

	//你分享或者关联给别人的宝宝
	public function youShareBaby(int $userid,int $type)
	{
		$row = $this->query("SELECT a.addtime,b.Telephone,c.Baby_Name,c.Baby_ID FROM matt_app.M_baby_share a LEFT JOIN matt_app.M_Teacher b ON a.pid = b.ID LEFT JOIN matt_app.M_Baby c ON a.babyId = c.Baby_ID WHERE a.uid = $userid AND a.type = $type AND a.status = 1");
		if ($row) {
			return $this->returnInfo('1',$row);
		} else {
			return $this->returnInfo('','没有信息哦');
		}
	}

	//解除你自己分享或者关联给别人的宝宝
	public function removeSelfBaby(int $userid,int $phone,int $babyid)
	{
		$peopleId = $this->getUserid($phone);
		$row = $this->execute("UPDATE matt_app.M_baby_share SET status = 3,addtime = now() WHERE uid = $userid AND pid = $peopleId AND babyId = $babyid");
		if ($row) {
			return $this->returnInfo('1','解除关系成功');
		} else {
			return $this->returnInfo('0','传入的信息不正确');
		}

	}

	//育婴师评价
	public function message(int $userid,int $phone,$score = '',$content)
	{
		$peopleId = $this->getUserid($phone);
		$row = $this->query("SELECT id FROM matt_app.Message WHERE uid = $userid AND pid = $peopleId");
		if ($row) {
			$result = $this->execute("UPDATE matt_app.Message SET score = '$score',content='$content',addtime = now() WHERE uid = $userid AND pid = $peopleId");
			if ($result) {
				return $this->returnInfo('1','修改成功');
			} else {
				return $this->returnInfo('','修改失败');
			}
		} else {
			$result = $this->execute("INSERT INTO matt_app.Message (uid,pid,score,content,addtime) VALUES ($userid,$peopleId,'$score','$content',now())");
			if ($result) {
				return $this->returnInfo('1','成功');
			} else {
				return $this->returnInfo('','失败');
			}
		}
	}

	//根据某个人的手机号码查询所有人对他的评价
	public function scoreForPhone(int $phone)
	{
		$peopleId = $this->getUserid($phone);
		$row = $this->query("SELECT a.score,a.content,a.addtime,b.Telephone FROM matt_app.Message a LEFT JOIN matt_app.M_Teacher b ON a.uid = b.ID WHERE a.pid = $peopleId");
		if ($row) {
			return $this->returnInfo('1',$row);
		} else {
			return $this->returnInfo('','暂时还没有评价哦');
		}
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
