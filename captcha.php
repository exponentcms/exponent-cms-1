<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
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