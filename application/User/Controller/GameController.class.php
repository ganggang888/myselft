<?php
//游戏
namespace User\Controller;
use Common\Controller\AdminbaseController;
class GameController extends AdminbaseController
{
	protected $game;//实例化model
        
        //分类
        protected $shareType = array(
            array('id'=>1,'name'=>'推荐食谱'),
            array('id'=>0,'name'=>'推荐游戏'),
        );
        
        //菜谱以及游戏分类
        protected $storeTerm = array(
            array('id'=>2,'name'=>'游戏库'),
            array('id'=>4,'name'=>'菜谱库'),
        );
	function _initialize() {
		parent::_initialize();
		$this->game = D("Common/SubjectGame");
	}

    /*
     * 推荐首页
     * @access public
     * @param int $month 月份
     * @param int $level 级别
     * @param int $type 类型：1为食谱，2为游戏 
     * @return view:user.game.index
     */
	public function index()
	{
		$month = I('get.month');
		$level = I('get.level');
                $type = I('get.type');
		$where = array();
		$month ? $where['month'] = $month : '';
		$level ? $where['level'] = $level : '';
                $type ? $where['type'] = $type : $where['type'] = 0;
		$count = $this->game->where($where)->count();
		$page = $this->page($count,10);
		$result = $this->game->where($where)->field(array('id','month','level','admin_id','nr','add_time'))->order("add_time DESC")->limit($page->firstRow,$page->listRows)->select();
		foreach ($result as $vo) {
			$vo['nr'] = $this->game->savedTestStore($vo['nr']);
			$data[] = $vo;
		}
                $shareType = $this->shareType;
		$this->assign('page',$page->show('Admin'));
		$this->assign(compact('month','level','type','data','shareType'));
		$this->display();
	}

	//添加
	public function add()
	{
		if (IS_POST) {
			$_POST['nr'] = serialize(I('post.test_id'));
			if ($this->game->create() !== false) {
				if ($this->game->add($_POST) !== false) {
					$_POST['type'] = 0 ? $this->success('添加成功',U('Game/index')) : $this->success('添加成功',U('Game/index',array('type'=>1)));
				} else {
					$this->error('添加失败',U('Game/index'));
				}
			} else {
				$this->error($this->game->getError());
			}
		}
                $this->assign('storeTerm',$this->storeTerm);
                $this->assign('shareType',$this->shareType);
		$this->assign('month',$this->game->allMonth());
		$this->assign('level',$this->game->allLevel());
		$this->display();
	}

	//修改
	public function edit()
	{
		if (IS_POST) {
			$_POST['nr'] = serialize(I('post.test_id'));
			if ($this->game->create() !== false) {
				if ($this->game->where("id = %d",array($_POST['id']))->save($_POST) !== false) {
					$_POST['type'] = 0 ? $this->success('修改成功',U('Game/index')) : $this->success('修改成功',U('Game/index',array('type'=>1)));
				} else {
					$this->error('修改失败',U('Game/index'));
				}
			} else {
				$this->error($this->game->getError());
			}
		}
		$id = I('get.id');
		$info = $this->game->where("id = %d",array($id))->field(array('id','month','level','type','admin_id','about','nr','add_time'))->find();
		$info['nr'] = $this->game->savedTestStore($info['nr']);
		$this->assign('month',$this->game->allMonth());
		$this->assign('level',$this->game->allLevel());
                $this->assign('storeTerm',$this->storeTerm);
                $this->assign('shareType',$this->shareType);
		$this->assign('info',$info);
		$this->display();
	}

	//检索列表
	public function checkTestStore()
	{
		$name = I('get.name');
		$month = I('get.month');
                $type = I('get.type');
		$result = $this->game->selectTestStoreInfo($name,$month,$type);
		$this->assign('result',$result);
		$this->display();
	}
	//删除
	public function delete()
	{
		$id = I('get.id');
		$this->game->where("id = %d",array($id))->delete() ? $this->success('删除成功',U('Game/index')) : $this->error('删除失败',U('Game/index'));
	}

	//宝宝测评历史记录
	public function babyHistory()
	{
		$where = "";
		$begin = I('get.begin');
		$end = I('get.end');
		$type = I('get.type');
		$Baby_Date = I('get.Baby_Date');
		$Baby_Name = I('get.Baby_Name');
		$Baby_Name ? $where .= " AND B.Baby_Name LIKE '%$Baby_Name%'" : '';
		$Baby_Date ? $where .= " AND B.Baby_Date = '$Baby_Date'" : '';
		$type ? $where .= " AND A.type = $type" : '';
		if ($begin && $end) {
			$where .= " AND A.add_time >= '$begin' && A.add_time < '$end'";
		} elseif ($begin && !$end) {
			$where .= " AND A.add_time >= '$begin'";
		} elseif (!$begin && $end) {
			$where .= " AND A.add_time < '$end'";
		}

		$model = M();
		$fields = "A.id,A.month,A.score,A.weight,A.header,A.height,A.bmi,A.total,A.add_time,B.Baby_Name,B.Baby_Date";
		$where ? $where = preg_replace('/AND/','WHERE',$where,1)  : '';
		$num = $model->query("SELECT COUNT(*) AS num FROM matt_chat.sp_examhistory A INNER JOIN matt_app.M_Baby B ON A.babyId = B.Baby_ID $where");
		$count = $num[0]['num'];
		$page = $this->page($count,15);
		$result = $model->query("SELECT $fields FROM matt_chat.sp_examhistory A INNER JOIN matt_app.M_Baby B ON A.babyId = B.Baby_ID $where ORDER BY A.id DESC LIMIT ".$page->firstRow.",".$page->listRows);
		$this->assign(compact('Baby_Name','Baby_Date','begin','end','result','page','type'));
		$this->display();
	}

	//查看宝宝该条所做的测试题详细信息
	public function babyDo(int $id)
	{
		$info = M('examhistory')->where("id=%d",array($id))->getField('answer');
		$data = $this->game->getNames($info);
		$this->assign(compact('data','id'));
		$this->display();
	}

	//获取测评结果所需要的图表数据
	public function getPicHeight(int $id)
	{
		$info = M('examhistory')->where("id=%d",array($id))->find();
		$message = $this->game->getAllMessage($info['babyid'],$info['month'],$info['weight'],$info['height'],$info['header'],$info['bmi']);

		//得到数据后开始处理数据，生成对应的数据结构图
		//1.身长数据处理
		$msg = $message['msg'];
		$height_p3 = json_encode(array_column($msg[0],'p3'));
		$height_p25 = json_encode(array_column($msg[0],'p25'));
		$height_p75 = json_encode(array_column($msg[0],'p75'));
		$height_p97 = json_encode(array_column($msg[0],'p97'));
		$height_x = json_encode($message['xy'][0]['x']);
		$height_y = json_encode($message['xy'][0]['y']);
		$height = $info['height'];
		$height_month = $info['month'];
		$talk = $message['talk'];
		$heightResult = $message['height'][0]['getSay'];
		$allMonth = json_encode(array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36));
		$this->assign(compact('height_p3','height_p25','height_p75','height_p97','height_x','height_y','allMonth','height','height_month','talk','heightResult'));

		//2.开始获取体重数据
		$weight_p3 = json_encode(array_column($msg[1],'p3'));
		$weight_p25 = json_encode(array_column($msg[1],'p25'));
		$weight_p75 = json_encode(array_column($msg[1],'p75'));
		$weight_p97 = json_encode(array_column($msg[1],'p97'));
		$weight = $info['weight'];
		$weightResult = $message['weight'][0]['getSay'];
		$this->assign(compact('weight_p3','weight_p25','weight_p75','weight_p97','weight','weightResult','id'));

		//3.开始获取BMI数据
		$bmi_p3 = json_encode(array_column($msg[2],'p3'));
		$bmi_p15 = json_encode(array_column($msg[2],'p15'));
		$bmi_p85 = json_encode(array_column($msg[2],'p85'));
		$bmi_p95 = json_encode(array_column($msg[2],'p95'));
		$bmi = $info['bmi'];
		$bmiResult = $message['bmi'][0]['getSay'];
		$this->assign(compact('bmi_p3','bmi_p15','bmi_p85','bmi_p95','bmi','bmiResult'));

		//4.开始获取头围数据
		$header_one = json_encode(array_column($msg[3],'one'));
		$header_two = json_encode(array_column($msg[3],'two'));
		$header_three = json_encode(array_column($msg[3],'three'));
		$header_four = json_encode(array_column($msg[3],'four'));
		$header_five = json_encode(array_column($msg[3],'five'));
		$header = $info['header'];
		$headerResult = $message['header'][0]['getSay'];
		$this->assign(compact('header_one','header_two','header_three','header_four','header_five','header','headerResult'));
		$this->display();
	}

}