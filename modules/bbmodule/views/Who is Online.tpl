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
 *}
 
<div class="bbmodule default">
   <img style="margin:4px 0 0 0" src="{$smarty.const.ICON_RELATIVE}whosonline.jpg" alt="" /> 
	{if $show_users == true}

	<div class="bb_whosonline"><span>There are <strong>{$anon_users}</strong> Guests<br />
	and these members:
	</span>

	<ul>
		<!--span style="font-size:6px;-->

		{foreach name="looponline" from=$users_online item=user}
		 <li> <a href="{link module=loginmodule action=showuserprofile id=$user->id}" title="View user profile">{$user->username}</a></li>
		{/foreach}
		<!--/span-->
	</ul>
		
	</div>{/if}<img src="{$smarty.const.ICON_RELATIVE}sidebox_bottom.gif" alt="" />
</div>
