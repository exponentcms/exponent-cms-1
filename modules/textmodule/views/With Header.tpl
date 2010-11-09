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

<div class="textmodule default">
	{if $moduletitle != ""}<h2>{$moduletitle}</h2>{/if}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if ($permissions.approve == 1 || $permissions.manage_approval == 1 || $permissions.edit == 1) }
			<div class="moduleactions">
				{if $permissions.edit == 1}
					{if $textitem->approved != 1}
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
					{else}
						<a class="mngmntlink text_mngmntlink" href="{link action=edit id=$textitem->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
					{/if}
				{/if}
				{if $textitem->approved != 1 && ($permissions.approve == 1 || $permissions.manage_approval == 1 || $permissions.edit == 1)}
					<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=textitem m=textmodule s=$__loc->src action=summary}">{$_TR.link_viewap}</a>
				{/if}
				{if $permissions.manage_approval == 1 && ($textitem->id != 0 && $textitem->approved != 0)}
					<a class="mngmntlink text_mngmntlink" href="{link module=workflow datatype=textitem m=textmodule s=$__loc->src action=revisions_view id=$textitem->id}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}" /></a>
				{/if}
			</div>
		{/if}
	{/permissions}
	<div class="textwithheader pad">
		{if $textitem->approved != 0}
			{$textitem->text}
		{/if}
	</div>
</div>
