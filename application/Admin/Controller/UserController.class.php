<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class UserController extends AdminbaseController{
	protected $users_model,$role_model,$month_info;
	
	function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/Users");
		$this->role_model = D("Common/Role");
		$this->month_info = D("Common/MonthInfo");
	}
	function index(){
		$count=$this->users_model->where(array("user_type"=>1))->count();
		$page = $this->page($count, 20);
		$users = $this->users_model
		->where(array("user_type"=>1))
		->order("create_time DESC")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();
		
		$roles_src=$this->role_model->select();
		$roles=array();
		foreach ($roles_src as $r){
			$roleid=$r['id'];
			$roles["$roleid"]=$r;
		}
		$this->assign("page", $page->show('Admin'));
		$this->assign("roles",$roles);
		$this->assign("users",$users);
		$this->display();
	}
	
	
	function add(){
		$roles=$this->role_model->where("status=1")->order("id desc")->select();
		$this->assign("roles",$roles);
		$this->display();
	}
	
	function add_post(){
		if(IS_POST){
			if(!empty($_POST['role_id']) && is_array($_POST['role_id'])){
				$role_ids=$_POST['role_id'];
				unset($_POST['role_id']);
				if ($this->users_model->create()) {
					$result=$this->users_model->add();
					if ($result!==false) {
						$role_user_model=M("RoleUser");
						foreach ($role_ids as $role_id){
							$role_user_model->add(array("role_id"=>$role_id,"user_id"=>$result));
						}
						$this->success("添加成功！", U("user/index"));
					} else {
						$this->error("添加失败！");
					}
				} else {
					$this->error($this->users_model->getError());
				}
			}else{
				$this->error("请为此用户指定角色！");
			}
			
		}
	}
	
	
	function edit(){
		$id= intval(I("get.id"));
		$roles=$this->role_model->where("status=1")->order("id desc")->select();
		$this->assign("roles",$roles);
		$role_user_model=M("RoleUser");
		$role_ids=$role_user_model->where(array("user_id"=>$id))->getField("role_id",true);
		$this->assign("role_ids",$role_ids);
			
		$user=$this->users_model->where(array("id"=>$id))->find();
		$this->assign($user);
		$this->display();
	}
	
	function edit_post(){
		if (IS_POST) {
			if(!empty($_POST['role_id']) && is_array($_POST['role_id'])){
				if(empty($_POST['user_pass'])){
					unset($_POST['user_pass']);
				}
				$role_ids=$_POST['role_id'];
				unset($_POST['role_id']);
				if ($this->users_model->create()) {
					$result=$this->users_model->save();
					if ($result!==false) {
						$uid=intval($_POST['id']);
						$role_user_model=M("RoleUser");
						$role_user_model->where(array("user_id"=>$uid))->delete();
						foreach ($role_ids as $role_id){
							$role_user_model->add(array("role_id"=>$role_id,"user_id"=>$uid));
						}
						$this->success("保存成功！");
					} else {
						$this->error("保存失败！");
					}
				} else {
					$this->error($this->users_model->getError());
				}
			}else{
				$this->error("请为此用户指定角色！");
			}
			
		}
	}
	
	/**
	 *  删除
	 */
	function delete(){
		$id = intval(I("get.id"));
		if($id==1){
			$this->error("最高管理员不能删除！");
		}
		
		if ($this->users_model->where("id=$id")->delete()!==false) {
			M("RoleUser")->where(array("user_id"=>$id))->delete();
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	
	function userinfo(){
		$id=get_current_admin_id();
		$user=$this->users_model->where(array("id"=>$id))->find();
		$this->assign($user);
		$this->display();
	}
	
	function userinfo_post(){
		if (IS_POST) {
			$_POST['id']=get_current_admin_id();
			$create_result=$this->users_model
			->field("user_login,user_email,last_login_ip,last_login_time,create_time,user_activation_key,user_status,role_id,score,user_type",true)//排除相关字段
			->create();
			if ($create_result) {
				if ($this->users_model->save()!==false) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->users_model->getError());
			}
		}
	}
	
	    function ban(){
        $id=intval($_GET['id']);
    	if ($id) {
    		$rst = $this->users_model->where(array("id"=>$id,"user_type"=>1))->setField('user_status','0');
    		if ($rst) {
    			$this->success("管理员停用成功！", U("user/index"));
    		} else {
    			$this->error('管理员停用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function cancelban(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = $this->users_model->where(array("id"=>$id,"user_type"=>1))->setField('user_status','1');
    		if ($rst) {
    			$this->success("管理员启用成功！", U("user/index"));
    		} else {
    			$this->error('管理员启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

	/**
	* monthInfo 
	* 宝宝月龄信息列表
	*
	* @access public
	* @param mixed $month 宝宝月份
	* @param mixed $content 介绍内容
	* @since 1.0
	* @return html
	*/
    public function monthInfo()
    {
    	$where = " id > 0 ";
    	$content = I('get.content');
    	$where .= I('get.month') ? ' AND month = '.I('get.month') : '';
    	$where .= $content ? " AND INSTR(`content`,'$content')" : "";
    	$count = $this->month_info->where($where)->count();
    	$page = $this->page($count,20);
    	$result = $this->month_info->where($where)->limit($page->firstRow,$page->listRows)->select();
    	$this->assign('page',$page->show('Admin'));
    	$this->assign('month',I('get.month'));
    	$this->assign('content',I('get.content'));
    	$this->assign('result',$result);
    	$this->display();
    }
	
	/**
	* addMonthInfo 
	* 添加宝宝月龄信息
	*
	* @access public
	* @param mixed $height 宝宝身高
	* @param mixed $weight 宝宝体重
	* @param mixed $month 宝宝月龄
	* @param mixed $content 宝宝介绍
	* @since 1.0
	* @return html
	*/
	public function addMonthInfo()
	{
		if (IS_POST) {
			$_POST['add_time'] = date("Y-m-d H:i:s");
			if ($this->month_info->create() !== false) {
				if ($this->month_info->add()) {
					$this->success("添加成功",U('User/monthInfo'));
				} else {
					$this->error("添加失败",U("User/monthInfo"));
				}
			} else {
				$this->error($this->month_info->getError());
			}
		}
		$this->display();
	}

	/**
	* editMonthInfo 
	* 修改宝宝月龄信息
	*
	* @access public
	* @param mixed $id 数据库表id
	* @since 1.0
	* @return html
	*/
	public function editMonthInfo()
	{
		$id = I("get.id");
		$id ? '' : $this->error("GET OUT!");
		$info = $this->month_info->find($id);
		//var_dump($info);
		$this->assign('info',$info);
		$this->display();
	}

	/**
	* postEditMonthInfo 
	* 提交修改宝宝宝宝月龄信息
	*
	* @access public
	* @param mixed $id 数据库表id
	* @param mixed $height 宝宝身高
	* @param mixed $weigth 宝宝体重
	* @param mixed $month 宝宝月龄
	* @param mixed $content 宝宝介绍
	* @since 1.0
	* @return html
	*/
	public function postEditMonthInfo()
	{
		if (IS_POST) {
			if ($this->month_info->create() !== false) {
				if ($this->month_info->where(array("id"=>$_POST['id']))->save() !== false) {
					$this->success("修改成功",U('User/monthInfo'));
				} else {
					$this->error("修改失败",U("User/monthInfo"));
				}
			} else {
				$this->error($this->month_info->getError());
			}
		}
	}

	/**
	* deleteInfo 
	* 删除宝宝月龄信息
	*
	* @access public
	* @param mixed $id 数据库表id
	* @since 1.0
	* @return html
	*/
	public function deleteInfo()
	{
		$id = I('get.id');
		$id ? '' : $this->error('GET OUT!');
		$this->month_info->where(array('id'=>$id))->delete() ? $this->success("删除成功") : $this->error("删除失败");
	}

	//导入excel数据格式的用户手机号码以及联系人信息到数据库
    public function savePhone()
    {
        if (IS_POST) {
        	if($_FILES["upload"]){
	            $hou = explode('.',$_FILES["upload"]["name"]);
	            //var_dump($_FILES);
	            if ($hou[1] == 'xlsx' || $hou[1] == 'xls') {
	                $name = "/matt/mattapp/document/".md5(microtime()).".".$hou[1];
	                $path = $name;
	                move_uploaded_file($_FILES["upload"]["tmp_name"],$path);
	                
	                //读取excel内容
	                define('IN_ECS', true);
	                vendor('phpexcel.init');
	                vendor('phpexcel.PHPExcel');
	                vendor('phpexcel.PHPExcel');
	                vendor('phpexcel.PHPExcel.Writer.Excel5');
	                vendor('phpexcel.PHPExcel.Writer.Excel2007');
	                //创建一个excel对象
	                $filePath = $path; 
	                //建立reader对象  
	                $PHPReader = new \PHPExcel_Reader_Excel2007();  
	                if(!$PHPReader->canRead($filePath)){  
	                    $PHPReader = new \PHPExcel_Reader_Excel5();  
	                    if(!$PHPReader->canRead($filePath)){  
	                        echo 'no Excel';  
	                        return ;  
	                    }  
	                }  

	                //建立excel对象，此时你即可以通过excel对象读取文件，也可以通过它写入文件  
	                $PHPExcel = $PHPReader->load($filePath);  

	                /**读取excel文件中的第一个工作表*/  
	                $currentSheet = $PHPExcel->getSheet(0);  
	                /**取得最大的列号*/  
	                $allColumn = $currentSheet->getHighestColumn();  
	                /**取得一共有多少行*/  
	                $allRow = $currentSheet->getHighestRow();  

	                //循环读取每个单元格的内容。注意行从1开始，列从A开始  
	                for($rowIndex=1;$rowIndex<=$allRow;$rowIndex++){  
	                    for($colIndex='A';$colIndex<=$allColumn;$colIndex++){  
	                        $addr = $colIndex.$rowIndex;  
	                        $cell = $currentSheet->getCell($addr)->getValue();  
	                        if($cell instanceof \PHPExcel_RichText)     //富文本转换字符串  
	                            $cell = $cell->__toString();
	                        $info[] = $cell;  
	                    }  

	                }
	                //将指定格式的excel内容进行处理，为新的数组
	                foreach ($info as $key=>$vo) {
	                    if (preg_match('/(13\d|14[57]|15[^4,\D]|17[678]|18\d)\d{8}|170[059]\d{7}/', $vo, $matches)) {
	                        //生成唯一识别码并保存
	                        $id = base64_encode(uniqid().mt_rand(1,10086));
	                        $array[] = array('name'=>$info[$key-1],'phone'=>$vo,'id'=>$id);
	                    }
	                }
	                if (!$array) {
	                	$this->error('上传的excel文件内容有误');
	                }
	                //先标记旧的数据，再存新的
	                M('number_info')->where("status = 0")->save(array('status'=>1));
	                $all = M('number_info')->addAll($array);
	                $this->success('导入成功',U('User/numberIndex'));
	            } else {
	                $this->error('格式不正确，请导入正确的excel文件~');
	            }
	        }
        }
        $this->display();
    }
    
    
    //查看通讯录列表信息
    public function numberIndex()
    {
        $phone = I('get.phone');//手机
        $name = I('get.name');//名称
        $where = ' status = 0 ';//默认条件
        //如果满足传输条件则开始判断
        $name ? $where .= " AND INSTR(`name`,'$name')" : '';
        $phone ? $where .= " AND INSTR(`phone`,'$phone') " : '';
        $count = M('number_info')->where($where)->count();
        $page = $this->page($count,20);
        $result = M('number_info')->where($where)->limit($page->firstRow,$page->listRows)->field(array('phone','name','id'))->select();
        $this->assign('page',$page->show('Admin'));
        $this->assign('result',$result);
        $this->assign('phone',$phone);
        $this->assign('name',$name);
        $this->display();
    }

    //提交群发短信的信息给管理员，从而让管理员去进行处理
    public function giveMessage()
    {
    	if (IS_POST) {
    		$_POST['add_time'] = date("Y-m-d H:i:s");
    		$_POST['id'] = base64_encode(uniqid().mt_rand(1,10086));
    		!$_POST['name'] || !$_POST['content'] ? $this->error('请输入正确的信息') : '';
    		M('message_info')->add($_POST);
    		$this->success('提交给管理员成功，等待审核中~',U('User/messageIndex'));
    	}
    	$this->display();
    }

    //提交的需要群发的短信模板列表
    public function messageIndex()
    {
    	$begin = I('get.begin');//开始时间
    	$end = I('get.end');//结束时间
    	$name = I('get.name'); //名称检索
    	$content = I('get.content'); //内容检索
    	$where = " name != '' ";
    	$name ? $where .= " AND INSTR(`name`,'$name') " : '';
    	$content ? $where .= " AND INSTR(`content`,'$content') " : '';
    	if ($begin && $end) {
    		$where .= " AND add_time >= '$begin' AND add_time < '$end' ";
    	} elseif ($begin && !$end) {
    		$where .= " AND add_time >= '$begin' ";
    	} elseif (!$begin && $end) {
    		$where .= " AND add_time < '$end'";
    	}

    	$count = M('message_info')->where($where)->count();
    	$page = $this->page($count,20);
    	$result = M('message_info')->where($where)->field(array('name','content','about','status','id','add_time'))->select();
    	$this->assign('data',$_GET);
    	$this->assign('page',$page->show('Admin'));
    	$this->assign('result',$result);
    	$this->display();
    }

    //管理员进行审核某条消息内容是否合理并给予发布
    public function messageStatus()
    {
    	$id = I('get.id');
    	$status = I('get.status');
    	$info = M('message_info')->where(array('id'=>$id))->find();
    	if ($info['status'] != 0) {
    		$this->error('管理员已经处理过了,如有任何事情，请联系管理员');
    	}
    	switch ($status) {
    		case 1://管理员同意后查询所有号码并遍历发送
    			$all = M('number_info')->where(array('status'=>0))->field(array('phone','name','status'))->select();
    			//var_dump($all);
    			foreach ($all as $vo) {
    				//在模板中替换客户姓名
    				$content = str_replace('(username)',$vo['name'],$info['content']);
    				//var_dump($content);var_dump($vo);exit;
    				//var_dump("http://localhost/info/send.php?keys=".md5(date("Y-m-d")."ZySWbXnXXXVxMDhLl9ZcWbIinFXkIq"));
    				//var_dump(array('phone'=>$vo['phone'],'content'=>$content));exit;
    				postRequest("http://app.mattservice.com/info/send.php?keys=".md5(date("Y-m-d")."ZySWbXnXXXVxMDhLl9ZcWbIinFXkIq"),array('phone'=>$vo['phone'],'content'=>$content));
    			}
    			//发送完后更改状态
    			M('message_info')->where(array('id'=>$id))->save(array('status'=>1));
    			break;
    		case 2://拒绝后直接更改状态
    			M('message_info')->where(array('id'=>$id))->save(array('status'=>2));
    			break;
    		default:
    			$this->error('请求出错，请重试');
    			# code...
    			break;
    	}
    	$this->success('处理成功');
    }

    //根据操作记录生成曲线图
    public function logLine()
    {
    	//echo microtime().'<br/>';
    	$model = M();
    	$begin = I('get.begin');
    	$end = I('get.end');
    	$this->assign('begin',$begin);
		$this->assign('end',$end);
    	$begin ? $begin = $begin." 00:00:00" : '';
    	$end ? $end = $end." 23:59:59" : '';
    	if ($begin && $end) {
    		!$begin || !$end ? $this->error('必须选择一个区间，并且该区间的日期少于或者等于31天',U('User/logLine')) : '';
			$begin > $end ? $this->error('开始时间不能大于结束时间',U('User/logLine')) : '';
			//计算日期相差天数
			if ((strtotime($end) - strtotime($begin)) / 86400 > 31) {
				$this->error('您查询的日期过长，请查询30天内的数据',U('User/logLine'));
			}
			//查询制定日期数据
			$result = $model->query("SELECT COUNT(0) AS num,action , DATE( `add_time` ) AS add_time, unix_timestamp( `add_time` ) * 1000 AS used FROM matt_app.teacher_log WHERE add_time >= '$begin' AND add_time <= '$end' GROUP BY DATE( `add_time` ),action");
			
			//为了提升运行效率，在此先将数据进行处理
			//echo microtime()."<br/>";
			foreach ($result as $key=>$vo) {
				$data[$vo['action']][] = $vo;
				//unset($result[$key]);
			}
			//echo microtime()."<br/>";
			foreach ($data as $key=>$vo) {
				foreach ($vo as $k=>$v) {
					$array[$key][$v['add_time']] = $v;
					//unset($data[$key][$k]);
				}
			}
			//var_dump($array);exit;
			//unset($data); //及时销毁
			//echo microtime();//exit;
			$tex = '';
			foreach ($array as $key=>$vo) {
				$all = array_reduce($vo, function ($vo, $v) {
			        $vo['all']+= $v['num'];
			        return $vo;
		    	});
				$tex .= "{"."name:'".logAction($key)."共".$all['all']."次',";
				$tex .= "data:[";
				foreach ($vo as $k=>$v) {
					$tex .= "[Date.UTC(new Date(parseInt(".$v['used'].")).getFullYear(),new Date(parseInt(".$v['used'].")).getMonth(),new Date(parseInt(".$v['used'].")).getDate()),".$v['num']."],";
					//$tex .= "[".$v[0]['dotime'].",".count($v)."],";
					//$tex .= "[new Date(parseInt(".$v[0]['used'].")),".count($v)."],";
					//echo "<script>alert(new Date(parseInt(".$v[0]['used'].")).getFullYear())</script>";
				}
				$tex .= "]},";
			}
			$tex = substr($tex,0,strlen($tex)-1);
			//var_dump($tex);
			
			$this->assign('tex',$tex);
			$this->assign('result',$array);
    	}
    	
    	$this->display();
    }

    //导出用户点击记录为excel表格形式
    public function getTable()
    {
    	//echo microtime().'<br/>';
    	$model = M();
    	$begin = I('get.begin');
    	$end = I('get.end');
    	$this->assign('begin',$begin);
		$this->assign('end',$end);
    	$begin ? $begin = $begin." 00:00:00" : '';
    	$end ? $end = $end." 23:59:59" : '';
    	if ($begin && $end) {
    		!$begin || !$end ? $this->error('必须选择一个区间，并且该区间的日期少于或者等于31天',U('User/logLine')) : '';
			$begin > $end ? $this->error('开始时间不能大于结束时间',U('User/logLine')) : '';
			
			//查询制定日期数据
			$result = $model->query("SELECT COUNT(*) AS num,action,DATE(add_time) AS add_time FROM matt_app.teacher_log WHERE add_time >= '$begin' AND add_time <= '$end' group by DATE(add_time),action");
			foreach ($result as $vo) {
				$data[$vo['add_time']][] = $vo;
			}
			$i=0;
			foreach ($data as $vo) {
				$row[$i] = $vo;
				$i++;
			}

			//var_dump($row);exit;
			define('IN_ECS', true);
		vendor('phpexcel.init');
		vendor('phpexcel.PHPExcel');
		vendor('phpexcel.PHPExcel');
		vendor('phpexcel.PHPExcel.Writer.Excel5');
		vendor('phpexcel.PHPExcel.Writer.Excel2007');
		//创建一个excel对象
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("ctos")
		        ->setLastModifiedBy("ctos")
		        ->setTitle("Office 2007 XLSX Test Document")
		        ->setSubject("Office 2007 XLSX Test Document")
		        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		        ->setKeywords("office 2007 openxml php")
		        ->setCategory("Test result file");

		//set width  
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);

		//设置行高度  
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);

		//set font size bold  
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		//设置水平居中  
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		//  
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');

		// set table header content  
		$objPHPExcel->setActiveSheetIndex(0)
		        ->setCellValue('A1', '操作记录')
		        ->setCellValue('A2', '时间')
		        ->setCellValue('B2', logAction(24))
		        ->setCellValue('C2', logAction(14))
		        ->setCellValue('D2', logAction(9))
		        ->setCellValue('E2', logAction(10))
		        ->setCellValue('F2', logAction(12))
		        ->setCellValue('G2', logAction(26))
		        ->setCellValue('H2', logAction(18))
		        ->setCellValue('I2', logAction(23))
		        ->setCellValue('J2', logAction(19))
		        ->setCellValue('K2', logAction(25))
		        ->setCellValue('L2', logAction(11))
		        ->setCellValue('M2', logAction(36))
		        ->setCellValue('N2', logAction(38))
		        ->setCellValue('O2', logAction(37))
		        ->setCellValue('P2', logAction(2));

		// Miscellaneous glyphs, UTF-8  

		for ($i = 0; $i <= count($row); $i++) {
		    $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $row[$i][0]['add_time']);
		    $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), self::getNumbers($row[$i],24));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), self::getNumbers($row[$i],14));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), self::getNumbers($row[$i],9));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 3), self::getNumbers($row[$i],10));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 3), self::getNumbers($row[$i],12));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 3), self::getNumbers($row[$i],26));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 3), self::getNumbers($row[$i],18));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($i + 3), self::getNumbers($row[$i],23));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('J' . ($i + 3), self::getNumbers($row[$i],19));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('K' . ($i + 3), self::getNumbers($row[$i],25));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('L' . ($i + 3), self::getNumbers($row[$i],11));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('M' . ($i + 3), self::getNumbers($row[$i],36));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('N' . ($i + 3), self::getNumbers($row[$i],38));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('O' . ($i + 3), self::getNumbers($row[$i],37));
		    $objPHPExcel->getActiveSheet(0)->setCellValue('P' . ($i + 3), self::getNumbers($row[$i],2));
		}


		// Rename sheet  
		$objPHPExcel->getActiveSheet()->setTitle('事件');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet  
		$objPHPExcel->setActiveSheetIndex(0);
		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="操作记录.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
    	}
    }

    function getNumbers($data,$action)
    {
    	$return = 0;
    	foreach ($data as $vo) {
    		if ($vo['action'] == $action) {
    			$return = $vo['num'];
    			break;
    		}
    	}
    	return $return;
    }

    //微信留言
    public function phoneInfo()
    {
    	$begin = I('get.begin');
    	$end = I('get.end');
    	if ($begin && $end) {
    		$where = " add_time >= '$begin' AND add_time < '$end'";
    	} elseif ($begin && !$end) {
    		$where = " add_time >= '$begin' ";
    	} elseif (!$begin && $end) {
    		$where = " AND add_time < '$end'";
    	}
    	$count = M('phone_info')->where($where)->count();
    	$page = $this->page($count,15);
    	$result = M('phone_info')->where($where)->limit($page->firstRow,$page->listRows)->field(array('name','phone','id','type','add_time'))->order("id DESC")->select();
    	$this->assign('begin',$begin);
    	$this->assign('end',$end);
    	$this->assign('page',$page->show('Admin'));
    	$this->assign('result',$result);
    	$this->display();
    }

    //删除微信留言的手机号
    public function deletePhoneInfo()
    {
    	$id = I('get.id');
    	M('phone_info')->where(array('id'=>$id))->delete() ? $this->success('删除成功',U('User/phoneInfo')) : $this->error('删除失败',U('User/phoneInfo'));
    }
	
}