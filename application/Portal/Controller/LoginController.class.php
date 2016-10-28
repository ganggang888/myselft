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
class LoginController extends HomeBaseController{
	protected $users = null;
	function _initialize() {
        parent::_initialize();
        $this->users = D("Common/Users");
    }

    /**
     * 生成游客用户
     * 需要post传输一个加密的key。key的生成方法是拼接woshishuaige加上今天的年月日，然后md5加密
     * @var key     md5(woshishuaige2015-11-19)
     * url:/info/index.php?g=portal&m=login&a=index
     */
    public function index()
    {
    	if ($_POST['key'] == md5("woshishuaige".date("Y-m-d"))) {
    		$info = $this->users->addUsers();
    		$array[] = array(
    			'phone'=>$info,
    			'password'=>'4920616d206c6f6e656c79'
    		);
    		$this->AjaxReturn(0,0,$array,0);
    	}
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

    //curl截图html信息
    public function getHtml()
    {
        $result = curl_file_get_contents("http://www.lgxc.gov.cn/home.aspx");
        $regex4 = "/<div class=\"weather_up_span\".*?>.*?<\/div>/ism";
        preg_match_all($regex4,$result,$match);
        $regex5 = "/<span>.*?<\/span>/ism";
        preg_match_all($regex5,$match[0][0],$info);
        foreach ($info[0] as $key=>$vo) {
            $key == 0 ? $arr['pm'] = strip_tags($vo) : $arr['pmAuto'] = strip_tags($vo);
        }
        //天气
        $weather = curl_file_get_contents("http://www.soweather.com/DataService/GetData.aspx?DataType=WeatherForecast_NanHui");
        $weather = json_decode($weather,true);
        $this->assign("weathers",$weather);
        $this->assign('arr',$arr);
        if(strstr($weather['weather24'],'雨')) {
            $this->display('6');
        } elseif (strstr($weather['weather24'],'雾霾')) {
            $this->display('2');
        } elseif (strstr($weather['weather24'],'晴转多云')) {
            $this->display('3');
        } elseif (strstr($weather['weather24'],'晴')) {
            $this->display('4');
        } elseif (strstr($weather['weather24'],'雪')) {
            $this->display('5');
        } elseif (strstr($weather['weather24'],'阴') || strstr($weather['weather24'],'多云')) {
            $this->display('6');
        }
        
    }
}