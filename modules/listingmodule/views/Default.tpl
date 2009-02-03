<div class="listingmodule default">
	{if $moduletitle}<h1>{$moduletitle}</h1>{/if}
	{foreach name=a from=$listings item=listing}
		{math equation="x-1" x=$listing->rank assign=prev}
		{math equation="x+1" x=$listing->rank assign=next}
		<div class="item {cycle values='odd,even'}">
			{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
				<div class="itemactions">
					{if $permissions.configure == 1 or $permissions.administrate == 1}
						{if $smarty.foreach.a.first == 0}
							<a href="{link action=rank_switch a=$listing->rank b=$prev id=$listing->id}">			
								<img src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_previous}" alt="{$_TR.alt_previous}" />
							</a>
						{/if}
						<a href="{link action=edit_listing id=$listing->id}" title="Edit this entry">
							<img src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
						</a>
						<a href="{link action=delete_listing id=$listing->id}" title="Delete this entry">
							<img src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
						</a>
						{if $smarty.foreach.a.last == 0}
							<a href="{link action=rank_switch a=$next b=$listing->rank id=$listing->id}">
								<img src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_next}" alt="{$_TR.alt_next}" />
							</a>
						{/if}
					{/if}
				</div>
			{/permissions}
			<div class="text">
				<h2><a href="{link action=view_listing id=$listing->id}">{$listing->name}</a></h2>
				{if $listing->picpath != ""}
					<a href="{link action=view_listing id=$listing->id}">
						<img class="listingimage" src="thumb.php?base={$smarty.const.BASE}&amp;file={$listing->picpath}&amp;width=100&amp;height=150"/>
					</a>
				{/if}
				<div class="bodycopy">
					{$listing->summary}
				</div>
			</div>
		</div>
	{foreachelse}
		<p><em>{$_TR.no_listings}</em></p>
	{/foreach}	

	{if $permissions.administrate == 1}
		<div class="moduleactions">
		    <a href="{link action=edit_listing}">{$_TR.create_item}</a>
		</div>
	{/if}

</div>
