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
<html>
	<head>
		<title>{$_TR.form_title}</title>
		<style type="text/css">
			{literal}
			body {
				margin: 0px;
				padding: 0px;
			}

			div.imagecollection_sidebar {
				background-color: #666;
				border-right: 2px solid black;
				border-bottom: 2px solid black;
				font-size: 11pt;
				position: absolute;
				left: 0px;
				top: 0px;
				padding: 1em;
				padding-left: .5em;
				padding-top: .5em;
				width: 200px;
			}

			div.imagecollection_sidebar ul {
				margin: 0px;
				padding: 0px;
			}
			div.imagecollection_sidebar ul li {
				list-style: none;
			}

			div.imagecollection_sidebar a {
				color: white;
				text-decoration: none;
				font-weight: bold;
			}

			div.imagecollection_sidebar a:hover {
				color: black;
				text-decoration: underline;
				font-weight: bold;
			}

			div.imagecollection_previews {
				padding: 1em;
				margin-bottom: 30px;
				margin-left: 220px;
			}

			div.scroller {
				width: 650px;
                height: 600px;
				overflow: auto;
			}

			div.imagecollection_previews table tr td {
				text-align: center;
				border: 1px solid #666;
				font-size: 10px;
				padding: 5px;
			}

			div.imagecollection_previews table tr td a {
				text-decoration: none;
				color: darkblue;
				font-weight: bold;
			}

			div.imagecollection_upload {
				background-color: #999;
				border-top: 2px solid black;
				border-left: 2px solid black;
				padding: .2em;
				text-align: right;
				font-size: 11px;
				position: fixed;
				bottom: 0;
				right: 0px;
				height: 30px;
			}

			div.imagecollection_previews table tr td img {
				border: 1px solid white;
			}

			div.imagecollection_previews table tr td#highlight img {
				background-color: #CCC;
				border: 6px solid black;
			}

			div.imagecollection_previews table tr td:hover img {
				border: 1px solid black;
			}
			{/literal}
		</style>
		<script type="text/javascript" src="../../../exponent.js.php"></script>
		<script type="text/javascript">
		{literal}
			// fckedior unfortunately does not ship with our efm_pickedFile() :)
			function FCKeditor_pickedFile(id, pickedFile) {
				window.opener.SetUrl(eXp.PATH_RELATIVE + pickedFile);
			}
			if (typeof window.opener.FCK == "object" && typeof window.opener.oEditor == "object") {
				//attach this to the window that opened the filemanager(=FCKeditor`s image insert)
				window.opener.efm_pickedFile = FCKeditor_pickedFile;
			}



			function openWindow(filename,width,height) {
				if (width != 0) {
					width = width+40;
					if (width > 600) width = 600;
				} else {
					width = 400;
				}

				if (height != 0) {
					height = height+40;
					if (height > 400) height = 400;
				} else {
					height = 400;
				}

				//window.open(filename,'image'+Math.random(),'status=no,status=no,width='+width+',height='+height);
				window.open(filename,'image'+Math.random(),'status=yes,status=yes,width='+width+',height='+height);
				return false;
			}
		{/literal}
		</script>
	</head>
	<body>
		<div class="imagecollection_sidebar">
			<ul>
				<li><a href="?id=0"><i>{$_TR.uncategorized}</i></a></li>
				{foreach from=$collections item=collect}
				<li><a href="?id={$collect->id}">{$collect->name}</a></li>
				{/foreach}
			</ul>
		</div>
		<div class="imagecollection_previews">
			<div style="font-size: larger;">{$collection->name} ({$numfiles} {plural singular=file plural=files count=$numfiles})</div>
			<div style="padding-left: 2em; margin-bottom: 1em; border-bottom: 2px solid black;">{$collection->description}</div>
			<div class="scroller">
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
							{if $file->name == ''}
							{$file->filename}
							{else}
								{$file->name}
							{/if}
						</a>
						<br />
						<a href="#" onclick="window.opener.efm_pickedFile({$file->id},'{$file->directory}/{$file->filename}'); window.close(); return false;">

							<img src="{$smarty.const.PATH_RELATIVE}thumb.php?id={$file->id}&constraint=1&width=100&height=100" title="Click to select" alt="Click to select" border="0"/>
						</a>
						<br />
						<a href="{$smarty.const.PATH_RELATIVE}{$file->directory}/{$file->filename}" onclick="return openWindow('{$smarty.const.PATH_RELATIVE}{$file->directory}/{$file->filename}',{$file->image_width},{$file->image_height});" target="_blank">
							{$_TR.show_full}
						</a>
						<br />
						{else}
						{getfileicon id=$file->id}
						<br />
							{if $file->name == ''}
							{$file->filename}
							{else}
								{$file->name}
							{/if}
							<br />
							<a href="#" onclick="window.opener.efm_pickedFile({$file->id},'{$file->directory}/{$file->filename}'); window.close(); return false;">Use</a>
						{/if}
						<a href="{link action=delete id=$file->id module=filemanagermodule}" onclick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.delete_desc}" alt="{$_TR.delete_desc}" /></a>
					</td>
					{foreachelse}
					<td><i>{$_TR.no_files}</i></td>
					{/foreach}
				</tr>
			</table>
			</div>
		</div>
		<div class="imagecollection_upload">
			<form method="post" action="upload_standalone.php" enctype="multipart/form-data">
			<input type="hidden" name="collection_id" value="{$collection->id}" />
			<input type="hidden" name="name" value="" />
			{$_TR.img_upload} <input type="file" name="file" />
			<input type="submit" value="Go" />
			</form>
		</div>
	</body>
</html>
