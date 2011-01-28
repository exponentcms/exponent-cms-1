<div class="listingmodule listings">
	{if $moduletitle}<h2>{$moduletitle}</h2>{/if}
	{if $config->description}{$config->description}{/if}
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
						{if $permissions.approve == 1 || $listing->permissions.approve == true}
							<a class="mngmntlink listing_mngmntlink" href="{link module=workflow datatype=listing m=listingmodule s=$__loc->src action=revisions_view id=$listing->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
						{/if}
						{if $smarty.foreach.a.last == 0}
							<a href="{link action=rank_switch a=$next b=$listing->rank id=$listing->id}">
								<img src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_next}" alt="{$_TR.alt_next}" />
							</a>
						{/if}
					{/if}
				</div>
			{/permissions}
			<div class="text">
				<h3><a href="{link action=view_listing id=$listing->id}">{$listing->name}</a></h3>
				{if $listing->picpath != ""}
					<a href="{link action=view_listing id=$listing->id}">
						<img class="listingimage" src="{$smarty.const.URL_FULL}/thumb.php?base={$smarty.const.BASE}&amp;file={$listing->picpath}&amp;width=100&amp;height=150" alt="{$listing->name}" />
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

	{if $pagecount>1}
	<div class="pagination">
		Page({$curpage} of {$pagecount})
		{if $curpage != 1}
			<a class="listing_page_link" href="{link action="view" view="Default" page=1}"><<</a>&nbsp;
		  	<a class="listing_page_link" href="{link action="view" view="Default" page=$curpage-1}"><</a>
		{/if}
		{if $downlimit>1 }...{/if}
		{section name=pages start=$downlimit loop=$pagecount+1 max=$uplimit} 
		  <a class="listing_page_link" href="{link action="view" view="Default" page=$smarty.section.pages.index}">
		    {if $curpage == $smarty.section.pages.index}
		    [{$smarty.section.pages.index}]
		    {else}
		    {$smarty.section.pages.index}
		    {/if}
		  </a>  
		{/section}
		{if $uplimit<$pagecount }...{/if}
		{if $curpage != $pagecount}
			<a class="listing_page_link" href="{link action="view" view="Default" page=$curpage+1}">></a>&nbsp;
			<a class="listing_page_link" href="{link action="view" view="Default" page=$pagecount}">>></a>
		{/if}
	</div>
	{/if}
	
	{if $permissions.administrate == 1}
		<div class="moduleactions">
		    <a href="{link action=edit_listing}">{$_TR.create_item}</a>
		{if $config->enable_categories == 1}
			{br}
			<a href="{link module=categories action=manage orig_module=listingmodule}">{$_TR.manage_categories}</a>
		{/if}			
		</div>
	{/if}

</div>
