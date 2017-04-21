<?php
require("function.php");

$json = json_decode(file_get_contents("data/bgm-list.json"),true);

$index_num = "";
foreach ($json as $array) {
	$id = $array['id'];
	$bid = $array['broadcast']['id'];
	$bsite = $array['broadcast']['site'];
	$status = $array['status'];
	$weekday = $array['weekday'];
	$eps = "";
	//if ( file_exists("data/details/$id.json") ){
	//	$detail_json = json_decode(file_get_contents("data/details/$id.json"),true);
	//	$eps = $detail_json['eps'];
	//}
	if ( $status <> "0" ){
		if ( $bsite == "bilibili" ){
			$b_api = curl_get_contents("http://bangumi.bilibili.com/jsonp/seasoninfo/$bid.ver",0);
			$b_api = str_replace('seasonListCallback(', '', $b_api);
			$b_api = str_replace(');', '', $b_api);
			$b_api = json_decode($b_api, true);
			$weekday = $b_api['result']['weekday'];
			if ( $weekday == 0 ){
				$weekday = 7;
			}
			if ( array_key_exists('episodes', $b_api['result']) && count($b_api['result']['episodes']) <> 0 ){
				unset($eps);
				foreach ($b_api['result']['episodes'] as $b_array) {
					$b_sort = $b_array['index'];
					$b_url = $b_array['webplay_url'];
					$b_uptime = date('H:i',strtotime($b_array['update_time']));
					$eps[$b_sort]['url'] = $b_array['webplay_url'];
					$eps[$b_sort]['url'] = str_replace("http://", "https://", $eps[$b_sort]['url']);
					$eps[$b_sort]['uptime'] = $b_uptime;
					ksort($eps);
				}
				if ( $weekday == "-1" ) {
					$weekday = date( "N", strtotime( $b_api['result']['episodes'][0]['update_time'] ) );
				}
			}
			if ( $weekday == "-1" ){
				$weekday = 7;
			}
		}
		if ( $bsite == "dmhy" ){
			$rss = xmlToArray(curl_get_contents("http://share.dmhy.org/topics/rss/rss.xml?keyword=$bid&sort_id=2",0));
			if ( array_key_exists('item', $rss['channel']) ){
				$q = 0;
				$r_date = "";
				if ( array_key_exists(0, $rss['channel']['item']) ){ 
					foreach ($rss['channel']['item'] as $r_array) {
						if ( preg_match('/(简|繁|GB|双语|雙語|BIG5)/i', $r_array['title']) == 1 && preg_match_all('/(\[|【|第|索|-)( |)(\d{1,2}((|.)\d*|))( |_|v\d*|)(END|\(\d*\)|完|)(\]|】|话|集|話| |\[)/', $r_array['title'], $matches) <> 0){
							$r_sort = end($matches[3]);
							$r_sort = preg_replace('/^0+/', '', $r_sort);
							$r_fullurl = $r_array['enclosure']['@attributes']['url'];
							$r_cutend = strpos($r_fullurl, '&', 0);
							$r_url = substr($r_fullurl, 0, $r_cutend);
							$r_uptime = date('H:i',strtotime($r_array['pubDate']));
							$eps[$r_sort]['url'] = $r_url;
							$eps[$r_sort]['uptime'] = $r_uptime;
							$r_date[$q] = date( 'N', strtotime($r_array['pubDate']) );
							$q = $q + 1;
							$rr_date = date( 'N', strtotime($r_array['pubDate']) );
							$weekday = $rr_date;
						}
					}
				} else {
					if ( preg_match('/(简|繁|GB|双语|雙語|BIG5)/i', $rss['channel']['item']['title']) == 1 && preg_match_all('/(\[|【|第|索|-)( |)(\d{1,2}((|.)\d*|))( |_|v\d*|)(END|\(\d*\)|完|)(\]|】|话|集|話| |\[)/', $rss['channel']['item']['title'], $matches) <> 0){
						$r_sort = end($matches[3]);
						$r_sort = preg_replace('/^0+/', '', $r_sort);
						$r_fullurl = $rss['channel']['item']['enclosure']['@attributes']['url'];
						$r_cutend = strpos($r_fullurl, '&', 0);
						$r_url = substr($r_fullurl, 0, $r_cutend);
						$r_uptime = date('H:i',strtotime($r_array['pubDate']));
						$eps[$r_sort]['url'] = $r_url;
						$eps[$r_sort]['uptime'] = $r_uptime;
						$r_date[$q] = date( 'N', strtotime($rss['channel']['item']['pubDate']) );
						$q = $q + 1;
						$rr_date = date( 'N', strtotime($r_array['pubDate']) );
						$weekday = $rr_date;
					}
				}
				if ( count($r_date) > 1 ){
					ksort($eps);
			    }
			}
		}
	}
	$array['weekday'] = (int)$weekday;
	$array['eps'] = $eps;
	file_put_contents("data/details/$id.json", json_encode($array));
	$index[$index_num] = $id;
	$index_num = $index_num + 1;
}
file_put_contents("data/details/index.json", json_encode($index));

logs(0);








?>