{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 *}
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>{$smarty.const.SITE_TITLE} -- Archived Content</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" href="{$smarty.const.THEME_RELATIVE}style.css" />
		<link rel="stylesheet" href="{$smarty.const.THEME_RELATIVE}editor.css" />
		<meta name="Generator" value="Exponent Content Management System" />
	</head>
	
	<body style="margin: 0px; padding: 0px;">
	<table cellspacing="0" cellpadding="5" width="100%" border="0">
		<tr>
			<td width="70%">
				<b>Archived Content Selector</b>
			</td>
			<td width="30%" align="right">
				[ <a class="mngmntlink" href="{$smarty.const.PATH_RELATIVE}source_selector.php">Live Content</a> ]
			</td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="5" width="100%" border="0">
		<tr>
			<td colspan="2" style="background-color: #999; color: #fff; border-bottom: 1px solid #000; padding-bottom: .5em;">
				<i>Use this page to choose content from a module that has been removed from the site, but not deleted.</i>
			</td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="5" width="100%" border="0" height="80%">
		<tr>
			<td valign="top" style="padding: 5px;">
			{$modules_output}
			</td>
			
			<td width="80%" valign="top" style="border-left: 1px dashed #666;">
			{if $error == ''}{$main_output}
			{elseif $error == 'needmodule'}Please select a module from the left
			{elseif $error == 'nomodule'}<i>No archived modules were found.</i>
			{/if}
			</td>
		</tr>
	</table>
	</body>
</html>