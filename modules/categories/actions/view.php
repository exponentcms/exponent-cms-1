<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# Copyright (c) 2007 ACYSOS S.L. Modified by Ignacio Ibeas
# Added subcategory function
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

$loc->mod = $_GET['m'];
exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
$categories = category::levelTemplate($loc,0,0);
$template = new template($loc->mod,'_cat_viewCategories',$loc);
$template->assign('categories',$categories);
$template->output();

?>