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
{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
{foreach from=$months key=m_ts item=count}
	<a class="mngmntlink weblog_mngmntlink weblog_monthview_mngmntlink" href="{link action=view_month month=$m_ts}">{$m_ts|format_date:"%B %Y"} ({$count})</a><br />
{/foreach}
