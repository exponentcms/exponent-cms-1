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

<div class="contactmodule default">
	{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
	{if $numContacts != 0}
		{form action=contact}
			{control type=hidden name=msg value="_Default"}<br />
			{control type=text name=name label=$_TR.name size=30}<br />
			{control type=text name=email label=$_TR.email size=30}<br />
			{control type=text name=subject label=$_TR.subject size=50}<br />
			{control type=textarea name=message rows="8" cols="45" label=$_TR.message}<br />
			{control type=buttongroup submit=Send}
		{/form}
	{else}
		{if $smarty.const.PREVIEW_READONLY == 1 || $permissions.configure == 1}
			{$_TR.no_contacts}<br /><br />
		{/if}
	{/if}

	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.configure == 1}
			<a class="mngmntlink contact_mngmntlink" href="{link action=manage_contacts}">{$_TR.manage_contacts}</a>
		{/if}
	{/permissions}
</div>
