<div class="moduletitle news_moduletitle">Shared Content</div>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header news_header">Title</td>
		<td class="header news_header">Status</td>
		<td class="header news_header"></td>
	</tr>
	{foreach from=$items item=channel_item}
	<tr>
		<td>
			<a class="mngmntlink news_mngmntlink" target="_blank" href="{link action=view id=$channel_item[1]->item_id}">{$channel_item[0]->title}</a>
		</td>
		<td>
			{if $channel_item[0]->approved}
				{if $channel_item[1]->status == 0}Existing
				{elseif $channel_item[1]->status == 1}New Post
				{elseif $channel_item[1]->status == 2}Edited
				{elseif $channel_item[1]->status == 3}Deleted
				{/if}
			{else}
				In Approval
			{/if}
		</td>
		<td>
			{if $channel_item[0]->approved && $channel_item[1]->status > 0}
			<a href="{link action=accept_channelitem id=$channel_item[1]->id accept=1}">Accept</a>
			&nbsp;|&nbsp;
			<a href="{link action=accept_channelitem id=$channel_item[1]->id accept=0}">Decline</a>
			{/if}
		</td>
	</tr>
	{foreachelse}
	<tr><td colspan="3" align="center"><i>No Shared Content</i></td></tr>
	{/foreach}
</table>
<br />
<a href="#" onClick='openContentSelector("all","{$channel->id}","_contentPicker"); return false;'>Go get content</a>