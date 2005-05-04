{*
 *LICENSEME
 *}

<ul>
	{foreach from=$collections item=collection}
	<li><a href="{link action=view_collection id=$collection->id}">{$collection->name}</a></li>
	{/foreach}
</ul>

<a href="{link action=edit_collection}">New Collection</a>