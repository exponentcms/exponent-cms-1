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
{assign var=haveFiles value=0}
{assign var=failed value=0}
{assign var=warn value=0}
<table cellspacing="0" cellpadding="0" border="0" width="100%">
{foreach from=$files_data item=mod_data key=modname }
<tr><td colspan="2"><b>{if $mod_data[0] != ''}{$mod_data[0]}{else}Unknown module : {$modname}{/if}</b></td></tr>
{foreach from=$mod_data[1] key=file item=status}
{assign var=haveFiles value=1}
<tr>
	<td style="padding-left: 2.5em;">{$file}</td>
	<td>
		{if $status == $smarty.const.SYS_FILES_SUCCESS}
		<span style="color: green;">passed</span>
		{elseif $status == $smarty.const.SYS_FILES_FOUNDFILE || $status == $smarty.const.SYS_FILES_FOUNDDIR}
		{assign var=warn value=1}
		<span style="color: orange;">file exists</span>
		{else}
		{assign var=failed value=1}
		<span style="color: red;">failed</span>
		{/if}
	</td>
</tr>
{foreachelse}<tr><td colspan="2"><i>No Files found</i></td></tr>
{/foreach}
{foreachelse}<tr><td colspan="2"><i>No Module Types Selected</i></td></tr>
{/foreach}
</table>
{if $haveFiles == 1}
<br />
<hr size="1" />
{if $failed == 0}
{if $warn == 1}<b>Note:</b> Continuing with the installation will overwrite existing files.  It is <b>highly recommended</b> that you ensure that you want to do this.<br /><br />{/if}
To install restore these files, click <a class="mngmntlink" href="{link action=page page=finish importer=files}">continue</a>.
{else}
Permissions on the webserver are preventing the restoration of these files.  Please make the necessary directories and/or files writable, and then reload this page to continue.
{/if}
{/if}