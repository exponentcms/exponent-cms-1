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
# $Id: view_article.php,v 1.3 2005/03/13 18:57:28 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");
	if (exponent_permissions_check("manage",$loc)) {
		$submissions = $db->selectObjects("article_submission","location_data='".serialize($loc)."'");
		for($i=0; $i < count($submissions); $i++) {
			$file = $db->selectObject('file', 'id='.$submissions[$i]->file_id);
			$submissions[$i]->file = $file;
		}

		$template = new template("articlemodule","_view_submissions",$loc);
		$template->assign('submissions', $submissions);
		$template->register_permissions(array('manage'),$loc);
		$template->output();
	} else {
        	echo SITE_404_HTML;
        }
?>
