{*
 *
 *}
<div class="form_title">Event Categories</div>
<div class="form_header">
Below is a list of categories that can be used for events in this calendar.
</div>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="header calendar_header">Name</td>
	<td class="header calendar_header">Color</td>
	<td class="header calendar_header"></td>
</tr>
{foreach from=$categories item=category}
<tr class="row {cycle values=odd_row,even_row}">
	<td>{$category->name}</td>
	<td>
		<div style="width: 32px; height: 16px; background-color: {$category->color}">&nbsp;</div>
	</td>
	<td>
		<a href="{link action=cat_editcategory id=$category->id}" class="mngmntlink calendar_mngmntlink">
			<img src="{$smarty.const.ICON_RELATIVE}edit.gif" border="0" />
		</a>
		<a href="{link action=cat_deletecategory id=$category->id}" class="mngmntlink calendar_mngmntlink">
			<img src="{$smarty.const.ICON_RELATIVE}delete.gif" border="0" />
		</a>
	</td>
</tr>
{foreachelse}
<tr>
	<td colspan="2" align="center"><i>No Categories</i></td>
</tr>
{/foreach}
</table>

<a href="{link action=cat_editcategory}" class="mngmntlink calendar_mngmntlink">New Category</a>