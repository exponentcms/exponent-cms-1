{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
<form method="post" action="">
<input type="hidden" name="action" value="channel_pull" />
<input type="hidden" name="module" value="common" />
<input type="hidden" name="channel_id" value="{$smarty.get.channel_id}" />
{foreach from=$news item=newsitem}{assign var=id value=$newsitem->id}
	<div>
		<div class="itemtitle news_itemtitle"><input type="checkbox" name="item[newsitem][]" value="{$newsitem->id}" {if $existing_items[$id]}disabled checked {/if}/>{$newsitem->title}</div>
		<div style="padding-left: 15px;">
		{$newsitem->body}
		</div>
	</div>
	<br /><br />
{foreachelse}
	<div style="margin-left: auto; margin-right: auto; font-style: italic;">No News Items found.</div>
{/foreach}
{if $haveNews == 1}
<input type="submit" value="Pull Selected" onClick="{literal}if (isOneSelected('item[newsitem')) return true; else { alert('You must select something.'); return false; }{/literal}" />
{/if}
</form>