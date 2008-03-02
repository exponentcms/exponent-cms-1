<div class="linklistmodule quicklinks">
	<h1>Quick Links:</h1>
	{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
	{if $permissions.administrate == 1}
		<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
		<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
	{/if}
	{if $permissions.configure == 1}
	        	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
	{/if}
	{if $permissions.configure == 1 or $permissions.administrate == 1}
		<br />
	{/if}
	{/permissions}
	<script type="text/javascript" charset="utf-8">
		function go()
		{ldelim}
			box = document.forms[0].quicklinks;
			destination = box.options[box.selectedIndex].value;
			if (destination) location.href = destination;
		{rdelim}
		
	</script>
	<form>
	<select id="quicklinks" name="quicklinks" onchange="go()">
	<option>Select a link...</option>
	{foreach from=$links item=link}
	<option value="{$link->url}" >
		{$link->name}		
	</option>
	{foreachelse}
	<option><i>No links were found.</i></option>
	{/foreach}
	</select>
	</form>
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.create == 1}
	<br>
	<a href="{link action=edit}">New Link</a>
	<a href="{link action=list}">Edit Links</a>
	{/if}
	{/permissions}
</div>
