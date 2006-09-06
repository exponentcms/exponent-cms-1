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
<div class="form_title">{$collection->name}</div>
<div class="form_header">
{$collection->description}
</div>
<script type="text/javascript">
{literal}
	function openWindow(filename,width,height) {
		if (width != 0) {
			width = width+20;
			if (width > 600) width = 600;
		} else {
			width = 400;
		}

		if (height != 0) {
			height = height+20;
			if (height > 400) height = 400;
		} else {
			height = 400;
		}

		window.open(filename,'image'+Math.random(),'status=no,status=no,width='+width+',height='+height);
		return false;
	}
{/literal}
</script>
<table>
<tr>
	{foreach name=i from=$files item=file}
	{if ($smarty.foreach.i.iteration - 1) mod 5 == 0}
</tr>
<tr>
	{/if}
	<td width="110" height="110" valign="top" align="center"{if $highlight_file == $file->id} id="highlight"{/if}>
		{if $file->is_image}
		<a href="{$smarty.const.PATH_RELATIVE}{$file->directory}/{$file->filename}" onclick="return openWindow('{$smarty.const.PATH_RELATIVE}{$file->directory}/{$file->filename}',{$file->image_width},{$file->image_height});" target="_blank">
			<img src="{$smarty.const.PATH_RELATIVE}thumb.php?id={$file->id}&constraint=1&width=100&height=100" border="0"/>
		</a>
		<br />
		<a href="{$smarty.const.PATH_RELATIVE}{$file->directory}/{$file->filename}" onclick="return openWindow('{$smarty.const.PATH_RELATIVE}{$file->directory}/{$file->filename}',{$file->image_width},{$file->image_height});" target="_blank">
			{$file->name}
		</a>
		{else}
		{getfileicon id=$file->id}
		<br />
			{if $file->name == ''}
			{$file->filename}
			{else}
				{$file->name}
			{/if}
		{/if}
		<a href="{link action=delete id=$file->id}" onclick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.delete_desc}" alt="{$_TR.delete_desc}" /></a>
	</td>
	{foreachelse}
	<td><i>{$_TR.no_files}</i></td>
	{/foreach}
</tr>
</table>
<br />
<a href="{link action=upload_file id=$collection->id}">{$_TR.new_file}</a>