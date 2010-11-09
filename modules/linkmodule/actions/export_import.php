<?php

#############################################################
# LINKMODULE
#############################################################
# Copyright (c) 2007 Eric Lestrade 
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##############################################################

if (!defined("EXPONENT")) exit("");

if(exponent_permissions_check('import',$loc))
 {
	$template = new template("linkmodule","_export_import");
   
	// Don't know why it lost the location source. Needs to be recalled here.
    $template->assign('src',$loc->src);
     
    $template->register_permissions(array('edit','import'),$loc);
	$template->output();
 }
 else echo SITE_403_HTML;
?>