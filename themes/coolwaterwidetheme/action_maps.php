<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

if (!defined('EXPONENT')) exit('');

 return array(
//         'newsmodule'=>array(
//                'view'=>'_test',
//                'view_all_news'=>'Full Body'),
         'calendarmodule'=>array(
				'viewmonth'=>'Full Body'),
         'imagegallerymodule'=>array(
				'Slideshow'=>'Full Body'),
         '*'=>array(
                'groupperms'=>'Full Body',
				'userperms'=>'Full Body'),				 
         'formbuilder'=>array(
                'view_data'=>'Full Body'),
	);

?>
