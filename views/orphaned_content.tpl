{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by James Hunt
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>{$smarty.const.SITE_TITLE} -- {$_TR.title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset={$smarty.const.LANG_CHARSET}" />
		<link rel="stylesheet" type="text/css" href="{$smarty.const.PATH_RELATIVE}tmp/css/exp-styles-min.css" />
		<meta name="Generator" value="Exponent Content Management System" />
	</head>
	
	<body style="margin: 0px; padding: 0px;">
	<table cellspacing="0" cellpadding="5" width="100%" border="0">
		<tr>
			<td width="70%">
				<b>{$_TR.selector}</b>
			</td>
			<td width="30%" align="right">
				[ <a class="mngmntlink" href="{$smarty.const.PATH_RELATIVE}source_selector.php">{$_TR.live_content}</a> ]
			</td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="5" width="100%" border="0">
		<tr>
			<td colspan="2" style="background-color: #999; color: #fff; border-bottom: 1px solid #000; padding-bottom: .5em;">
				<i>{$_TR.instruction}</i>
			</td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="5" width="100%" border="0" height="80%">
		<tr>
			<td valign="top" style="padding: 5px;">
			{$modules_output}
			</td>
			
			<td width="80%" valign="top" style="border-left: 1px dashed #666;">
			{clear}
			<div style="text-align: left;">
			{if $error == ''}{$main_output}
			{elseif $error == 'needmodule'}{$_TR.select_mod}
			{elseif $error == 'nomodule'}<i>{$_TR.no_modules}</i>
			{/if}
			</div>
			</td>
		</tr>
	</table>
	</body>
</html>
