{if $moduletitle != ''}<div class="moduletitle MODULE_moduletitle">{$moduletitle}</div>{/if} 
{if $noupload == 1}Uploads are disabled ({$uploadError}){/if}
<table cellspacing="0" cellpadding="4" style="border:none;" width="100%" />
<tr>
{foreach name=i from=$images item=image}
	{math equation="x-1" assign=this x=$smarty.foreach.i.iteration}
	<td valign="top" width="220" align="center">
		<a href="{link action=workshop id=$image->id}" />
		<img src="{$smarty.const.PATH_RELATIVE}thumb.php?id={$image->file_id}&amp;constraint=1&amp;width=200&amp;height=200" style="border: 1px solid black;" alt="{$image->name}" />
		<br />
		{$image->name}
		</a>
		<br />
		{* Left *}
		{if $smarty.foreach.i.first == 0}
		{math equation="x-2" assign="prev" x=$smarty.foreach.i.iteration}
		<a href="{link action=order a=$this b=$prev}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}left.png" title="{$_TR.alt_previous}" alt="{$_TR.alt_previous}" />
		</a>
		{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}left.disabled.png" title="{$_TR.alt_previous_disabled}" alt="{$_TR.alt_previous_disabled}" />
		{/if}
		<a href="{link action=delete id=$image->id}" onClick="return confirm('Are you sure you want to delete this image and any changes you have made to it?');">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
		</a>
		{* Right *}
		{if $smarty.foreach.i.last == 0}
		<a href="{link action=order a=$this b=$smarty.foreach.i.iteration}">
			<img class="mngmnt_icon" style="border:none; text-align:center; margin-right: 5px;" src="{$smarty.const.ICON_RELATIVE}right.png" title="{$_TR.next}" alt="{$_TR.next}" />
		</a>
		{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}right.disabled.png" title="{$_TR.alt_next_disabled}" alt="{$_TR.alt_next_disabled}" />
		{/if}
	</td>
	{if $smarty.foreach.i.iteration mod 2 == 0}
	</tr><tr>
	{/if}
	{foreachelse}
	<td><i>You have no images</i></td>
	{/foreach}
</tr>
</table>
{if $noupload != 1}<a href="{link action=new_image}">Upload Image</a>{/if}