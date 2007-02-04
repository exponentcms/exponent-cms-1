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

	//start test code
	DEFINE("IMAGEROOT", "modules/directorylistingmodule/images/");  #CHANGE /images/ TO THE PATH OF THE ASSOCIATED IMAGES

	$textcolor = "#FFFFFF";           #TEXT COLOUR
	$bgcolor = "#535353";             #PAGE BACKGROUND COLOUR
	$normalcolor = "#FFFFFF";         #TABLE ROW BACKGROUND COLOUR
	$highlightcolor = "#006699";      #TABLE ROW BACKGROUND COLOUR WHEN HIGHLIGHTED
	$headercolor = "#CCCCCC";         #TABLE HEADER BACKGROUND COLOUR
	$bordercolor = "#202750";         #TABLE BORDER COLOUR

	clearstatcache();
	if (!isset($PHP_SELF)) {$PHP_SELF = $_SERVER['PHP_SELF'];}
	$n=0;

	if(empty($_REQUEST['sort']))
	{
	  $_REQUEST['sort'] = 'date';
	}

	if(empty($_REQUEST['order']))
	{
	  $_REQUEST['order'] = "desc";
	}

	if(empty($_REQUEST['traverse']))
	{
	 $_REQUEST['traverse'] = '/';
	}else{
	 $_REQUEST['traverse'] = '/' . $_REQUEST['traverse'];
	}

	//echo $config->root_path . $_REQUEST['traverse'] . "<br />";

	if ($handle = opendir($config->root_path . $_REQUEST['traverse']) ){
	  while (false !== ($file = readdir($handle))) {
	    if ($file != "." && $file != ".." && $file != substr($PHP_SELF, -(strlen($PHP_SELF) - strrpos($PHP_SELF, "/") - 1))) {

		  if (filetype($file) == "dir") {
			  //SET THE KEY ENABLING US TO SORT
			  $n++;
			  if($_REQUEST['sort']=="date") {
				$key = filemtime($file) . ".$n";
			  }
			  else {
				$key = $n;
			  }
		  $dirs[$key] = $file . "/";
	      }
	      else {
			  //SET THE KEY ENABLING US TO SORT
			  $n++;
			  if($_REQUEST['sort']=="date") {
				$key = filemtime($file) . ".$n";
			  }
			  elseif($_REQUEST['sort']=="size") {
				$key = filesize($file) . ".$n";
			  }
			  else {
				$key = $n;
			  }
		  $files[$key] = $file;
	      }
	    }
	  }
	closedir($handle);
	}

	#USE THE CORRECT ALGORITHM AND SORT OUR ARRAY
	if($_REQUEST['sort']=="date") {
		@ksort($dirs, SORT_NUMERIC);
		@ksort($files, SORT_NUMERIC);
	}
	elseif($_REQUEST['sort']=="size") {
		@natcasesort($dirs);
		@ksort($files, SORT_NUMERIC);
	}
	else {
		@natcasesort($dirs);
		@natcasesort($files);
	}

	#ORDER ACCORDING TO ASCENDING OR DESCENDING AS REQUESTED
	if($_REQUEST['order']=="desc" && $_REQUEST['sort']!="size") {$dirs = array_reverse($dirs);}
	if($_REQUEST['order']=="desc") {$files = array_reverse($files);}
	$dirs = @array_values($dirs); $files = @array_values($files);

	echo "<table width=\"\" border=\"0\" cellspacing=\"0\" align=\"center\"><tr bgcolor=\"$headercolor\"><td colspan=\"2\" id=\"bottomborder\">";
	if($_REQUEST['sort']!="name") {
	  echo "<a href=\"".$_SERVER['PHP_SELF']."?sort=name&order=asc\">";
	}
	else {
	  if($_REQUEST['order']=="desc") {#
	    echo "<a href=\"".$_SERVER['PHP_SELF']."?sort=name&order=asc\">";
	  }
	  else {
	    echo "<a href=\"".$_SERVER['PHP_SELF']."?sort=name&order=desc\">";
	  }
	}
	echo "File</td><td id=\"bottomborder\" width=\"50\"></a>";
	if($_REQUEST['sort']!="size") {
	  echo "<a href=\"".$_SERVER['PHP_SELF']."?sort=size&order=asc\">";
	}
	else {
	  if($_REQUEST['order']=="desc") {#
	    echo "<a href=\"".$_SERVER['PHP_SELF']."?sort=size&order=asc\">";
	  }
	  else {
	    echo "<a href=\"".$_SERVER['PHP_SELF']."?sort=size&order=desc\">";
	  }
	}
	echo "Size</td><td id=\"bottomborder\" width=\"120\" nowrap></a>";
	if($_REQUEST['sort']!="date") {
	  echo "<a href=\"".$_SERVER['PHP_SELF']."?sort=date&order=asc\">";
	}
	else {
	  if($_REQUEST['order']=="desc") {#
	    echo "<a href=\"".$_SERVER['PHP_SELF']."?sort=date&order=asc\">";
	  }
	  else {
	    echo "<a href=\"".$_SERVER['PHP_SELF']."?sort=date&order=desc\">";
	  }
	}
	echo "Date Modified</a></td></tr>";

	$arsize = sizeof($dirs);
	for($i=0;$i<$arsize;$i++) {
	  echo "\t<tr class=\"row\" onmouseover=\"this.style.backgroundColor='$highlightcolor'; this.style.cursor='hand';\" onMouseOut=\"this.style.backgroundColor='$normalcolor';\" onClick=\"window.location.href='" . $dirs[$i] . "';\">";
	  echo "\t\t<td width=\"16\"><img src=\"" . IMAGEROOT . "folder.gif\" width=\"16\" height=\"16\" alt=\"Directory\"></td>";
	  echo "\t\t<td><a href=\"?action=list&module=directorylistingmodule&traverse=" . substr($dirs[$i], 0, strlen($dirs[$i]) -1 ) . "\">" . $dirs[$i] . "</a></td>";
	  echo "\t\t<td width=\"50\" align=\"left\">-</td>";
	  echo "\t\t<td width=\"\" align=\"left\" nowrap>" . date ("M d Y h:i:s A", filemtime($dirs[$i])) . "</td>";
	  echo "\t</tr>";
	}

	$arsize = sizeof($files);
	for($i=0;$i<$arsize;$i++) {
	  switch (substr($files[$i], -3)) {
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

	  echo "\t<tr class=\"row\" onmouseover=\"this.style.backgroundColor='$highlightcolor'; this.style.cursor='hand';\" onMouseOut=\"this.style.backgroundColor='$normalcolor';\" onClick=\"window.location.href='" . $files[$i] . "';\">\r\n";
	  echo "\t\t<td width=\"16\"><img src=\"" . IMAGEROOT . "$img\" width=\"16\" height=\"16\" alt=\"Directory\"></td>\r\n";
	  echo "\t\t<td><a href=\"" . $files[$i] . "\">" . $files[$i] . "</a></td>\r\n";
	  echo "\t\t<td width=\"50\" align=\"left\">" . round(filesize($files[$i])/1024) . "KB</td>\r\n";
	  echo "\t\t<td width=\"120\" align=\"left\" nowrap>" . date ("M d Y h:i:s A", filemtime($files[$i])) . "</td>\r\n";
	  echo "\t</tr>\r\n";
	}
	echo "</table>";
	//end test code
?>