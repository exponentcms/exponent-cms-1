{if $noupload == 1}Uploads are disabled ({$uploadError}){/if}
<table cellspacing="0" cellpadding="4" style="border:none;" width="100%" />
<tr>
	<td valign="top" width="120" align="center">
		<b>Workspace</b><br />
	{foreach name=i from=$images item=image}
		{math equation="x-1" assign=this x=$smarty.foreach.i.iteration}
		<a href="{link action=workshop id=$image->id}" />
		<img src="{$smarty.const.PATH_RELATIVE}thumb.php?id={$image->file_id}&constraint=1&width=100&height=100" style="border:none;" style="border: 1px solid black;"/>
		<br />
		{$image->name}
		</a>
		<br />
		{* Up *}
		{if $smarty.foreach.i.first == 0}
		{math equation="x-2" assign="prev" x=$smarty.foreach.i.iteration}
		<a href="{link action=order a=$this b=$prev}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_up}" alt="{$_TR.alt_up}" />
		</a>
		{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.disabled.png" title="{$_TR.alt_up_disabled}" alt="{$_TR.alt_up_disabled}" />
		{/if}
		<a href="{link action=delete id=$image->id}" onClick="return confirm('Are you sure you want to delete this image and any changes you have made to it?');">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
		</a>
		{* Down *}
		{if $smarty.foreach.i.last == 0}
		<a href="{link action=order a=$this b=$smarty.foreach.i.iteration}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_down}" alt="{$_TR.alt_down}" />
		</a>
		{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.disabled.png" title="{$_TR.alt_down_disabled}" alt="{$_TR.alt_down_disabled}" />
		{/if}
		<br />
		<hr size="1" />
	{foreachelse}
		<i>You have no images</i><br />
		<hr size="1" />
	{/foreach}
		{if $noupload != 1}<a href="{link action=new_image}">Upload Image</a>{/if}
	</td>
	<td valign="top" width="400">
		{if $current->id == null}
		Please select an image on the left to work with.
		{else}
		<div class="moduletitle">{$current->name}{if $nochange == 1} (Unchanged){else} (Modified){/if}</div>
		
		<img src="{$smarty.const.PATH_RELATIVE}thumb.php?id={$working->file_id}&constraint=1&width=400&height=400" style="border:none;" style="border: 1px solid black;" alt="Loading image..." />
		{if $working->_width > 400 || $working->_height > 400}
		<div align="right">
			<a href="{$smarty.const.PATH_RELATIVE}{$working->_file->directory}/{$working->_file->filename}" target="_blank">(View Full-scale Image)</a>
		</div>
		{/if}
		<table cellspacing="2" cellpadding="0" border="0">
			<tr>
				<td style="font-weight: bold; background-color: #CCC;">File Name:</td>
				<td>{$current->_realname}</td>
			</tr>
			{*<tr>
				<td style="font-weight: bold; background-color: #CCC;">File Type:</td>
				<td>{$working->_imagetype}</td>
			</tr>
			*}<tr>
				<td style="font-weight: bold; background-color: #CCC;">Image Width:</td>
				<td>{$working->_width}</td>
			</tr>
			<tr>
				<td style="font-weight: bold; background-color: #CCC;">Image Height:</td>
				<td>{$working->_height}</td>
			</tr>
			<tr>
				<td style="font-weight: bold; background-color: #CCC;">Pixel Depth:</td>
				<td>{$working->_bitdepth}-bit</td>
			</tr>
			<tr>
				<td style="font-weight: bold; background-color: #CCC;">File Size:</td>
				<td>{$working->_filesize} {if $nochange == 0}({$sizediff}% of original){/if}</td>
			</tr>
		</table>
		
		<div style="border-left: 1.5em solid #CCC;">
			Image Operations<br />
			<ul>
				<li><a href="{link action=resize_form id=$current->id}">Resize</a></li>
				<li><a href="{link action=rotate_form id=$current->id}">Rotate</a></li>
				<li><a href="{link action=flip_form id=$current->id}">Flip</a></li>
				{*<li>Convert</li>
				*}
			</ul>
		</div>
		<div align="right">
		{if $nochange == 0}
			<a href="{link action=save_changes id=$current->id}">Save Changes</a>
			&nbsp;|&nbsp;
			<a href="{link action=revert_changes id=$current->id}">Revert to Original</a>
		{else}
			<a href="#" onClick="openSelector('imagemanagermodule','?module=imageworkshopmodule&action=imgmgr_move&file_id={$current->file_id}','containermodule','_sourcePicker'); return false;">Copy to an Image Manager</a>
		{/if}
		</div>
		<hr size="1" />
		<b>Original Image:</b><br />
		<img src="{$smarty.const.PATH_RELATIVE}thumb.php?id={$current->file_id}&constraint=1&width=400&height=400" style="border:none;" style="border: 1px solid black;"/>
		{if $current->_width > 400 || $current->_height > 400}
		<div align="right">
			<a href="{$smarty.const.PATH_RELATIVE}{$current->_file->directory}/{$current->_file->filename}" target="_blank">(View Full-scale Image)</a>
		</div>
		{/if}
		<table cellspacing="2" cellpadding="0" border="0">
			<tr>
				<td style="font-weight: bold; background-color: #CCC;">File Name:</td>
				<td>{$current->_realname}</td>
			</tr>
			{*<tr>
				<td style="font-weight: bold; background-color: #CCC;">File Type:</td>
				<td>{$current->_imagetype}</td>
			</tr>
			*}<tr>
				<td style="font-weight: bold; background-color: #CCC;">Image Width:</td>
				<td>{$current->_width}</td>
			</tr>
			<tr>
				<td style="font-weight: bold; background-color: #CCC;">Image Height:</td>
				<td>{$current->_height}</td>
			</tr>
			<tr>
				<td style="font-weight: bold; background-color: #CCC;">Pixel Depth:</td>
				<td>{$current->_bitdepth}-bit</td>
			</tr>
			<tr>
				<td style="font-weight: bold; background-color: #CCC;">File Size:</td>
				<td>{$current->_filesize}</td>
			</tr>
		</table>
		{/if}
	</td>
</tr>
</table>