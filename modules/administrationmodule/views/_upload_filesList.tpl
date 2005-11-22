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
{assign var=haveFiles value=1}
{assign var=failed value=0}
{assign var=warn value=0}
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td class="header administration_header">{$_TR.file}</td>
	<td class="header administration_header">{$_TR.status}</td>
	<td class="header administration_header"></td>
</tr>
{foreach from=$files item=file}
<tr>
	<td>{$file.absolute}</td>
	<td>
	{if $file.canCreate == $smarty.const.SYS_FILES_SUCCESS}
	<span style="color: green;">{$_TR.passed}</span>
	{elseif $file.canCreate == $smarty.const.SYS_FILES_FOUNDFILE || $file.canCreate == $smarty.const.SYS_FILES_FOUNDDIR}
	{assign var=warn value=1}
	<span style="color: orange;">{$_TR.file_exists}</span>
	{else}
	{assign var=failed value=1}
	<span style="color: red;">{$_TR.failed}</span>
	{/if}
	</td>
	<td>
	{if $file.ext == "tpl" || $file.ext == "php"}
	{capture assign="filearg"}{$smarty.const.PATH_RELATVE}{$relative}{$file.absolute}{/capture}
		<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=viewcode file=$filearg}">
			{if $file.ext == "tpl"}{$_TR.view_template}{else}{$_TR.view_php}{/if}
		</a>
	{/if}
	</td>
</tr>
{foreachelse}
{assign var=haveFiles value=0}
<tr><td colspan="3">
<i>{$_TR.no_files}</i>
</td></tr>
{/foreach}
</table>
{if $haveFiles == 1}
<br />
<hr size="1" />
{if $failed == 0}
{if $warn == 1}{$_TR.overwrite_warning}<br /><br />{/if}
<a class="mngmntlink administration_mngmntlink" href="{link action=finish_install_extension}">{$_TR.install}</a>.
{else}
{$_TR.bad_permissions}
{/if}
{/if}