<div class="listingmodule default">
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

	{if $moduletitle}<h1>{$moduletitle}</h1>{/if}
	{foreach name=a from=$listings item=listing}
	<div class="item">
		{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
		<div class="itemactions">
			{if $permissions.configure == 1 or $permissions.administrate == 1}

			{if $smarty.foreach.a.first == 0}
			<a href="{link action=rank_switch a=$listing->rank b=$prev id=$listing->id}">			
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_previous}" alt="{$_TR.alt_previous}" />
			</a>
			{/if}

			<a href="{link action=edit_listing id=$listing->id}" title="Edit this entry">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
			</a>
			<a href="{link action=delete_listing id=$listing->id}" title="Delete this entry">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
			</a>


			{if $smarty.foreach.a.last == 0}
			<a href="{link action=rank_switch a=$next b=$listing->rank id=$listing->id}">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_next}" alt="{$_TR.alt_next}" />
			</a>
			{/if}
			{/if}
		</div>
		{/permissions}
		<div class="text">
			{if $listing->picpath != ""}
				<a href="{link action=view_listing id=$listing->id}">
					<img class="listingimage" src="thumb.php?file={$listing->picpath}&width=100&height=150"/>
				</a>
			{/if}
			<h2><a href="{link action=view_listing id=$listing->id}">{$listing->name}</a></h2>
			<div id="bodycopy">
				{$listing->summary}
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
{foreachelse}
	<div><i>No listings found.</i></div>
{/foreach}	

{if $permissions.administrate == 1}
<div class="moduleactions">
    <a href="{link action=edit_listing}">New Listing</a>
</div>
{/if}

</div>
