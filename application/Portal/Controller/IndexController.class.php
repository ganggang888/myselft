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
 * 首页
 */
class IndexController extends HomeBaseController {
    
    protected $classify = null;
    protected $users = null;
    protected $_xmpp = null;
    function _initialize() {
        parent::_initialize();
        $this->users = D("Common/Users");
        $this->classify = D("Common/Classify");
        $file_path = "http://app.mattservice.com/xmpp.txt";
        $file = fopen($file_path,"r");
        $xmpp_url = fgets($file);
        
        fclose($file);
        $this->_xmpp = $xmpp_url;
    }

    //首页
    /*public function index() {
        $this->display(":index");
    }*/

    //返回分类信息
    public function zhuanjia()
    {
        $all = $this->classify->select();
        $this->AjaxReturn(0,0,$all,0);
    }
    //推送
    public function tui()
    {
        header("Access-Control-Allow-Origin: http://zj.mattservice.com");
        extract($_POST);
        if (!$teacher || !$phone || !$message) {
            $this->AjaxReturn(0,0,'缺少参数',0);
        }
        $model = M();
        $info = $model->query("SELECT token FROM matt_app.users WHERE Telephone = '$phone'");
        
        if (!$info) {
            $this->AjaxReturn(0,0,'没有登陆',0);
        }
        $token = $info[0]['token'];
        $token_type = $info[0]['token_type'];
        // Put your device token here (without spaces):
        $deviceToken = $token;

        // Put your private key's passphrase here:密语
        $passphrase = 'zczczzcc';
        $teacher = explode('@', $teacher);
        $teacher = $teacher[0];
        $sql = "SELECT name FROM sp_classify WHERE id = (SELECT term FROM sp_users WHERE user_login = '$teacher')";
        $msgs = $model->query($sql);
        $names = $msgs[0]['name'];
        // Put your alert message here:
        $message = $names.'专家对'.$phone.'说：'.$message;

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

        if (!$fp) {
            $this->AjaxReturn(0,0,'无效的token',1);
        }

        $array = array(
            'jid'=>$teacher,
            'toid'=>$phone,
            'message'=>$message,
            'send_time'=>date('Y-m-d H:i:s'),
        );
        $model->execute("INSERT INTO matt_app.M_tui (jid,toid,message,send_time) VALUES ('$teacher','$phone','$message','$time')");
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
        $this->AjaxReturn(0,0,'成功',0);
    }

    //根据专家id判断专家类型
    public function get_term()
    {
        echo "麦忒专家";exit;
        $id = $_POST['id'];
        $sql = "SELECT name FROM sp_classify WHERE id = (SELECT term FROM sp_users WHERE user_login = '$id')";
        $model = M();
        $row = $model->query($sql);
        echo $row[0]['name'];exit;
    }
    
    //用户分类专家后聊天
    public function zhuanjia_list()
    {
        $id = $_POST['id'];
        //存点击分类记录
        $todo = $this->to_get($this->_xmpp.'plugins/presence/status',array('jid'=>'zhuanjia2@mattservice.com','type'=>'xml'));
        if (!$todo) {
            $this->AjaxReturn(0,'当前正在维护，请稍后再试',0,1);
        }
        $model = D('teacher_hits');
        $model->add(array('uid'=>$_SESSION['NOW_USER_ID'],'term_id'=>$id,'add_time'=>date('Y-m-d H:i:s')));
        //$model->execute("UPDATE sp_classify SET hits = hits+1 WHERE id = '$id'");
        if ($id == 4) {
            $prochatid[]=array("chatid"=>"appadvice","about"=>"育儿界泰斗朱家雄先生，《育婴师》 教材的编写人张静芬女士领衔麦忒强大的专家团队，为您和您的家庭提供专业的产后护理及科学的育儿方案。为宝宝的健康成长保驾护航。","img"=>"","name"=>"麦忒专家团队");
        } else {
            $prochatid[]=array("chatid"=>"zhuanjia2","about"=>"育儿界泰斗朱家雄先生，《育婴师》 教材的编写人张静芬女士领衔麦忒强大的专家团队，为您和您的家庭提供专业的产后护理及科学的育儿方案。为宝宝的健康成长保驾护航。","img"=>"","name"=>"麦忒专家团队");
        }
        
        $this->AjaxReturn(0,0,$prochatid,0);
        /*
        $id = $_POST['id'];
        $all = $this->users->where("term = '$id'")->select();
        //筛选出所有符合条目的记录进行xmpp在线认证
        foreach($all as $key=>$vo) {
            $user_login = $vo['user_login'].'@xmpp.mattservice.com';
            $data = array(
                'jid'   =>  $user_login,
                'type'  =>  'xml',
            );
            $todo = $this->to_get('http://xmpp.mattservice.com:9090/plugins/presence/status',$data);
            $return = simplexml_load_string($todo);
            if ($return && $return->status != 'Unavailable') {
                $datas[] = array("chatid"=>$vo['user_login'],"about"=>$vo['about'],"img"=>"","name"=>"麦忒专家团队");
            }
        }
        if (sizeof($datas) != 0) {
            $rand = array_rand($datas,1);
            $give[] = $datas[$rand];
            $this->AjaxReturn(0,0,$give,0);
        } else {
            $after = $this->users->where("user_type = '2'")->select();
            //如果当前分类没有在线的专家，随机分配到其他专家
            foreach($after as $key=>$vo) {
                $user_login = $vo['user_login'].'@xmpp.mattservice.com';
                $data = array(
                    'jid'   =>  $user_login,
                    'type'  =>  'xml',
                );
                $todo = $this->to_get('http://xmpp.mattservice.com:9090/plugins/presence/status',$data);
                $return = simplexml_load_string($todo);
                if ($return && $return->status != 'Unavailable') {
                    $infos[] = array("chatid"=>$vo['user_login'],"about"=>$vo['about'],"img"=>"","name"=>"麦忒专家团队");
                }
                
            }
            if (sizeof($infos) != 0) {
                    $rand = array_rand($infos,1);
                    $given[] = $infos[$rand];
                    $this->AjaxReturn(0,0,$given,0);
                } else {
                    $prochatid[]=array("chatid"=>"zj18917115885","about"=>"育儿界泰斗朱家雄先生，《育婴师》 教材的编写人张静芬女士领衔麦忒强大的专家团队，为您和您的家庭提供专业的产后护理及科学的育儿方案。为宝宝的健康成长保驾护航。","img"=>"","name"=>"麦忒专家团队");
            $this->AjaxReturn(0,0,$prochatid,0);
                }
        }
        */
    }


    //get请求
    public function to_get($url,$array)
    {
        $info = http_build_query($array);
        $all_url = $url.'?'.$info;
        $ch = curl_init($all_url) ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($ch, CURLOPT_TIMEOUT,5);//设置超时时间
        $output = curl_exec($ch) ;
        return $output;
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

    public function now_in()
    {
        $model = M();
        $num = $model->query("SELECT count(*) AS num FROM matt_app.session_status WHERE unix_timestamp(now()) - unix_timestamp(login_time) < 36000 AND `type` = 1");
        $count = $num[0]['num'];
        echo '<meta charset="utf-8" />';
        echo "当前共有<span style='color:#F00'>".$count."</span>人在线";

    }

    public function add_token()
    {
        $token = $_POST['token'];
        $model = D('token');
        $model->add(array('token'=>$token,'add_time'=>date('Y-m-d H:i:s')));
    }

    //查询你给谁推荐了
    public function istui()
    {
        header("Content-type: text/html; charset=utf-8");
        echo "<form method='post' action=''>";
        echo "<input type='text' name='phone'>";
        echo "<input type='submit'>";
        echo "</form>";
        if (IS_POST) {
            $model = M();
            $phone = $_POST['phone'];
            $row = $model->query("SELECT Telephone FROM matt_app.users WHERE pid = (SELECT uuid FROM matt_app.users WHERE Telephone = '$phone')");
            echo "你已经推荐了".sizeof($row)."人";
            echo "<br/>";
            foreach ($row as $key => $value) {
                echo $value['telephone']."<br/>";
            }
        }
    }

    //查询号码是否注册
    public function isadd()
    {
        header("Content-type: text/html; charset=utf-8");
        echo "<form method='post' action=''>";
        echo "<input type='text' name='phone'>";
        echo "<input type='submit'>";
        echo "</form>";
        if (IS_POST) {
            $model = M();
            $phone = $_POST['phone'];
            $info = $model->query("SELECT id FROM matt_app.users WHERE Telephone = '$phone'");
            if ($info) {
                echo "已经注册过了";
            } else {
                echo "未注册";
            }
        }
    }

    //获取时间线中选中的内容
    public function timeline()
    {
        $id = $_POST['id'];
        //$id = 682;
        $model = M();
        $row = $model->query("SELECT info FROM matt_app.M_BabyTimeLine WHERE ID = '$id'");
        $info = $row[0]['info'];
        if (!$info) {
            $this->AjaxReturn(0,'没有信息',0,1);
        }
        $info = unserialize($info);
        foreach($info as $vo) {
            $sql = "SELECT Title,ObsPointID FROM matt_app.M_TestStoreOptions WHERE ID = (SELECT TestStoreOptions_ID FROM matt_app.M_TestStoreObsPointScore WHERE ID = '$vo' )";
            $data = $model->query($sql);
            $ObsPointID = $data[0]['obspointid'];
            $name = $model->query("SELECT Title FROM matt_app.M_TestStoreObsPoint WHERE ID = '$ObsPointID'");
            $array[] = array('big_title'=>$name[0]['title'],'less_title'=>$data[0]['title']);
        }
        $this->AjaxReturn(0,0,$array,0);

    }

    //测试数据恢复
    public function huifu()
    {
        $model = M();
        $row = $model->query("SELECT * FROM matt_app.M_Teacher WHERE UserId NOT IN (SELECT id FROM matt_app.users)");
        var_dump($row);
        exit;
        foreach($row as $vo) {
            $phone = $vo['telephone'];
            $uid = $vo['userid'];
            $change[] = $model->execute("UPDATE matt_app.users SET Telephone = '$phone' WHERE id = '$uid'");
        }
        var_dump($change);
    }

    public function isrepeat()
    {
        $phone = $_POST['phone'];
        if (!$phone) {
            $this->AjaxReturn(0,0,0,1);
        }
        $model = M();
        $row = $model->query("SELECT Telephone FROM matt_app.users WHERE Telephone = '$phone'");
        if ($row) {
            $this->AjaxReturn(0,'手机号已经被注册过了',0,1);
        } else {
            $this->AjaxReturn(0,0,0,0);
        }
    }


    //查询推荐人数
    public function hadtui()
    {
        $model = M();
            $userid = $_SESSION['NOW_USER_ID'];
            //$userid = 128;
            $phone = $_POST['phone'];
            if ($phone) {
                $row = $model->query("SELECT Telephone,registtime FROM matt_app.users WHERE pid = (SELECT uuid FROM matt_app.users WHERE Telephone = '$phone')");
            } else {
                $row = $model->query("SELECT Telephone,registtime FROM matt_app.users WHERE pid = (SELECT uuid FROM matt_app.users WHERE id = (SELECT UserId FROM matt_app.M_Teacher WHERE ID = '$userid'))");
            }
            if ($row) {
                $this->AjaxReturn(0,0,$row,0);
            } else {
                $this->AjaxReturn(0,'快去推荐人注册吧',0,1);
            }
    }

    //显示是否停机维护
    public function maintain()
    {
        $array = array(
            array('type'=>1,'message'=>'正常访问'),
        );
        $type = 1;
        if ($type == 1) {
            $this->AjaxReturn(0,0,$array,0);
        } else {
            $this->AjaxReturn(0,0,$array,1);
        }
        
    }
}


