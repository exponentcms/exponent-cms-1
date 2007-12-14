{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by Phillip Ball
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

<div class="navigationmodule manage">
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $canManage == 1}
		<li><a class="navlink" href="{link action=manage}">Manage Navigation</a></li>
	{/if}
	{/permissions}
</div>