<?php
require("../function.php");

$html = curl_get_contents("http://bangumi.tv/index/22363",0);

$start = 0;
$end = 0;
$result = "";
$bgm_id = "";
for ( $i=0; $i < substr_count($html, 'item_'); $i++ ){ 
	$start = strpos($html, 'item_', $start + 20) + 5;
	$end = strpos($html, '"', $start);
	$bgm_id[$i] = substr($html, $start, $end - $start);
}

for ( $i=0; $i < count($bgm_id); $i++ ){ 
	$item = json_decode(curl_get_contents("http://api.bgm.tv/subject/$bgm_id[$i]",0), true);
	$bgmurl = $item['url'];
	$bgmurl = str_replace("http://", "https://", $bgmurl);
	$weekday = $item['air_weekday'];
	$thumb = $item['images']['small'];
	$thumb = str_replace("http://", "https://", $thumb);
	$result[$i] = array(
						 "id"   => $bgm_id[$i],
					  "weekday" => $weekday,
				 	   "bgmurl" => $bgmurl,
					   "thumb"	=> $thumb
						);
}

file_put_contents("../data/bangumi-list.json", json_encode($result));
logs(1);

?>