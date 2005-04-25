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
<div class="container_editbox">
 
	<div class="container_editheader">
		{* I.E. requires a 'dummy' div inside of the above div, so that it
		   doesn't just 'lose' the margins and padding. jh 8/23/04 *}
		<div width="100%" style="width: 100%">
		<table width="100%" cellpadding="0" cellspacing="3" border="0" class="container_editheader">
			<tr>
				<td valign="top" class="info">
					{$container->info.module}
					{if $container->view != ""}<br />Shown in {$container->view} view{/if}
				</td>
				<td align="right" valign="top">
					{if $container->info.clickable && $container->info.hasContent}
					{*<a class="mngmntlink container_mngmnltink" href="{$dest}&ss={$container->info.source}&sm={$container->info.class}">*}
					<a class="mngmntlink container_mngmnltink" href="{link action=content_selector module=$container->info.class src=$container->info.source channel_id=$dest}">
					Select Content from this Module
					</a>
					{/if}
				</td>
			</tr>
		</table>
		</div>
	</div>
	<div class="container_box">
		<div width="100%" style="width: 100%">
		{$container->output}
		</div>
	</div>	
</div>
<br /><br />