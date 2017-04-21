<?php
require("../function.php");

file_put_contents("../data/bgm-data.json", curl_get_contents("https://raw.githubusercontent.com/bangumi-data/bangumi-data/master/dist/data.json",1));
logs(1);
?>