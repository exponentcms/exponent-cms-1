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
# $Id: save_article.php,v 1.4 2005/03/18 02:58:26 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");
	$config = $db->selectObject("articlemodule_config","location_data='".serialize($loc)."'");
        
        if (!empty($config->allow_submissions)) {
		$directory = 'files/articlemodule/'.$loc->src;
		$file = file::update('file',$directory,null,time().'_'.$_FILES['file']['name']);
                if (is_object($file)) {
			$article = null;
			$article->location_data = serialize($loc);
                        $article->file_id = $db->insertObject($file,'file');
			$article->submitter_name = $_POST['submitter_name'];
			$article->submitter_email = $_POST['submitter_email'];
			$db->insertObject($article, 'article_submission');
		}	
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
?>
