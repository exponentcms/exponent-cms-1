{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by James Hunt
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 *}

<div class="search_results_title">
Search Results
</div>
<div class="search_results_options">
{$_TR.search_returned|sprintf:$query:$num_results}<br />
{if $have_excluded_terms != 0}<span class="search_results_excludes">{$_TR.ignored_terms}: {', '|join:$excluded_terms}</span>{/if}
</div>

{if $config->is_categorized == 0}
	{foreach from=$results item=result}
		<div class="search_result_item">
			<a href="{$result->view_link}">{$result->title}</a>
			{if $result->sum != ""}<br /><span class="search_result_item_body">{$result->sum}</span>{/if}
			{if $result->view_link != ""}<br /><span class="search_result_item_link">{$result->view_link}</span>{/if}
		</div>
	{/foreach}
{else}{* categorized, list of crap is two levels deep *}
	{foreach from=$results key=category item=subresults}
		<div class="search_result_cat"><a name="#{$category}">{$category}</a></div>
		{foreach from=$subresults item=result}
			<div class="search_result_item">
				<a href="{$result->view_link}">{$result->title}</a>
				<br /><span class="search_result_item_body">{$result->sum}</span>
				<br /><span class="search_result_item_link">{$result->view_link}</span>
			</div>
		{/foreach}
	{/foreach}
{/if}
