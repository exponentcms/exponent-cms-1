{*
 *
 *}
<div class="moduletitle">Regenerating Search Index</div>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
{foreach from=$mods key=name item=status}
<tr class="row {cycle values=odd_row,even_row}">
	<td>{$name}</td>
	<td>{if $status == 1}<span style="color: green">Regenerated</span>{else}<span style="color: red; font-weight: bold">No Search Support</span>{/if}</td>
</tr>
{/foreach}
</table>