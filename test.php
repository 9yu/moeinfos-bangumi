<?php
require("function.php");

$html = curl_get_contents("http://bangumi.tv/index/22363",0);
echo $html;

?>