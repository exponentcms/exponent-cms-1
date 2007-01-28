{*
 *
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 * $Id: Text_on_right.tpl,v 1.1 2005/05/20 15:13:19 freddirkse Exp $
 *}
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
        	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
<div class="moduletitle listing_moduletitle">{$moduletitle}</div>
<br>
{literal}
<SCRIPT LANGUAGE="JavaScript">
function ViewImage(ifile,ix,iy,ititle) { 
	var win;
	var sWidth;
	var sHeight;
	var NS = (document.layers) ? 1 : 0;
	win = window.open("","imageviewer","width="+ix+",height="+iy+",menubar=no,toolbar=no");
	if (NS) {
		sWidth = win.innerWidth;
		sHeight = win.innerHeight;
	} else {
		sWidth = win.document.body.clientWidth;
		sHeight = win.document.body.clientHeight;
	}
	if(sWidth!=ix || sHeight!=iy) {
		win.close();
		setTimeout("ViewImage('"+ifile+"',"+ix+","+iy+",'"+ititle+"')", 250);
		return;
	}
	win.document.open();
	win.document.write("<html><head><title>"+ititle+"</title>");
	win.document.write("</head><body>");
	win.document.write('<div style="position:absolute;width:'+ix+'px;height:'+iy+'px;left:0px;top:0px">');
	win.document.write("<img src="+ifile+"></div></body></html>");
	win.document.close();
}
</script>
{/literal}



<table cellspacing="0" cellpadding="0" style="border:none;" width="100%">
<tr>
{foreach name=a from=$listings item=listing}
{math equation="x-1" x=$listing->rank assign=prev}
{math equation="x+1" x=$listing->rank assign=next}
	<td align="center" valign="middle" width="220px">
		{if $listing->picpath == ""}
		<i>No picture available</i>
		{else}
		<a href="JavaScript: ViewImage('{$listing->picpath}',640,480,'')">
			<img style="border:none;" width="200px" height="200px" src="{$listing->picpath}"/>
		</a>
		{/if}
	</td><td align="center" valign="top">
		<br>
		{$listing->name}<br>{$listing->body}
		<br>
		
		{if $permissions.configure == 1 or $permissions.administrate == 1}
		
		{if $smarty.foreach.a.first == 0}
		<a href="{link action=rank_switch a=$listing->rank b=$prev id=$listing->id}">			
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}left.png" title="{$_TR.alt_previous}" alt="{$_TR.alt_previous}" />
		</a>
		{/if}
			
		<a href="{link action=edit_listing id=$listing->id}" title="Edit this entry">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
		</a>
		<a href="{link action=delete_listing id=$listing->id}" title="Delete this entry">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
		</a>
		
				
		{if $smarty.foreach.a.last == 0}
		<a href="{link action=rank_switch a=$next b=$listing->rank id=$listing->id}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}right.png" title="{$_TR.alt_next}" alt="{$_TR.alt_next}" />
		</a>
		{/if}
		
		
	</td>
	{/if}
	{if $smarty.foreach.a.iteration mod $__viewconfig.perrow == 0}</tr><tr><td>&nbsp;</td></tr><tr>{/if}
{foreachelse}
	<td align="center"><i>No listings found.</i></td>
{/foreach}
</tr>
</table>

{if $permissions.administrate == 1}
<br>
<a href="{link action=edit_listing}">New Listing</a>
<br>
{/if}
