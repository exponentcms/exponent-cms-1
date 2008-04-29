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

<div class="common msg-queue">
{foreach from=$queues item=queue key=qname}
	<ul class="queue {$qname}">
		{foreach from=$queue item=msg} 
        		<li>{$msg}</li>
		{/foreach}
	</ul>
{/foreach}
</div>
