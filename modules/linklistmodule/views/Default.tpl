
<div class="linklistmodule default">

	{if $moduletitle}<h1>{$moduletitle}</h1>{/if}
	<ul>
	{foreach name=links from=$links item=link}
	{math equation="x-1" x=$link->rank assign=prev}
	{math equation="x+1" x=$link->rank assign=next}
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
    			{if $smarty.foreach.links.first == 0}
    			<a href="{link action=rank_switch a=$link->rank b=$prev id=$link->id}">			
    				<img src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_previous}" alt="{$_TR.alt_previous}" />
    			</a>
    			{/if}
    			{if $smarty.foreach.links.last == 0}
    			<a href="{link action=rank_switch a=$next b=$link->rank id=$link->id}">
    				<img src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_next}" alt="{$_TR.alt_next}" />
    			</a>
    			{/if}
			</div>
            {/if}
            {/permissions}
			<strong>{$link->name}</strong>{br}
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
