
<?php
define('IN_ECS', true);
include ('includes/init.php');
require_once 'phpexcel/PHPExcel.php'; 
require_once 'phpexcel/PHPExcel/Writer/Excel5.php';     // 用于其他低版本xls 
require_once 'phpexcel/PHPExcel/Writer/Excel2007.php'; // 用于 excel-2007 格式 
require_once 'phpexcel/PHPExcel/IOFactory.php'; 
$order_id = intval($_REQUEST['order_id']);
$sql="select gt.goods_thumb, g.goods_name, g.goods_sn, g.goods_price, g.goods_number, (g.goods_price*g.goods_number) as num_price, g.goods_attr from ecs_goods gt, ecs_order_info i, ecs_order_goods g where g.order_id=i.order_id and gt.goods_id = g.goods_id and i.order_id = '$order_id'";
$data=$db->getAll($sql);
				$obj_phpexcel = new PHPExcel();
        $obj_phpexcel->getActiveSheet()->setCellValue('a1','Key');
        $obj_phpexcel->getActiveSheet()->setCellValue('b1','Value');        
        if($data){
            $i =2;
            foreach ($data as $key => $value) {
                # code...
                $obj_phpexcel->getActiveSheet()->setCellValue('a'.$i,$value);
                $i++;
            }
        }    

        $obj_Writer = PHPExcel_IOFactory::createWriter($obj_phpexcel,'Excel5');
        $filename = "outexcel.xls";
        
        header("Content-Type: application/force-download"); 
        header("Content-Type: application/octet-stream"); 
        header("Content-Type: application/download"); 
        header('Content-Disposition:inline;filename="'.$filename.'"'); 
        header("Content-Transfer-Encoding: binary"); 
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
        header("Pragma: no-cache"); 
        $obj_Writer->save('php://output'); 

?>