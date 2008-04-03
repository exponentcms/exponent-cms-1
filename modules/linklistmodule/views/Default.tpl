
<div class="linklistmodule default">
	<div class="permissions">
		{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}		
	</div>
	{if $moduletitle}<h1>{$moduletitle}</h1>{/if}
	<ul>
	{foreach from=$links item=link}
		<li>
        	{permissions level=$smarty.const.UILEVEL_NORMAL}
            {if $permissions.edit == 1 || $permissions.delete == 1}
			<div class="itemactions">
				
				{if $permissions.edit == 1}
				<a href="{link action=edit id=$link->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
				{/if}
				{if $permissions.delete == 1}
				<a href="{link action=delete id=$link->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" onclick="return confirm('{$_TR.delete_link_confirm}');" /></a>
				{/if}
				
			</div>
            {/if}
            {/permissions}
			<span class="name">{$link->name}</span><br />
			<a title="{$link->name}" href="{$link->url}"{if $link->opennew == 1} target="_blank"{/if} class="link">{$link->url}</a>
			{if $link->description != ''}<p class="description">{$link->description}</p>{/if}
		</li>
	{foreachelse}
	<li align="center"><i>{$_TR.no_link}</i></li>
	{/foreach}
	</ul>
	<div class="actions">
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.create == 1}
			<a href="{link action=edit}">{$_TR.new_link}</a>
		{/if}
		{/permissions}
	</div>	
</div>
