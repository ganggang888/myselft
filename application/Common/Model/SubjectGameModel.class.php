<?php
namespace Common\Model;
use Common\Model\CommonModel;
class SubjectGameModel extends CommonModel
{
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('month', 'require', '月份不能为空', 1, 'regex', 3),
			array('level', 'require', '级别不能为空', 1, 'regex', 3),
			array('nr', 'require', '内容不能为空', 1, 'regex', 3),
	);

	//操作一些数据
	protected function _before_insert(&$data,$option)
	{
		$data['add_time'] = date("Y-m-d H:i:s");
		$data['admin_id'] = get_current_admin_id();
	}

	//根据数组

	//获取所有分类月份数组
	public function allMonth()
	{
		for($i=1;$i<=36;$i++) {
			$arr[] = $i;
		}
		return $arr;
	}

	//所有level级别数组
	public function allLevel()
	{
		$array = array(1,2,3);
		return $array;
	}
        
	/*
         * 根据条目名称对库进行模糊查询
         * @access public
         * @param string $name 名称
         * @param int $month 月份
         * @param int $type 类型
         * @return array
         */
        public function selectTestStoreInfo($name,$month,$type)
	{
		$where = "";
		$name ? $where .= " AND INSTR(`Title`,'$name') " : '';
		$month ? $where .= " AND Month = $month " : '';
                $type ? $where .= " AND Type = $type" : '';
		$where ? $where = preg_replace('/AND/','WHERE',$where,1) : '';
		//var_dump("SELECT ID,Title FROM matt_app.M_TestStore WHERE $where");exit;
		$info = $this->query("SELECT ID,Title FROM matt_app.M_TestStore $where");
		return $info;
	}

	//根据数据库存储的数据将nr变成指定的数组
	public function savedTestStore($info)
	{
		$row = unserialize($info);
		$i = implode(',',$row);
		//var_dump("SELECT ID,Title FROM matt_app.M_TestStore WHERE ID IN($i)");
		$info = $this->query("SELECT ID,Title FROM matt_app.M_TestStore WHERE ID IN($i) ORDER BY FIELD(`ID`,$i)");
		return $info;
	}

	//根据题目ID给题目加上名称
	public function getNames($info = "")
	{
		$info = unserialize($info);
		$i = 1;
		$result = array_map(function($v)use($i){$v['listorder'] = $i;$i++;return $v;},$info);
		$i = '';
		//var_dump($result);exit;
		foreach ($result as $vo) {
			$i .= $vo['id'].',';
		}
		$i = substr($i,0,strlen($i)-1);

		//开始查询所有出现的名称
		$i ? $find = $this->query("SELECT id,name,unscramble FROM matt_chat.sp_subject_info WHERE id IN ($i)") : '';

		//var_dump($find);exit;
		$info = array();
		foreach ($find as $value) {
			//var_dump($value);exit;
			$info[$value['id']] = $value;
		}
		foreach ($result as $vo) {

			$vo['name'] = $info[$vo['id']]['name'];
			$vo['score'] ? '' : $vo['unscramble'] = $info[$vo['id']]['unscramble'];
			//$vo['unscramble'] = $info[$vo['id']]['name'];
			$data[] = $vo;
		}
		return $data;
	}

	//获取所有测评图表数据
	public function getAllMessage($babyId,$month,$weight,$height,$header,$bmi)
	{
		//先获取折现数据
		$allMessage = $this->query("SELECT month,P3,P25,P75,P97,type,sex FROM sp_graphical ORDER BY id ASC");

		//获取宝宝基本信息
		$babyInfo = $this->query("SELECT Baby_Sex,Baby_Date FROM matt_app.M_Baby WHERE Baby_ID = $babyId");
		$sex = $babyInfo['baby_sex'];
		!$sex ? $sex = 1 : '';
		//开始对数据进行处理，整理出各个曲线图所需要的数据
		foreach ($allMessage as $vo) {

			//type为3的键值替换
			if ($vo['type'] == 3) {
                $vo['p15'] = $vo['p25'];
                unset($vo['p25']);
                $vo['p85'] = $vo['p75'];
                unset($vo['p75']);
                $vo['p95'] = $vo['p97'];
                unset($vo['p97']);
            }
            if ($vo['type'] == 1) {
                $vo['term_name'] = '身长';
            } elseif ($vo['type'] == 2) {
                $vo['term_name'] = '体重';
            } elseif ($vo['type'] == 3) {
                $vo['term_name'] = 'BMI';
            }
            if ($sex == $vo['sex']) {
            	$data[$vo['type']][] = $vo;
            }
		}
		$array = array();
		foreach ($data as $key=>$vo) {
			$xy[] = $this->produceXY($month,$key);
			$array[$key-1] = $vo;
		}

		//头围信息写入
		$xy[] = $this->produceXY($month,4);
		//单独列出头围信息
		$headerMsg = $this->babyHeader($sex);


		$array[] = $headerMsg;
		$sex == 0 ? $sex = 1 : '';

		//开始组合数据
		$heights[] = array('number'=>$height,'month'=>$month,'getSay'=>$this->getSay($weight,$height,$header,$bmi,$sex,$month,1,$data[1][$month]));
		$weights[] = array('number'=>$vo->weight,'month'=>$vo->month,'getSay'=>$this->getSay($weight,$height,$header,$bmi,$sex,$month,2,$data[2][$month]));
		$headers[] = array('number'=>$vo->header,'month'=>$vo->month,'getSay'=>$this->getSay($weight,$height,$header,$bmi,$sex,$month,4,$headerMsg[$month]));
		$bmis[] = array('number'=>$vo->bmi,'month'=>$vo->month,'getSay'=>$this->getSay($weight,$height,$header,$bmi,$sex,$month,3,$data[3][$month]));
		$foodList = self::returnFoods($month);

		//显示的区间范围数据
		$talk = array(
			array('type'=>1,'sad'=>"            <3% 异常            3%-25% 中等偏下            25%-75% 中等            75%-97%中等偏上            >97% 超常"),
			array('type'=>2,'sad'=>"            <3% 异常            3%-25% 中等偏下            25%-75% 中等            75%-97%中等偏上            >97% 超常"),
			array('type'=>3,'sad'=>"            <3% 严重消瘦            3%-15% 消瘦            15%-85% 正常            85%-95%超重            >95%肥胖"),
			array('type'=>4,'sad'=>''),
			//array('type'=>4,'sad'=>'-2SD<头围<2SD 脑发育正常   头围<-2SD 有脑发育不良的可能   头围<-3SD 脑发育不良   头围>2SD有脑积水或其它疾病的可能   头围>3SD 有脑积水或其它疾病'),
		);
		return array('msg'=>$array,'height'=>$heights,'weight'=>$weights,'header'=>$headers,'bmi'=>$bmis,'xy'=>$xy,'headerKey'=>array('one'=>'-3SD','two'=>'-2SD','three'=>'均数','four'=>'2SD','five'=>'3SD'),'talk'=>$talk,'foodList'=>$foodList);
	}


	//根据月龄、分类纵坐标和横坐标
	private function produceXY($month,$type)
	{
		switch ($type) {
			case 1:
				if ($month >=0 && $month <= 6) {
					$x = array(0,1,2,3,4,5,6);
					$y = array(40,50,55,60,65,70,75);
				} elseif ($month >= 7 && $month <= 12) {
					$x = array(7,8,9,10,11,12);
					$y = array(60,65,70,75,80,85);
				} elseif ($month >= 13 && $month <= 18) {
					$x = array(13,14,15,16,17,18);
					$y = array(65,70,75,80,85,90);
				} elseif ($month >= 19 && $month <= 24) {
					$x = array(19,20,21,22,23,24);
					$y = array(70,75,80,85,90,95);
				} elseif ($month >= 25 && $month <= 30) {
					$x = array(25,26,27,28,29,30);
					$y = array(75,80,85,90,95,100);
				} elseif ($month >= 31 && $month <= 36) {
					$x = array(31,32,33,34,35,36);
					$y = array(80,85,90,95,100,105);
				}
				# code...
				break;
			case 2:
				if ($month >= 0 && $month <= 6) {
					$x = array(0,1,2,3,4,5,6);
					$y = array(2,4,6,8,10);
				} elseif ($month >= 7 && $month <= 12) {
					$x = array(7,8,9,10,11,12);
					$y = array(6,8,10,12,14);
				} elseif ($month >= 13 && $month <= 18) {
					$x = array(13,14,15,16,17,18);
					$y = array(6,8,10,12,14);
				} elseif ($month >= 19 && $month <= 24) {
					$x = array(19,20,21,22,23,24);
					$y = array(8,10,12,14,16);
				} elseif ($month >= 25 && $month <= 30) {
					$x = array(25,26,27,28,29,30);
					$y = array(8,10,12,14,16,18);
				} elseif ($month >= 31 && $month <= 36) {
					$x = array(31,32,33,34,35,36);
					$y = array(10,12,14,16,18);
				}
				break;
			case 3:
				if ($month >= 0 && $month <= 6) {
					$x = array(0,1,2,3,4,5,6);
					$y = array(9,11,13,15,17,19,21);
				} elseif ($month >= 7 && $month <= 12) {
					$x = array(7,8,9,10,11,12);
					$y = array(13,15,17,19,21);
				} elseif ($month >= 13 && $month <= 18) {
					$x = array(13,14,15,16,17,18);
					$y = array(13,15,17,19,21);
				} elseif ($month >= 19 && $month <= 24) {
					$x = array(19,20,21,22,23,24);
					$y = array(11,13,15,17,19);
				} elseif ($month >= 25 && $month <= 30) {
					$x = array(25,26,27,28,29,30);
					$y = array(11,13,15,17,19);
				} elseif ($month >= 31 && $month <= 36) {
					$x = array(31,32,33,34,35,36);
					$y = array(11,13,15,17,19);
				}
				break;
			case 4:
				if ($month >= 0 && $month <= 6) {
					$x = array(0,1,2,3,4,5,6);
					$y = array(30,35,40,45,50);
				} elseif ($month >= 7 && $month <= 12) {
					$x = array(7,8,9,10,11,12);
					$y = array(35,40,45,50,55);
				} elseif ($month >= 13 && $month <= 18) {
					$x = array(13,14,15,16,17,18);
					$y = array(40,45,50,55,60);
				} elseif ($month >= 19 && $month <= 24) {
					$x = array(19,20,21,22,23,24);
					$y = array(40,45,50,55,60);
				} elseif ($month >= 25 && $month <= 30) {
					$x = array(25,26,27,28,29,30);
					$y = array(40,45,50,55,60);
				} elseif ($month >= 31 && $month <= 36) {
					$x = array(31,32,33,34,35,36);
					$y = array(40,45,50,55,60);
				}
				break;
			default:
				# code...
				break;
		}

		return array('x'=>$x,'y'=>$y);

	}

	//根据所测数据生成对应的话
	private function getSay($weight,$height,$header,$bmi,$sex,$month,$type,$array)
	{
		switch ($type) {
			case 1:
				if ($height < $array['p3'] ) {
					$message = "宝宝本次身长测评百分位小于P3。在同龄宝宝中，宝宝的身长处于异常水平，请带宝宝到正规的儿科医院做综合的检查和评估！";
				} elseif ($height >= $array['p3'] && $height < $array['p25']) {
					$message = "宝宝本次身长测评百分位处于P3-P25。在同龄宝宝中，宝宝的身长处于中等偏下水平，请结合BMI指标判断是否需要干预！";
				} elseif ($height >= $array['p25'] && $height < $array['p75']) {
					$message = "宝宝本次身长测评百分位处于P25-P75。在同龄宝宝中，宝宝的身长处于中等水平，发育正常！";
				} elseif ($height >= $array['p75'] && $height < $array['p97']) {
					$message = "宝宝本次身长测评百分位处于P75-P97。在同龄宝宝中，宝宝的身长处于中等偏上水平，请结合BMI指标判断是否需要干预！";
				} else {
					$message = "宝宝本次身长测评百分位大于P97。在同龄宝宝中，宝宝的身长处于超常水平，请带宝宝到正规的儿科医院做综合的检查和评估！";
				}

				# code...
				break;
			case 2:
				if ($weight < $array['p3'] ) {
					$message = "宝宝本次体重测评百分位小于P3。在同龄宝宝中，宝宝的体重处于异常水平，请带宝宝到正规的儿科医院做综合的检查和评估！";
				} elseif ($weight >= $array['p3'] && $weight < $array['p25']) {
					$message = "宝宝本次体重测评百分位处于P3-P25。在同龄宝宝中，宝宝的体重处于中等偏下水平，请结合BMI指标判断是否需要干预！";
				} elseif ($weight >= $array['p25'] && $weight < $array['p75']) {
					$message = "宝宝本次体重测评百分位处于P25-P75。在同龄宝宝中，宝宝的体重处于中等水平，发育正常！";
				} elseif ($weight >= $array['p75'] && $weight < $array['p97']) {
					$message = "宝宝本次体重测评百分位处于P75-P97。在同龄宝宝中，宝宝的体重处于中等偏上水平，请结合BMI指标判断是否需要干预！";
				} else {
					$message = "宝宝本次体重测评百分位大于P97。在同龄宝宝中，宝宝的体重处于超常水平，请带宝宝到正规的儿科医院做综合的检查和评估！";
				}
				break;
			case 3:
				if ($bmi <= $array['p3'] ) {
					$message = "宝宝本次测评的BMI百分位小于P3。综合身长、体重发育指标，宝宝的体型处于严重消瘦水平，请带宝宝到正规的儿科医院做综合的检查和评估！";
				} elseif ($bmi >= $array['p3'] && $bmi < $array['p15']) {
					$message = "宝宝本次测评的BMI百分位处于P3-P15。综合身长、体重发育指标，宝宝的体型处于消瘦水平，请带宝宝到正规的儿科医院做综合的检查和评估！";
				} elseif ($bmi >= $array['p15'] && $bmi < $array['p85']) {
					$message = "宝宝本次测评的BMI百分位处于P15-P85。综合身长、体重发育指标，宝宝的体型处于正常水平，发育良好！";
				} elseif ($bmi >= $array['p85'] && $bmi < $array['p95']) {
					$message = "宝宝本次测评的BMI百分位处于P85-P95。综合身长、体重发育指标，宝宝的体型处于超重水平，请带宝宝到正规的儿科医院做综合的检查和评估！";
				} else {
					$message = "宝宝本次测评的BMI百分位大于P95。综合身长、体重发育指标，宝宝的体型处于肥胖水平，请带宝宝到正规的儿科医院做综合的检查和评估！";
				}
				break;
			case 4:
				if ($header >= $array['two'] && $header <= $array['four']) {
					$message = "宝宝本次头围测评结果为".$header."CM。宝宝头围处于正常值范围，发育正常！";
				} elseif ($header < $array['two']) {
					$message = "宝宝本次头围测评结果为".$header."CM。宝宝头围偏小，提示有脑发育不良的可能，建议去正规的儿科医院做进一步检查！";
				} elseif ($header <= $array['one']) {
					$message = "宝宝本次头围测评结果为".$header."CM。宝宝头围偏小，提示脑发育不良，建议去正规的儿科医院做进一步检查";
				} elseif ($header > $array['four']) {
					$message = "宝宝本次头围测评结果为".$header."CM。宝宝头围偏大，提示有脑积水或其它疾病的可能，建议去正规的儿科医院做进一步检查！";
				} elseif ($header >= $array['five']) {
					$message = "宝宝本次头围测评结果为".$header."CM。宝宝头围偏大，提示有脑积水或其它疾病，建议去正规的儿科医院做进一步检查！";
				}
				break;
			default:
				# code...
				break;
		}

		return $message;
	}

	//头围相关数据
	private function babyHeader()
	{
		$boy = '[{"month":0,"one":30.7,"two":31.9,"three":34.5,"four":37,"five":38.3,"type":4,"sex":1},{"month":1,"one":33.8,"two":34.9,"three":37.3,"four":39.6,"five":40.8,"type":4,"sex":1},{"month":2,"one":35.6,"two":36.8,"three":39.1,"four":41.5,"five":42.6,"type":4,"sex":1},{"month":3,"one":37,"two":38.1,"three":40.5,"four":42.9,"five":44.1,"type":4,"sex":1},{"month":4,"one":38,"two":39.2,"three":41.6,"four":44,"five":45.2,"type":4,"sex":1},{"month":5,"one":38.9,"two":40.1,"three":42.6,"four":45,"five":46.2,"type":4,"sex":1},{"month":6,"one":39.7,"two":40.9,"three":43.3,"four":45.8,"five":47,"type":4,"sex":1},{"month":7,"one":40.3,"two":41.5,"three":44,"four":46.4,"five":47.7,"type":4,"sex":1},{"month":8,"one":40.8,"two":42,"three":44.5,"four":47,"five":48.3,"type":4,"sex":1},{"month":9,"one":41.2,"two":42.5,"three":45,"four":47.5,"five":48.8,"type":4,"sex":1},{"month":10,"one":41.6,"two":42.9,"three":45.4,"four":47.9,"five":49.2,"type":4,"sex":1},{"month":11,"one":41.9,"two":43.2,"three":45.8,"four":48.3,"five":49.6,"type":4,"sex":1},{"month":12,"one":42.2,"two":43.5,"three":46.1,"four":48.6,"five":49.9,"type":4,"sex":1},{"month":13,"one":42.5,"two":43.8,"three":46.3,"four":48.9,"five":50.2,"type":4,"sex":1},{"month":14,"one":42.7,"two":44,"three":46.6,"four":49.2,"five":50.5,"type":4,"sex":1},{"month":15,"one":42.9,"two":44.2,"three":46.8,"four":49.4,"five":50.7,"type":4,"sex":1},{"month":16,"one":43.1,"two":44.4,"three":47,"four":49.6,"five":51,"type":4,"sex":1},{"month":17,"one":43.2,"two":44.6,"three":47.2,"four":49.8,"five":51.2,"type":4,"sex":1},{"month":18,"one":43.4,"two":44.7,"three":47.4,"four":50,"five":51.4,"type":4,"sex":1},{"month":19,"one":43.5,"two":44.9,"three":47.5,"four":50.2,"five":51.5,"type":4,"sex":1},{"month":20,"one":43.7,"two":45,"three":47.7,"four":50.4,"five":51.7,"type":4,"sex":1},{"month":21,"one":43.8,"two":45.2,"three":47.8,"four":50.5,"five":51.9,"type":4,"sex":1},{"month":22,"one":43.9,"two":45.3,"three":48,"four":50.7,"five":52,"type":4,"sex":1},{"month":23,"one":44.1,"two":45.4,"three":48.1,"four":50.8,"five":52.2,"type":4,"sex":1},{"month":24,"one":44.2,"two":45.5,"three":48.3,"four":51,"five":52.3,"type":4,"sex":1},{"month":25,"one":44.3,"two":45.6,"three":48.4,"four":51.1,"five":52.5,"type":4,"sex":1},{"month":26,"one":44.4,"two":45.8,"three":48.5,"four":51.2,"five":52.6,"type":4,"sex":1},{"month":27,"one":44.5,"two":45.9,"three":48.6,"four":51.4,"five":52.7,"type":4,"sex":1},{"month":28,"one":44.6,"two":46,"three":48.7,"four":51.5,"five":52.9,"type":4,"sex":1},{"month":29,"one":44.7,"two":46.1,"three":48.8,"four":51.6,"five":53,"type":4,"sex":1},{"month":30,"one":44.8,"two":46.1,"three":48.9,"four":51.7,"five":53.1,"type":4,"sex":1},{"month":31,"one":44.8,"two":46.2,"three":49,"four":51.8,"five":53.2,"type":4,"sex":1},{"month":32,"one":44.9,"two":46.3,"three":49.1,"four":51.9,"five":53.3,"type":4,"sex":1},{"month":33,"one":45,"two":46.4,"three":49.2,"four":52,"five":53.4,"type":4,"sex":1},{"month":34,"one":45.1,"two":46.5,"three":49.3,"four":52.1,"five":53.5,"type":4,"sex":1},{"month":35,"one":45.1,"two":46.6,"three":49.4,"four":52.2,"five":53.6,"type":4,"sex":1},{"month":36,"one":45.2,"two":46.6,"three":49.5,"four":52.3,"five":53.7,"type":4,"sex":1}]';
		$boy = json_decode($boy,true);
		$girl = '[{"month":0,"one":30.3,"two":31.5,"three":33.9,"four":36.2,"five":37.4,"type":4,"sex":2},{"month":1,"one":33,"two":34.2,"three":36.5,"four":38.9,"five":40.1,"type":4,"sex":2},{"month":2,"one":34.6,"two":35.8,"three":38.3,"four":40.7,"five":41.9,"type":4,"sex":2},{"month":3,"one":35.8,"two":37.1,"three":39.5,"four":42,"five":43.3,"type":4,"sex":2},{"month":4,"one":36.8,"two":38.1,"three":40.6,"four":43.1,"five":44.4,"type":4,"sex":2},{"month":5,"one":37.6,"two":38.9,"three":41.5,"four":44,"five":45.3,"type":4,"sex":2},{"month":6,"one":38.3,"two":"39.6","three":42.2,"four":44.8,"five":46.1,"type":4,"sex":2},{"month":7,"one":38.9,"two":40.2,"three":42.8,"four":45.5,"five":46.8,"type":4,"sex":2},{"month":8,"one":39.4,"two":40.7,"three":43.4,"four":46,"five":47.4,"type":4,"sex":2},{"month":9,"one":39.8,"two":41.2,"three":43.8,"four":46.5,"five":47.8,"type":4,"sex":2},{"month":10,"one":40.2,"two":41.5,"three":44.2,"four":46.9,"five":48.3,"type":4,"sex":2},{"month":11,"one":40.5,"two":41.9,"three":44.6,"four":47.3,"five":48.6,"type":4,"sex":2},{"month":12,"one":40.8,"two":42.2,"three":44.9,"four":47.6,"five":49,"type":4,"sex":2},{"month":13,"one":41.1,"two":42.4,"three":45.2,"four":47.9,"five":49.3,"type":4,"sex":2},{"month":14,"one":41.3,"two":42.7,"three":45.4,"four":48.2,"five":49.5,"type":4,"sex":2},{"month":15,"one":41.5,"two":42.9,"three":45.7,"four":48.4,"five":49.8,"type":4,"sex":2},{"month":16,"one":41.7,"two":43.1,"three":45.9,"four":48.6,"five":50,"type":4,"sex":2},{"month":17,"one":41.9,"two":43.3,"three":46.1,"four":48.8,"five":50.2,"type":4,"sex":2},{"month":18,"one":42.1,"two":43.5,"three":46.2,"four":49,"five":50.4,"type":4,"sex":2},{"month":19,"one":42.3,"two":43.6,"three":46.4,"four":49.2,"five":50.6,"type":4,"sex":2},{"month":20,"one":42.4,"two":43.8,"three":46.6,"four":49.4,"five":50.7,"type":4,"sex":2},{"month":21,"one":42.6,"two":44,"three":46.7,"four":49.5,"five":50.9,"type":4,"sex":2},{"month":22,"one":42.7,"two":44.1,"three":46.9,"four":49.7,"five":51.1,"type":4,"sex":2},{"month":23,"one":42.9,"two":44.3,"three":47,"four":49.8,"five":51.2,"type":4,"sex":2},{"month":24,"one":43,"two":44.4,"three":47.2,"four":50,"five":51.4,"type":4,"sex":2},{"month":25,"one":43.1,"two":44.5,"three":47.3,"four":50.1,"five":51.5,"type":4,"sex":2},{"month":26,"one":43.3,"two":44.7,"three":47.5,"four":50.3,"five":51.7,"type":4,"sex":2},{"month":27,"one":43.4,"two":44.8,"three":47.6,"four":50.4,"five":51.8,"type":4,"sex":2},{"month":28,"one":43.5,"two":44.9,"three":47.7,"four":50.5,"five":51.9,"type":4,"sex":2},{"month":29,"one":43.6,"two":45,"three":47.8,"four":50.6,"five":52,"type":4,"sex":2},{"month":30,"one":43.7,"two":45.1,"three":47.9,"four":50.7,"five":52.2,"type":4,"sex":2},{"month":31,"one":43.8,"two":45.2,"three":48,"four":50.9,"five":52.3,"type":4,"sex":2},{"month":32,"one":43.9,"two":45.3,"three":48.1,"four":51,"five":52.4,"type":4,"sex":2},{"month":33,"one":44,"two":45.4,"three":48.2,"four":51.1,"five":52.5,"type":4,"sex":2},{"month":34,"one":44.1,"two":45.5,"three":48.3,"four":51.2,"five":52.6,"type":4,"sex":2},{"month":35,"one":44.2,"two":45.6,"three":48.4,"four":51.2,"five":52.7,"type":4,"sex":2},{"month":36,"one":44.3,"two":45.7,"three":48.5,"four":51.3,"five":52.7,"type":4,"sex":2}]';
		$girl = json_decode($girl,true);
		if ($sex == 1) {
			return $boy;
		} else {
			return $girl;
		}
	}


	//根据宝宝当月月龄获取对应的食谱
    public function returnFoods($month)
    {

    	
    	$info = $this->query("SELECT nr FROM sp_subject_game WHERE month = $month AND type = 1");
    	if ($info[0]['nr']) {
    		$info = unserialize($info[0]['nr']);
    		$i = join(',',$info);
    		$foodList = $this->query("SELECT ID,Title,Media,thumb FROM matt_app.M_TestStore WHERE ID IN($i)");
    		$foodList = array_map(function($v){
				$pregStr = 'src="/attached/image';
				$content = str_replace($pregStr, 'src="https://app.mattservice.com/attached/image', $v['content']);
				$content = preg_replace("/<a[^>]*>(.*)<\/a>/isU",'${1}',$content);
				$v['content'] = $content;
				$v['media'] = "https://app.mattservice.com/".$v['media'];
				$v['thumb'] = "https://app.mattservice.com/".'info/'.$v['thumb'];
				return get_object_vars($v);
			},$foodList);
			return $foodList;
    	} else {
    		return NULL;
    	}
    }
}