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
 {permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" title="Assign user permissions on this Flash Module" alt="Assign user permissions on this News Feed System" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" title="Assign group permissions on this Flash Module" alt="Assign group permissions on this News Feed System" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=edit}"><img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}

<script language="javascript">
{literal}
function FlashInstalled()
{
	result = false;

	if (navigator.mimeTypes && navigator.mimeTypes["application/x-shockwave-flash"])
	{
		result = navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin;
	}
	else if (document.all && (navigator.appVersion.indexOf("Mac")==-1))
	{
		// IE Windows only -- check for ActiveX control, have to hide code in eval from Netscape (doesn't like try)
		eval ('try {var xObj = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");if (xObj) result = true; xObj = null;	} catch (e) {}');
	}
	//alert(result);
	return result;
}
{/literal}
</script>

<table cellpadding='0' cellspaceing='0' width='100%'>
	<tr>
		<td align='{$data->_align}'>
		{if $data->_noflash == 1}
			No Flash file has been loaded.
		{else}
		<script language="javascript">
			var flash_url = "{$data->_flashurl}";
			if (FlashInstalled() && flash_url != "") {ldelim}
				var temp;
				temp='<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"';
				temp+=' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" ';
				temp+='  WIDTH="{$data->width}" HEIGHT="{$data->height}">';
				temp+=' <PARAM NAME="movie" VALUE="{$data->_flashurl}"> <PARAM NAME="quality" VALUE="high"> <PARAM NAME="wmode" VALUE="transparent">  '; 
				temp+=' <EMBED src="{$data->_flashurl}" quality="high" bgcolor="{$data->bgcolor}"  ';
				temp+=' swLiveConnect="FALSE" WIDTH="{$data->width}" HEIGHT="{$data->height}"';
				temp+=' TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">';
				temp+=' </EMBED></OBJECT>';
				document.write(temp);
			 {rdelim}
			 else {ldelim}
				var temp;
				temp='<img src="{$data->_noflashurl}" width="{$data->width}" height="{$data->height}">';
				document.write(temp);
			 {rdelim}
		</script>
		{/if}
		</td>
	<tr>
</table>
