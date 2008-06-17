
<div class="linklistmodule quick-links">

	{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}		

	{if $moduletitle}<h1>{$moduletitle}</h1>{/if}
	<ul>
	{foreach from=$links item=link}
		<li>
			<div class="itemactions">
				{permissions level=$smarty.const.UILEVEL_NORMAL}
				{if $permissions.edit == 1}
				<a href="{link action=edit id=$link->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit} - {$link->name}" /></a>
				{/if}
				{if $permissions.delete == 1}
				<a href="{link action=delete id=$link->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete} - {$link->name}" onclick="return confirm('{$_TR.delete_link_confirm}');" /></a>
				{/if}
				{/permissions}
			</div>
			<a title="{$link->description}" href="{$link->url}"{if $link->opennew == 1} target="_blank"{/if} >{$link->name}</a>
		</li>
	{foreachelse}
		<li align="center"><i>No Links Available</i></li>
	{/foreach}
	</ul>
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.create == 1}
	<div class="moduleactions">
	<a href="{link action=edit}">Create New Link</a>
	</div>	
	{/if}
	{/permissions}
</div>
