<?php

function logs($location){
	$php_self=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
	$result = $php_self . " 于 " . date('c') . " 运行" . "\n";
	if ($location == 0) {
		$logs = "logs/full.log";
		$log = "logs/$php_self.log";
	} else {
		$logs = "../logs/full.log";
		$log = "../logs/$php_self.log";
	}
	file_put_contents($logs, $result, FILE_APPEND);
	file_put_contents($log, $result, FILE_APPEND);
}

function curl_get_contents($url, $proxy) { 
	$curlHandle = curl_init(); 
	curl_setopt( $curlHandle , CURLOPT_URL, $url ); 
	curl_setopt( $curlHandle , CURLOPT_RETURNTRANSFER, 1 ); 
	curl_setopt( $curlHandle , CURLOPT_TIMEOUT, 20 ); 
	curl_setopt( $curlHandle , CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt( $curlHandle , CURLOPT_SSL_VERIFYHOST, FALSE);
	if ( $proxy == 1 ){ curl_setopt( $curlHandle , CURLOPT_PROXY, "http://9yukinoz:01370149@ppc.vnet.link:8800"); }
	$curl_errno = curl_errno( $curlHandle ); 
	$result = curl_exec( $curlHandle ); 
	curl_close( $curlHandle ); 
	if ( $curl_errno <> 0 ) {
		$php_self=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
		$result = $php_self . " 于 " . date('c') . " 错误并停止工作" . "\n";
		if ( file_exists("$php_self.erros.log") ) {
			file_put_contents("$php_self.erros.log", $result, FILE_APPEND);
		} else {
			file_put_contents("$php_self.erros.log", $result);
		}
		exit();
	}
	return $result; 
}

function xmlToArray($xml){ 
	libxml_disable_entity_loader(true); 
	$xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
	$val = json_decode(json_encode($xmlstring),true); 
	return $val; 
} 

function bgm_find($id,$content){
	$i = 0;
	foreach ($content['items'] as $array) {
		foreach ($array['sites'] as $value) {
			if ( $value['site'] == "bangumi" && $value['id'] == $id ){
				return $i;
			}
		}
	$i = $i + 1;
	}
}

function dateDiff($date){
	date_default_timezone_set("Asia/Shanghai");
	$today = strtotime("now");
	$date_time = strtotime($date);
	if ( $today >= $date_time ){
		return "y";
	}
	$today = date("Y-m-d H:i:s",$today);
	$datetime1 = date_create($today);
    $datetime2 = date_create($date);
    $interval = date_diff($datetime1, $datetime2);
    $diff = $interval->format('%r%a');
    if ( $diff > 0 ){
    	return $diff . "d";
    } 
    if ( $diff == 0 ){
    	$diff = $interval->format('%h');
    	if ( $diff <> 0 ){
    		return $diff . "h";
    	} else {
    		$diff = $interval->format('%i');
    		return $diff . "m";
    	}
    }
}

date_default_timezone_set("Asia/Shanghai");
$today = date('N');
$today_month = date('Y-m-d');
$yesterday = $today - 1;
$tomorrow = $today + 1;
if ( $today == 1 ){
	$yesterday = 7;
}
if ( $today == 7){
	$tomorrow = 1;
}

?>