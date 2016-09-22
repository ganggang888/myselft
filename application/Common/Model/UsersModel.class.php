<?php
namespace Common\Model;
use Common\Model\CommonModel;
class UsersModel extends CommonModel
{
	
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('user_login', 'require', '用户名称不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT  ),
			array('user_pass', 'require', '密码不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT ),
			//array('user_login', 'require', '用户名称不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
			array('user_pass', 'require', '密码不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
			array('user_login','','用户名已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_login字段是否唯一
			//array('user_email','','邮箱帐号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_email字段是否唯一
			//array('user_email','email','邮箱格式不正确！',0,'',CommonModel:: MODEL_BOTH ), // 验证user_email字段格式是否正确
	);
	
	//用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	function mGetDate() {
		return date('Y-m-d H:i:s');
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
		
		if(!empty($data['user_pass']) && strlen($data['user_pass'])<25){
			$data['user_pass']=sp_password($data['user_pass']);
		}
	}

	//记录点击次数与ip，同时限制同一ip十分钟算一次
	public function hits($ip,$begin,$times)
	{

		$row = $this->db->query("SELECT id FROM sp_hits WHERE ip = '$ip' AND ($begin - UNIX_TIMESTAMP(`add_time`)) < $times ORDER BY add_time DESC limit 0,1");
		if ($row) {
			return false;
		} else {
			$now = date("Y-m-d H:i:s");
			$this->db->execute("INSERT INTO sp_hits (ip,add_time) VALUES ('$ip','$now')");
			return true;
		}
	} 

	public function todo($sql)
	{
		$rows = $this->db(1,OTHER_DB)->query($sql);
		return $rows;
	}

	//独特的UUID
	function uuid() {
        $charid = md5(uniqid(mt_rand(), true));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
               .substr($charid, 8, 4).$hyphen
               .substr($charid,12, 4).$hyphen
               .substr($charid,16, 4).$hyphen
               .substr($charid,20,12);
        return $uuid;
	}

	//添加一个新的用户
	public function addUsers()
	{
		$phone = $this->getPhone();
		$password = md5("4920616d206c6f6e656c79");
		$uuid = $this->uuid();
		$time = date("Y-m-d H:i:s");
		$this->db->startTrans();
		$add_user = $this->db->execute("INSERT INTO matt_app.users (Telephone,password,role,uuid,is_active,is_locked,ip,first_time_login,registtime,csrf_token) VALUES ($phone,'$password','staff','$uuid','is_active','NO','192.168.1.1','NO','$time','ganggang')"); //用户表信息

		$last_uid = $this->db->query("SELECT MAX(id) AS id FROM matt_app.users");//获取uid
		$last_uid = $last_uid[0]['id'];
		$add_teacher = $this->db->execute("INSERT INTO matt_app.M_Teacher (UserId,Telephone,CreateDate) VALUES ('$last_uid',$phone,'$time')");   //育婴师表信息添加
		$add_role = $this->db->execute("INSERT INTO matt_app.acl_user_role_country (user_id,role_id,country_id) VALUES ($last_uid,1015,6)");  //给权限
		if ($add_user && $add_teacher && $add_role) {
			$this->db->commit();
			self::addOpenfireUsers($phone,$password1,'','');
			return $phone;
		} else {
			$this->db->rollback();
			return false;
		}
	}

	private function addOpenfireUsers($userid, $plainpwd, $uname, $email)
    {
        error_reporting(0);
        $url = "http://xmpp.mattservice.com:9090/plugins/userService/userservice?type=add&secret=pI5w95oM&username=".$userid."&password=3d3bae6a-729c-3649-7728-57439a6773cc&name=" . $uname . "&email=" . $email . "&groups='user'";

        //fastcgi_finish_request();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        $output = curl_exec($ch);

        /*
        $curl = new curl_multi();
        $curl->setUrlList($url);
        $output=$curl->execute();
        */
        if (strstr($output, 'OK')) {
            return true;
        } else {
            return true;
        }
        fclose($f);

    }

	//生成一个不重复的手机号
	public function getPhone()
	{
		do {
			$phone = '0'.mt_rand(1111111111,9999999999);
			$row = $this->db->query("SELECT id FROM matt_app.users WHERE Telephone = $phone");
		} while (!empty($row));
		return $phone;
	}

}

