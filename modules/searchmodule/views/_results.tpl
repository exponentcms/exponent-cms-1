{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 *}
<b>Search Results</b>
<br />
{$_TR.search_returned|sprintf:$query:$num_results}<br />
{if $have_excluded_terms != 0}
<i>{$_TR.ignored_terms}: {', '|join:$excluded_terms}<br />
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