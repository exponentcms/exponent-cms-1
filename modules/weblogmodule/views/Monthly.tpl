{*
 * Copyright (c) 2004-2011 OIC Group, Inc.
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
 
<div class="weblogmodule monthly">
	<h2>
		{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	<ul>
		{foreach from=$months key=m_ts item=count}
			<li><a class="mngmntlink weblog_mngmntlink weblog_monthview_mngmntlink" href="{link action=view_month month=$m_ts}" title="{$_TR.link_title} {$m_ts|format_date:"%B %Y"}">{$m_ts|format_date:"%B %Y"} ({$count})</a></li>
		{/foreach}
	</ul>
</div>