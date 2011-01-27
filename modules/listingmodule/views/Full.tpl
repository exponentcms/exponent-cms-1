<div class="listingmodule listings">
	<a name="#top"></a>
	{if $moduletitle}<h2>{$moduletitle}</h2>{/if}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit == 1}
			<div class="moduleactions">
				<a href="{link action=edit id=$config->id}"><img src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit_desc}" alt="{$_TR.alt_edit_desc}" {$smarty.const.XHTML_CLOSING}></a>
			</div>
		{/if}
	{/permissions}		
	{if $config->description}
		{$config->description}
	{/if}

	{if $__viewconfig.show_category_jump}
		<script type="text/javascript" charset="utf-8">
			function go()
			{ldelim}
				box = document.forms[0].quicklinks;
				destination = box.options[box.selectedIndex].value;
				if (destination) location.href = destination;
			{rdelim}	
		</script>
		<form>
			{$_TR.jump_category}...
			<select id="quicklinks" name="quicklinks" onchange="go()">
				<option>{$_TR.select_category}...</option>
				{assign var=listing_number value=0}
				{foreach name=c from=$data key=catid item=listings}
					{math assign=page_number equation="floor((x-1)/y)+1" x=$listing_number y=$config->items_perpage}
					{if $listings}
						{if ($listing_number >= $first) && ($listing_number < $last)}
							<option value="#{$categories[$catid]->name}" >
						{else}
							<option value="{link action=view view=Simple page=$page_number}" >
						{/if}
							{$categories[$catid]->name}		
						</option>
						{foreach name=a from=$listings item=listing}
							{assign var=listing_number value=$listing_number+1}	
						{/foreach}
					{/if}					
				{foreachelse}
					<option><em>{$_TR.no_category}</em></option>
				{/foreach}
			</select>
		</form>
	{/if}
	
	{assign var=listing_number value=0}
	{foreach name=c from=$data key=catid item=listings}
		{assign var=category_printed value=0}
		{if ($listing_number >= $first) && ($listing_number < $last)}
			{if $hasCategories != 0}
				<hr size="1">
				{if $catid != 0}
					<a name="#{$categories[$catid]->name}"></a>
					<h3>{$categories[$catid]->name}</h3>
				{elseif $listings}
					<h3>{$_TR.no_category}</h3>
				{/if}	
				{assign var=category_printed value=1}
			{/if}
		{/if}
		{assign var=listing_found value=0}
		{foreach name=a from=$listings item=listing}
			{if ($listing_number >= $first) && ($listing_number < $last)}
				{if ($hasCategories != 0) && !$category_printed}
					<hr size="1">
					{if $catid != 0}
						<h3>{$categories[$catid]->name}&nbsp;(cont...)</h3>
					{else}
						<h3>{$_TR.no_category}&nbsp;(cont...)</h3>
					{/if}	
					{assign var=category_printed value=1}
				{/if}
				{math equation="x-1" x=$listing->rank assign=prev}
				{math equation="x+1" x=$listing->rank assign=next}
				<div class="item {cycle values='odd,even'}">		
					<div class="text">
						<h4>
						<a href="{link action=view_listing id=$listing->id}">{$listing->name}</a>					
						{permissions level=$smarty.const.UILEVEL_PERMISSIONS}							
								{if $permissions.configure == 1 or $permissions.administrate == 1}
									<a href="{link action=edit_listing id=$listing->id}" title="{$_TR.alt_edit}">
										<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
									</a>
									<a href="{link action=delete_listing id=$listing->id}" title="{$_TR.alt_delete}" onclick="return confirm('{$_TR.delete_confirm}');">
										<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
									</a>	
									{if !$hasCategories && $config->orderhow == 2}
										{if $smarty.foreach.a.first == 0}
										<a href="{link action=rank_switch a=$listing->rank b=$prev id=$listing->id category_id=$catid}">			
											<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_up}" alt="{$_TR.alt_up}" />
										</a>
										{else}
										<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.disabled.png" title="{$_TR.alt_up_disabled}" alt="{$_TR.alt_up_disabled}" />
										{/if}
										
										{if $smarty.foreach.a.last == 0}
										<a href="{link action=rank_switch a=$next b=$listing->rank id=$listing->id category_id=$catid}">
											<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_down}" alt="{$_TR.alt_down}" />
										</a>
										{else}
										<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.disabled.png" title="{$_TR.alt_down_disabled}" alt="{$_TR.alt_down_disabled}" />
										{/if}	
									{/if}
								{/if}							
						{/permissions}	
						</h4>						
						<div class="bodycopy">
							{if $listing->picpath != ""}					
								<a href="{link action=view_listing id=$listing->id}">
									<img class="listingimage{if $__viewconfig.image_right}-right{/if}" src="{$smarty.const.URL_FULL}/thumb.php?base={$smarty.const.BASE}&amp;file={$listing->picpath}&amp;width=100&amp;height=150" alt="{$listing->name}" />
								</a>					
							{/if}
							{$listing->body}
							<BR CLEAR="all"> 
						</div>						
					</div>
				</div>
			{elseif $listing_number == $last && $smarty.foreach.a.index && ($smarty.foreach.a.index != $smarty.foreach.a.total)}
				{$categories[$catid]->name} (continues...){br}
			{/if}
			{assign var=listing_found value=1}
			{if $__viewconfig.show_category_jump && $listing_number == $last}
				<a href="#top"><em>(back to top)</em></a>
			{/if}		
			{assign var=listing_number value=$listing_number+1}
		{foreachelse}
			{ if (($config->enable_categories == 1 && $catid != 0) || ($config->enable_categories==0)) && (($listing_number >= $first) && ($listing_number <= $last)) && !$listing_found}	
				{if ($hasCategories != 0) && !$category_printed}
					<hr size="1">
					{if $catid != 0}
						<h3>{$categories[$catid]->name}</h3>
					{else}
						<h3>{$_TR.no_category}</h3>
					{/if}	
					{assign var=category_printed value=1}
				{/if}			
				<em>&nbsp;&nbsp;{$_TR.no_listings}</em>{br}{br}
			{/if}
		{/foreach}
		{if $__viewconfig.show_category_jump && ($listing_number > $first) && ($listing_number < $last) && $listing_found}
			<a href="#top"><em>(back to top)</em></a>
		{/if}
	{foreachelse}
	{/foreach}
	
	{if $pagecount>1}
	<div class="pagination">
		Page({$curpage} of {$pagecount})
		{if $curpage != 1}
			<a class="listing_page_link mngmntlink" href="{link action=view view=Simple page=1}"><<</a>&nbsp;
		  	<a class="listing_page_link mngmntlink" href="{link action=view view=Simple page=$curpage-1}"><</a>
		{/if}
		{if $downlimit>1 }...{/if}
		{section name=pages start=$downlimit loop=$pagecount+1 max=$uplimit} 
		  <a class="listing_page_link mngmntlink" href="{link action=view view=Simple page=$smarty.section.pages.index}">
		    {if $curpage == $smarty.section.pages.index}
		    [{$smarty.section.pages.index}]
		    {else}
		    {$smarty.section.pages.index}
		    {/if}
		  </a>  
		{/section}
		{if $uplimit<$pagecount }...{/if}
		{if $curpage != $pagecount}
			<a class="listing_page_link mngmntlink" href="{link action=view view=Simple page=$curpage+1}">></a>&nbsp;
			<a class="listing_page_link mngmntlink" href="{link action=view view=Simple page=$pagecount}">>></a>
		{/if}
	</div>
	{/if}
	{permissions level=$smarty.const.UILEVEL_PERMISSIONS}	
		{if $permissions.administrate == 1}
			<div class="moduleactions">
				<a class="mngmntlink additem"  href="{link action=edit_listing}">{$_TR.create_item}</a>
				{if $config->enable_categories == 1}
					{br}<a class="mngmntlink cats" href="{link module=categories action=manage orig_module=listingmodule src=$loc->src}">{$_TR.manage_categories}</a>
				{/if}			
			</div>
		{/if}
	{/permissions}
</div>
