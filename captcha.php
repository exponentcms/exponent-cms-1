<?php

include_once('pathos.php');
include_once('subsystems/image.php');

$w = (isset($_GET['w']) ? $_GET['w'] : 200);
$h = (isset($_GET['h']) ? $_GET['h'] : 50);
$name = (isset($_GET['name']) ? $_GET['name'] : 'capcha_string');

if (pathos_sessions_isset($name)) {
	$str = pathos_sessions_get($name);
} else {
	$str = strtoupper(substr(md5(rand()),17,6));
	pathos_sessions_set($name,$str);
}

$img = pathos_image_captcha($w,$h,$str);
if ($img) {
	$sizeinfo = array('mime'=>'image/png');
	pathos_image_output($img,$sizeinfo);
}

?>