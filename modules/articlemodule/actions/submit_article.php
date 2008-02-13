<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: edit_article.php,v 1.4 2005/04/26 02:50:20 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");
	$config = $db->selectObject("articlemodule_config","location_data='".serialize($loc)."'");

	if (!empty($config->allow_submissions)) {
		$template = new template("articlemodule","_form_submit_article",$loc);
		$template->assign("config", $config);
		$template->output();
	} else {
		echo SITE_403_HTML;
	}
	
?>
