<div class="headlinemodule default">
	{if $headline->headline != ""}<h1 class="top">{$headline->headline}</h1>{/if}
	{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
	{if $permissions.edit == 1}
		<a href="{link action=edit_listing id=$headline->id}"><img src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
	{/if}
	{/permissions}
</div>

