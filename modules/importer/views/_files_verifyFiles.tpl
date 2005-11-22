{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
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
		<span style="color: orange;">{$_TR.file_exists}</span>
		{else}
		{assign var=failed value=1}
		<span style="color: red;">{$_TR.failed}</span>
		{/if}
	</td>
</tr>
{foreachelse}<tr><td colspan="2"><i>{$_TR.no_files}</i></td></tr>
{/foreach}
{foreachelse}<tr><td colspan="2"><i>{$_TR.no_modules}</i></td></tr>
{/foreach}
</table>
{if $haveFiles == 1}
<br />
<hr size="1" />
{if $failed == 0}
{if $warn == 1}{$_TR.overwrite_warning}<br /><br />{/if}
<a class="mngmntlink" href="{link action=page page=finish importer=files}">{$_TR.restore_files}</a>
{else}
{$_TR.bad_permissions}
{/if}
{/if}