<?php

#############################################################
# LINK MODULE
#############################################################
# Written by Eric Lestrade 
#
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

global $db;

if (exponent_permissions_check('import',$loc))
{
	$location_data=serialize($loc);
	$i=0;
	while(true) {
		if(isset($_POST['url'.$i])) {
			if(isset($_POST['get'.$i])) {
				$link=null;
				if ($_POST['name'.$i])
					$link->name= $_POST['name'.$i] ;
				$link->url=$_POST['url'.$i];
				$link->location_data=$location_data;
				$link->category_id=$_POST['category_id'.$i];
				$link->rank = $db->max('link', 'rank', 'location_data', "location_data='".serialize($loc)."'");
				if ($link->rank == null) {
					$link->rank = 0;
				} else {
					$link->rank += 1;
				}	
				$db->insertObject($link,"link");
			}
		}	else break;
		if($i>100) break;
		$i++;	
	}
	exponent_flow_redirect();
}
else echo SITE_403_HTML;

?>
