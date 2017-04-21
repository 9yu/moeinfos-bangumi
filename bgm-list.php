<?php
require("function.php");

if (file_get_contents("data/bgm-data.json") == "") {
	logs(0);
	exit();
}

$bangumi_list = json_decode(file_get_contents("data/bangumi-list.json"),true);
$bgm_data = json_decode(file_get_contents("data/bgm-data.json"),true);
$userConfig = json_decode(file_get_contents("user-config.json"),true);

foreach ($bangumi_list as &$array) {
	$id = $array['id'];
	// 清空
	$remark = "";
	$broadcast_site = "";
	$broadcast_id = "";
	$broadcast_url = "";
	$bangumi_id = $array['id'];
	$num = bgm_find($bangumi_id,$bgm_data);
	$title = $bgm_data['items'][$num]['titleTranslate']['zh-Hans'][0];
	$begin = $bgm_data['items'][$num]['begin'];
	$end = $bgm_data['items'][$num]['end'];
	$time_diff = dateDiff($begin);
	$status = "1"; // 0未开播 1开播了 2完结了 21完结信息提取完毕
	if ( $time_diff <> "y" ){
		$status = "0";
		$remark = $time_diff;
	}
	if ( $end <> "" ){
		if ( dateDiff($end) == "y" ){
			$status = "2";
			$remark = "fin";
		}
	}
	foreach ($bgm_data['items'][$num]['sites'] as &$value) {
		if ( $value['site'] == "bilibili" ){
			$broadcast_site = "bilibili";
			$broadcast_id = $value['id'];
			$broadcast_url = "https://bangumi.bilibili.com/anime/$broadcast_id";
		} 
	}
	if ( $broadcast_site == "" ){
		$broadcast_site = "dmhy";
		$broadcast_id = urlencode($title);
		$broadcast_url = "https://share.dmhy.org/topics/list?keyword=$broadcast_id&sort_id=2";
	}

	// user-config
	foreach ($userConfig as $u_array) {
		if ( $u_array['id'] == $id ) {
			$broadcast_site = "dmhy"; 
			$broadcast_id = urlencode($u_array['keyword']);
			$broadcast_url = "https://share.dmhy.org/topics/list?keyword=$broadcast_id&sort_id=2";
		}
	}

	//写入json
	$array['title'] = $title;
	$array['status'] = $status;
	$array['remark'] = $remark;
	$array['broadcast']['site'] = $broadcast_site;
	$array['broadcast']['id'] = $broadcast_id;
	$array['broadcast']['url'] = $broadcast_url;
}


file_put_contents("data/bgm-list.json", json_encode($bangumi_list));
logs(0);

?>