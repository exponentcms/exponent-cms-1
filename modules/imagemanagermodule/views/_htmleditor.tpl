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
<script language="javascript" src="{$smarty.const.PATH_RELATIVE}js/ImageManagerHTMLArea.js"></script>
<table cellpadding="2" cellspacing="0" border="0" width="100%" rules="rows" style="border: 1px solid lightgrey">
{foreach from=$grid item=row}
	<tr>
	{foreach from=$row item=item}
	{assign var=fid value=$item->file_id}
		<td align="center">
			<a class="mngmntlink imagemanager_mngmntlink" href="" onclick="setContent('{$files[$fid]->directory}/{$files[$fid]->filename}','{$smarty.const.PATH_RELATIVE}'); return false">
				<img src="thumb.php?file={$files[$fid]->directory}/{$files[$fid]->filename}&amp;scale={$item->scale}" border="0" />
			</a>
			<br />
			{$item->name}
		</td>
	{/foreach}
	</tr>
{foreachelse}
	<tr><td>{$_TR.no_images}</td></tr>
{/foreach}
</table>