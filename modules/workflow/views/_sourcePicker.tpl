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
{foreach name=c from=$containers item=container}
	{assign var=i value=$smarty.foreach.c.iteration}
	<div class="container_editbox">
	 
		<div class="container_editheader">
			{* I.E. requires a 'dummy' div inside of the above div, so that it
			   doesn't just 'lose' the margins and padding. jh 8/23/04 *}
			<div width="100%" style="width: 100%">
			<table width="100%" cellpadding="0" cellspacing="3" border="0" class="container_editheader">
				<tr>
					{if $container->info.clickable}
						<td width="18">
							<a href="#" onClick="window.open('{$smarty.const.PATH_RELATIVE}modules/containermodule/infopopup.php?{if $container->id != 0}id={$container->id}{else}mod={$container->info.class}&src={$container->info.src}{/if}','info','scrollbars=yes,title=no,titlebar=no,width=300,height=200');"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}info.png" /></a>
						</td>
					{/if}
						<td valign="top" class="info">
						{$container->info.module}
						<br />
						{if $container->info.supportsWorkflow}
						{if $container->info.workflowUsesDefault == 1}Default: {/if}
						{if $container->info.workflowPolicy != ""}{$container->info.workflowPolicy}{else}<i>No Workflow Policy</i>{/if}
						{else}
						
						{/if}
					</td>
					<td align="right" valign="top">
						{if $container->info.supportsWorkflow == 1}
						<a class="mngmntlink container_mngmnltink" href="{$dest}&s={$container->info.source}&m={$container->info.class}">
						Change Policy
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
{/foreach}
