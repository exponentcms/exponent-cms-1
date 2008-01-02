{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Copyright (c) 2005-2006 Maxim Mueller
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

<div id="cermi-files">
{foreach name=i from=$files item=file}
	{if $file->name == ''}{assign var='filename' value=$file->filename}{else}{assign var='filename' value=$file->name}{/if}
		<div class="preview-wrapper">
			<div id="file-{$file->id}" class="file-preview">
				{if $file->is_image}
					<a href="#" onclick="select({$file->id});">
						<img id="img-{$file->id}" src="{$smarty.const.PATH_RELATIVE}thumb.php?id={$file->id}&square=75" title="{$filename}" alt="{$filename}" />
					</a>
				{else}
					{getfileicon id=$file->id}
					<br />
					<a href="#" onclick="window.opener.efm_pickedFile({$file->id},'{$file->directory}/{$file->filename}'); window.close(); return false;">Select</a>
				{/if}
				<div style="clear:both"></div>
			</div>
			<span class="file-name">{$filename|truncate:15:"..."}</span>
		</div>
{foreachelse}
	<i>{$_TR.no_files}</i>
{/foreach}
</div>
