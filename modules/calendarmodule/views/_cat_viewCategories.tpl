{*
 *
 *}
<div class="form_title">Event Categories</div>
<div class="form_header">
Below is a list of categories that can be used for events in this calendar.
</div>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
{foreach from=$categories item=category}
	<td>{$category->name}</td>
	<td>
		<div style="width: 32px; height: 16px; background-color: {$category->color}">&nbsp;</div>
	</td>
</tr>
{foreachelse}
<tr>
	<td colspan="2" align="center"><i>No Categories</i></td>
</tr>
{/foreach}
</table>