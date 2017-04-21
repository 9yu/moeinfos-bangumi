<?php
require("function.php");

$week = json_decode(file_get_contents("data/week.json"),true);

foreach ($week as $weekday) {
	foreach ($weekday as $details) {
		$id = $details['id'];
		$title = $details['title'];
		$status = $details['status'];
		$bgmurl = $details['bgmurl'];
		$bsite = $details['broadcast']['site'];
		$burl = $details['broadcast']['url'];
		if ( isset($details['latest_ep']) == false ) {
			if ( $status <> 0 ) {
				$error_list[] =  '<li><a href="'. $bgmurl . '" target="_blank">' . $id . " " . $title . '</a><span><a href="'. $burl .'" target="_blank">' . $bsite . '</a></span></li>';// 开播了没有集数
			}
		}
		if ( $status == 0 ) {
			$zero_list[] = '<li><a href="'. $bgmurl . '" target="_blank">' . $id . " " . $title . '</a></li>'; // 未开播
		}
	}
}

$li_list = "";
if ( isset($zero_list) ) {
	foreach ($zero_list as $zero_list_p) {
		$li_list .=  $zero_list_p;
	}
}
echo '<header><h2>Moeinfos Bangumi 记录</h2><button><a href="fetch/bangumi-list.php" target="_blank">刷新 bangumi-list</a></button>
<button><a href="fetch/bgm-data.php" target="_blank">刷新 bgm-data</a></button>
<button><a href="bgm-list.php" target="_blank">再生成 bgm-list</a></button>
<button><a href="details.php" target="_blank">再生成 detail files</a></button>
<button><a href="week.php" target="_blank">再生成 week 列表</a></button></header><div class="up"><ul class="list"><h3>未开播</h3>' . $li_list . '</ul>';

$li_list = "";
if ( isset($error_list) ) {
	foreach ($error_list as $error_list_p) {
		$li_list .=  $error_list_p;
	}
}
echo '<ul class="list"><h3>开播了没有集数</h3>' . $li_list . '</ul></div>';

$php_list = array(
		"fetch/bangumi-list.php",
		"fetch/bgm-data.php",
		"bgm-list.php",
		"details.php",
		"week.php"
	);


$notice_list = "";
foreach ($php_list as $php_list_p) {
	$if = file_exists("$php_list_p.errors.log");
	if ($if == false) {
		$notice_list .= '<li>' . $php_list_p . "<span>没有错误日志</span>" . '</li>';
	} else {
		$filemtime = filemtime("$php_list_p.errors.log");
		$filemtime = date("Y-m-d H:i:s", $filemtime);
		$notice_list .= '<li>' .$php_list_p . "<span>最近一次错误发生在 " . $filemtime . '</span></li>';
	}
	clearstatcache();
	
}
echo '<div class="down"><ul class="list"><h3>错误日志</h3>' . $notice_list . '</ul>';

$notice_list = "";
foreach ($php_list as $php_list_p) {
	$php_list_p = basename($php_list_p);
	$if = file_exists("logs/$php_list_p.log");
	if ($if == false) {
		$notice_list .= '<li>' . $php_list_p . "<span>没有运行日志</span>" . '</li>';
	} else {
		$filemtime = filemtime("logs/$php_list_p.log");
		$filemtime = date("Y-m-d H:i:s", $filemtime);
		$notice_list .= '<li>' .$php_list_p . "<span>最近一次运行在 " . $filemtime . '</span></li>';
	}
	clearstatcache();
	
}
echo '<ul class="list"><h3>运行日志</h3>' . $notice_list . '</ul></div>';
error_reporting(0);

?>
<head>
	<link rel="stylesheet" type="text/css" href="normalize.css" />
	<style type="text/css">
		ul {
			width: 400px;
			border: 1px solid #EEEEEE;
			margin: 10px;
		}
		.list {
			float: left;
		}
		div {
			float: left;
		}
		li {
			line-height: 30px;
			font-size: 16px;
		}
		h2,h3,li,a {
			color: #333;
		}
		li:last-child {
			padding-bottom: 16px;
		}
		.list span {
			float: right;
			color: #666;
			font-size: 14px;
			padding-right: 10px;
		}
		.list span a {
			color: #666;
		}
		body {
			width: 980px;
			margin: 0 auto;
		}
		h2 {
			margin: 14px 25px 6px 16px;
			font-weight: normal;
			color: #555;
			float: left;
		}
		header button {
			float: left;
			margin: 16px 4px 6px;
		}

	</style>
</head>