{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="Configure this Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
<ul>
{foreach from=$links item=link}
<li>
	<a href="{$link->url}"{if $link->opennew == 1} target="_blank"{/if}>{$link->name}</a>
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.edit == 1}
	<a href="{link action=edit id=$link->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" /></a>
	{/if}
	{if $permissions.delete == 1}
	<a href="{link action=delet id=$link->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" onClick="return confirm('Are you sure you want to delete this link?');" /></a>
	{/if}
	{/permissions}
	{if $link->description != ''}<div style="margin-left: 20px;">{$link->description}</div>{/if}
</li>
{foreachelse}
<div align="center"><i>No links were found.</i></div>
{/foreach}
</ul>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create == 1}
<a href="{link action=edit}">New Link</a>
{/if}
{/permissions}