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

if (!defined('PATHOS')) exit('');

$loc->mod = $_GET['m'];
pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
$categories = $db->selectObjects('category',"location_data='".serialize($loc)."'");
$template = new template($loc->mod,'_cat_viewCategories',$loc);
$template->assign('categories',$categories);
$template->output();

?>