{*
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 <div class="searchresults" style="padding: 15px;">
<h2>Search Results</h2>
<br />
{$_TR.search_returned|sprintf:$query:$num_results}<br />
{if $have_excluded_terms != 0}
<i>{$_TR.ignored_terms}: {', '|join:$excluded_terms}</i><br />
{/if}
{if $config->is_categorized == 0}{* not categorized, we just have a list of crap *}
{foreach from=$results item=result}
<hr size="1" />
<a href="{$result->view_link}">{$result->title}</a><br />{$result->sum}<br />
{/foreach}
{else}{* categorized, list of crap is two levels deep *}
{foreach from=$results key=category item=subresults}
	<hr size='1' />
	<hr size='1' />
	<b>{$category}</b>
	{foreach from=$subresults item=result}
		<hr size="1" />
		<a href="{$result->view_link}">{$result->title}</a><br />{$result->sum}<br />
	{/foreach}
{/foreach}
{/if}
<div>