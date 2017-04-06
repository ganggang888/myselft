<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/30
 * Time: 13:32
 */
namespace Common\Model;
use Common\Model\CommonModel;
class TopicTermModel extends CommonModel
{
    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('name', 'require', '分类名称不能为空！', 1, 'regex', 3),
    );

    //操作一些数据
    protected function _before_insert(&$data,$option)
    {
        $data['add_time'] = date("Y-m-d H:i:s");
    }
}