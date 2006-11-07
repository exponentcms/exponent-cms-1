{if $moduletitle != ''}<div class="moduletitle MODULE_moduletitle">{$moduletitle}</div>{/if} 
{if $noupload == 1}Uploads are disabled ({$uploadError}){/if}
<table cellspacing="0" cellpadding="4" border="0" width="100%" />
<tr>
{foreach name=i from=$images item=image}
	{math equation="x-1" assign=this x=$smarty.foreach.i.iteration}
	<td valign="top" width="220" align="center">
		<a href="{link action=workshop id=$image->id}" />
		<img src="{$smarty.const.PATH_RELATIVE}thumb.php?id={$image->file_id}&constraint=1&width=200&height=200" border="0" style="border: 1px solid black;"/>
		<br />
		{$image->name}
		</a>
		<br />
		{* Left *}
		{if $smarty.foreach.i.first == 0}
		{math equation="x-2" assign="prev" x=$smarty.foreach.i.iteration}
		<a href="{link action=order a=$this b=$prev}">
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}left.png" />
		</a>
		{else}
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}left.disabled.png" />
		{/if}
		<a href="{link action=delete id=$image->id}" onClick="return confirm('Are you sure you want to delete this image and any changes you have made to it?');">
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" />
		</a>
		{* Right *}
		{if $smarty.foreach.i.last == 0}
		<a href="{link action=order a=$this b=$smarty.foreach.i.iteration}">
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}right.png" />
		</a>
		{else}
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}right.disabled.png" />
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