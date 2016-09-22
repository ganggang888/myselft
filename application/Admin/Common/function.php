<?php

function sp_get_url_route(){
	$apps=sp_scan_dir(SPAPP."*",GLOB_ONLYDIR);
	$host=(is_ssl() ? 'https' : 'http')."://".$_SERVER['HTTP_HOST'];
	$routes=array();
	foreach ($apps as $a){
	
		if(is_dir(SPAPP.$a)){
			if(!(strpos($a, ".") === 0)){
				$navfile=SPAPP.$a."/nav.php";
				$app=$a;
				if(file_exists($navfile)){
					$navgeturls=include $navfile;
					foreach ($navgeturls as $url){
						//echo U("$app/$url");
						$nav= file_get_contents($host.U("$app/$url"));
						$nav=json_decode($nav,true);
						if(!empty($nav) && isset($nav['urlrule'])){
							if(!is_array($nav['urlrule']['param'])){
								$params=$nav['urlrule']['param'];
								$params=explode(",", $params);
							}
							sort($params);
							$param="";
							foreach($params as $p){
								$param.=":$p/";
							}
							
							$routes[strtolower($nav['urlrule']['action'])."/".$param]=$nav['urlrule']['action'];
						}
					}
				}
					
			}
		}
	}
	
	return $routes;
}


function postRequest( $api, array $params = array(), $timeout = 30 )
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $api );
        // 以返回的形式接收信息
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        // 设置为POST方式
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
        // 不验证https证书
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept: application/json',
        ) ); 
        // 发送数据
        $response = curl_exec( $ch );
        // 不要忘记释放资源
        curl_close( $ch );
        return $response;
    }
