{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
/if}
{if $permissions.configure == 1}
        	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>\n<img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
<div class="moduletitle tasklist_moduletitle">{$moduletitle}</div>
<table cellpadding="3" cellspacing="0" border="0" width="100%">
{foreach from=$tasks item=task}
<tr class="{cycle values=odd_row,even_row}">
	<td width="16"><input type="checkbox" onClick="return false;" {if $task->completion == 100}checked {/if}/></td>
	<td>{$task->name}</td>
	<td width="40">
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
	<td>
		{if $task->priority > 5}
			<span style="color: red; font-weight: bold;">
			{if $task->priority == 9}!!!!
			{elseif $task->priority == 8}!!!
			{elseif $task->priority == 7}!!
			{elseif $task->priority == 6}!
			{/if}
			</span>
		{/if}
	</td>
	<td>{$task->completion}%</td>
	<td width="50%">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				{if $task->completion != 0}
				<td width="{$task->completion}%" bgcolor="#00FF00" style="border: 1px solid black;">&nbsp;</td>
				{/if}
				{if $task->left != 0}
				<td width="{$task->left}%" bgcolor="#FF0000" style="border: 1px solid black">&nbsp;</td>
				{/if}
			</tr>
		</table>
	</td>
</tr>
{foreachelse}
<tr><td align="center"><i>No tasks have been created.</td></tr>
{/foreach}
</table>
{$num_completed} {plural count=$num_completed singular="task has" plural="tasks have"} been completed.<br />
{$num_uncompleted} {plural count=$num_uncompleted singular="task is" plural="tasks are"} not completed.<br />
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create == 1}
<a href="{link action=edit_task}">New Task</a>
{/if}
{/permissions}