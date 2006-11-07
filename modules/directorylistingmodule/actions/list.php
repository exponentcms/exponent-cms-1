<?php

##################################################
#
# Copyright 2004 OIC Group, Inc. and OIC Group, Inc.
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
# $Id: edit_faq.php,v 1.2 2005/01/17 23:25:20 kessler44 Exp $
##################################################

if (!defined("EXPONENT")) exit("");
	
	//assign these so they are set regardless if we are calling this action by linking or through inclusion in the module show function
	$view = $_GET['view'];
	if (isset($_GET['title']) ){
		$title = $_GET['title'];
	}else{
		$title = "";
	}
	
	//define these so PHP doesn't bark
	$_REQUEST['sort']="";
	$_REQUEST['order']="";
	$n=0;
	//if path isn't set, set to /
	if(empty($_REQUEST['path']))
	{
	 $_REQUEST['path'] = '/';
	}
	
	// Create the template
	$template = new template("directorylistingmodule",$view,$loc);
		
	//output default perms and such
	$template->register_permissions(
		array("administrate","configure"),
		$loc);	
	$template->assign("moduletitle", $title);
	
	//define location of images used for folders and such.
	DEFINE("IMAGEROOT", "modules/directorylistingmodule/images/");  #CHANGE /images/ TO THE PATH OF THE ASSOCIATED IMAGES
	$template->assign("imageroot", IMAGEROOT);
	
	//grab the module config record.  we need the root_path.
	$config = $db->selectObject('directorylistingmodule_config',"location_data='".serialize($loc)."'");
	
	//double check default path for stupidity
	$config->root_path = realpath($config->root_path);
	
	//set error_reporting to 0 and set this variable to display any error messages to user
	error_reporting(0);
	$error_message = "";
	
	if ( !empty($config->root_path)){
			
		//check passed $path for mean and evil things
		$path = realpath($config->root_path . "/" . $_REQUEST['path']);
			
		//check for valid path first then check to make sure full path is underneath our chroot else default to chroot path		
		if ( !($path && strstr($path, $config->root_path)) ){ 
			$path = $config->root_path;
		}
		$path = $path . "/";
		
		//find total valid full path above chroot
		$backpath = substr($path, strlen($config->root_path) + 1, strlen($path) - strlen($config->root_path) );	
		//find second to last slash
		$pos = strrpos(substr($backpath, 0 , strlen($backpath)-1), "/");
		//get everything up to current directory
		$backpath = substr($backpath, 0, $pos );	
		$template->assign("backpath", $backpath);
		
		//clear the file statistics cache		
		clearstatcache();
		
		if ($handle = opendir($path) ){
			while ($file = readdir($handle)) { 
				if ($file != "." && $file != ".." ) { 
					//echo "Filename: " . $file . "IsNull: " . $file==null . " Filetype: " . filetype($file) . "<br/>";
					$fullpath = $path . $file;
					if (filetype($fullpath) == "dir") {
						//SET THE KEY ENABLING US TO SORT
						$n++;
						if($_REQUEST['sort']=="date") {
							$key = filemtime($fullpath) . ".$n";
						}else {
							$key = $n;
						}
						
						$mypath = substr($fullpath, strlen($config->root_path) + 1, strlen($fullpath) - strlen($config->root_path) );
						$dirs[$key] = array("name" => $file . "/", "path" => $mypath,"type" => "folder.gif", "size" => ".", "date" => filemtime($fullpath));
					} else {
						//SET THE KEY ENABLING US TO SORT
						$n++;
						if($_REQUEST['sort']=="date") {
							$key = filemtime($fullpath) . ".$n";
						}
						elseif($_REQUEST['sort']=="size") {
							$key = filesize($fullpath) . ".$n";
						}else {
							$key = $n;
						}
						
						switch (substr($file, -3)) {
						    case "jpg":
						      $img = "jpg.gif";
						      break;
						    case "gif":
						      $img = "gif.gif";
						      break;
						    case "zip":
						      $img = "zip.gif";
						      break;
						    case "png":
						      $img = "png.gif";
						      break;
						    case "avi":
						      $img = "move.gif";
						      break;
						    case "mpg":
						      $img = "move.gif";
						      break;
						    default:
						      $img = "what.gif";
						      break;
						}
						
						$mysize = round(filesize($fullpath));
						if ($mysize > 1048576) {
							$mysize = round($mysize/1048576,2) . " MB";							
						}elseif ($mysize > 1024) {
							$mysize = round(filesize($fullpath)/1024,2) . " KB";
						}else{
							$mysize = $mysize . " Bytes";
						}
						
						$files[$key] = array("name" => $file, "path" => $fullpath,"type" => $img, "size" => $mysize, "date" => filemtime($fullpath));
					}
				}
			}
		closedir($handle); 
		}else{
			$error_message = "Failed to open designated path.  Please check your settings.";
		}
		//$path = substr($path, strlen($config->root_path), strlen($file) - strlen($config->root_path));
	
	
	
		
		/*
		echo "<xmp>";
		print_r ($dirs);
		echo "</xmp>";	
		echo "<br />";
		echo "<xmp>";
			print_r ($files);
		echo "</xmp>";	
		*/
		
		$dirs = @array_values($dirs); 
		$files = @array_values($files);
		
		
		$template->assign("dirs",$dirs);
		$template->assign("files",$files);
		$template->assign("error_mes", $error_message);			
	}else{
		$error_message = "Invalid root path.  Please check your settings.";
	}
	$template->assign("error_mes", $error_message);
	$template->output();

	
?>

