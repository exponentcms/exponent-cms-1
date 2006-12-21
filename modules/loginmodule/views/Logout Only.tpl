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
{if $loggedin == true || $smarty.const.PREVIEW_READONLY == 1}
	<a href="{link action=logout}"><img src="{$smarty.const.ICON_RELATIVE}logout.png" alt="Logout" border="0" />&nbsp;<strong>{$_TR.logout}</strong></a>
{/if}
