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
# $Id: view_gallery.php,v 1.5 2005/02/24 20:14:14 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$gallery = null;
if (isset($_GET['id'])) $gallery = $db->selectObject("imagegallery_gallery","id=".$_GET['id']);
	$view = (isset($_GET['view']) ? $_GET['view'] : "_view_all_galleries");

if ($gallery) {
	exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

	$loc = unserialize($gallery->location_data);

	$gallery->totalimages = $db->countObjects("imagegallery_image","gallery_id=".$gallery->id);
	$gallery->currentpage = (isset($_GET['page']) ? $_GET['page'] : 0);

	$gallery->perrow = $gallery->perrow != 0 ? $gallery->perrow : 3;
	$gallery->perpage = $gallery->perpage != 0 ? $gallery->perpage : 12;
	$gallery->totalpages = ceil($gallery->totalimages/$gallery->perpage);

	if ($gallery->totalpages == 0) $gallery->totalpages = 1;
	if ($gallery->currentpage >= $gallery->totalpages || $gallery->currentpage < 0) $gallery->currentpage = 0;
//	$gallery->prevpage = $gallery->currentpage-1;
//	$gallery->nextpage = $gallery->currentpage+1;

	$offset = 0;
	$num_items = 1000000; // just a large number to get everything
	if (isset($_GET['page'])) {
		$offset = $gallery->perpage*intval($_GET['page']);
		$num_items = $gallery->perpage;
	}

	$gallery->images = array();
	$gallery->images = $db->selectObjects("imagegallery_image","gallery_id=".$gallery->id. ' ORDER BY rank ASC '.$db->limit($num_items,$offset));
	for ($i = 0; $i < count($gallery->images); $i++) {
		$gallery->images[$i]->file = $db->selectObject("file","id=".$gallery->images[$i]->file_id);
		//eDebug($gallery->images[$i]->file);
		if(is_object($gallery->images[$i]->file)){
			if (file_exists(BASE.$gallery->images[$i]->file->directory."/".$gallery->images[$i]->thumbnail)) {
				$tsize = getimagesize(BASE.$gallery->images[$i]->file->directory."/".$gallery->images[$i]->thumbnail);
				//eDebug($gallery->images[$i]);
				$gallery->images[$i]->twidth = $tsize[0];
				$gallery->images[$i]->theight = $tsize[1];
			}		
			if (file_exists(BASE.$gallery->images[$i]->file->directory."/".$gallery->images[$i]->enlarged)) {
				$popsize = getimagesize(BASE.$gallery->images[$i]->file->directory."/".$gallery->images[$i]->enlarged);
				//eDebug($gallery->images[$i]);
				$gallery->images[$i]->popwidth = $popsize[0];
				$gallery->images[$i]->popheight = $popsize[1];
			}
		}
	}

	$table = array();
	for ($i = 0; $i < count($gallery->images);$i++) {
		$tmp = array();
		for ($j = 0; $j < $gallery->perrow && $i < count($gallery->images); $j++, $i++) {
			$tmp[] = $gallery->images[$i];
		}
		$table[] = $tmp;
	}

	$iloc = exponent_core_makeLocation($loc->mod,$loc->src);
	$iloc->int = $gallery->id;
	$gallery->permissions = array(
		"administrate"=>exponent_permissions_check("administrate",$iloc),
		"edit"=>exponent_permissions_check("edit",$iloc),
		"delete"=>exponent_permissions_check("delete",$iloc),
		"manage"=>exponent_permissions_check("manage",$iloc)
	);
	//$template = new template("imagegallerymodule","_view_gallery",$iloc);
	$template = new template("imagegallerymodule",$view,$iloc);
	$template->register_permissions(
		array("administrate","edit","delete","manage"),
		$iloc
	);

	//eDebug($gallery); exit();
	$galleries = array();
	$galleries[0] = $gallery;
	$template->assign("galleries",$galleries);
	//$template->assign("images",$images);
	$template->assign("table",$table);
/*
	$template->assign("currentpage",$currentpage);
	$template->assign("nextpage",$currentpage+1);
	$template->assign("prevpage",$currentpage-1);
	$template->assign("totalimages",$totalimages);
	$template->assign("totalpages",$totalpages);
*/
	$template->output();
} else {
	echo SITE_404_HTML;
}

?>
