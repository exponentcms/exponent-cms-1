{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
        	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>\n<img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
<div class="moduletitle tasklist_moduletitle">{$moduletitle}</div>
<form method="post" action="{$smarty.const.SCRIPT_RELATIVE}{$smarty.const.SCRIPT_FILENAME}">
<input type="hidden" name="module" value="tasklistmodule" />
<input type="hidden" name="action" value="update_checklist" />
<input type="hidden" name="src" value="{$__loc->src}" />
<table cellpadding="3" cellspacing="0" border="0" width="100%">
{foreach from=$tasks item=task}
<tr class="row {cycle values=odd_row,even_row}">
	<td width="16"><input name="item[{$task->id}]" type="checkbox" {if $task->completion == 100}checked {/if}/></td>
	<td>{$task->name}</td>
	<td align="right">
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit == 1}
		<a href="{link action=edit_task id=$task->id}">
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" />
		</a>
		{/if}
		{if $permissions.delete == 1}
		<a href="{link action=delete_task id=$task->id}" onClick="return confirm('Are you sure you want to delete this task?');">
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" />
		</a>
		{/if}
		{/permissions}
	</td>
</tr>
{/foreach}
</table>
<input type="submit" value="Update" />
</form>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create == 1}
<a href="{link action=edit_task}">New Task</a>
{/if}
{/permissions}