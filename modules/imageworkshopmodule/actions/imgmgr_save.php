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
# $Id: imgmgr_save.php,v 1.1 2005/04/18 01:27:23 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

// PERM CHECK
	$item = imagemanageritem::update($_POST,null);
	$item->location_data = serialize(exponent_core_makeLocation('imagemanagermodule',$_POST['imgmgr_src']));
	
	$file = $db->selectObject('file','id='.$_POST['file_id']);
	
	$new_dir = 'files/imagemanagermodule/'.$_POST['imgmgr_src'];
	$new_file = time() . '_'.$file->filename;
	copy(BASE.$file->directory.'/'.$file->filename,BASE.$new_dir.'/'.$new_file);
	
	$file->directory = $new_dir;
	$file->filename = $new_file;
	unset($file->id);
	$item->file_id = $db->insertObject($file,'file');
	$db->insertObject($item,'imagemanageritem');
	
	#echo '<script type="text/javascript">alert("Image Copied Successfully!"); window.close();</script>';
// END PERM CHECK

?>