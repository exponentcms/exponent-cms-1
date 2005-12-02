<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

include_once(dirname(realpath(__FILE__)).'/pathos.php');
include_once(dirname(realpath(__FILE__)).'/subsystems/image.php');

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
	ob_end_clean();
	pathos_image_output($img,$sizeinfo);
}

?>