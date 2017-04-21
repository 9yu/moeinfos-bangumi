<?php
require("function.php");

$index = json_decode(file_get_contents("data/details/index.json"),true);
$num = array( 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0 );
foreach ($index as $id) {
	$data = json_decode(file_get_contents("data/details/$id.json"),true);
	if ( $data['status'] <> 0 && $data['eps'] <> "" ) {
		foreach ($data['eps'] as $key => $value) {
			$sort = $key;
			$url = $value['url'];
			$uptime = $value['uptime'];
		}
		$latest_ep = array(
			"sort" => $sort,
			"url"  => $url,
			"uptime" => $uptime
			);
		$data['latest_ep'] = $latest_ep;
	}
	$weekday = $data['weekday'];
	unset($data['eps']);
	unset($data['weekday']);
	$week[$weekday][$num[$weekday]] = $data;
	$num[$weekday] = $num[$weekday] + 1;
}
file_put_contents("data/week.json", json_encode($week));
logs(0);
?>