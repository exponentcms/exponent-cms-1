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
 * $Id: Sub Sections.tpl 1242 2006-05-22 00:18:46 -0500 (Mon, 22 May 2006) maxxcorp $
 *}
{capture assign=output}
	<div class="modulewrapper">
	<div class="moduletitlesmall2">
	{$moduletitle}
	</div>
		<div class="submenulink">
		{assign var=haveAny value=0}
		{foreach from=$sections item=section}
		{if $section->numParents != 0 && ($section->parents[0] == $current->parents[0] || $section->parents[0] == $current->id)}
			<div style="padding-left: {math equation="x*20-20" x=$section->depth}px"><div class="submenulink2" style="font-size: 16px;">
			<a href="{$section->link}"><b>{$section->name}</b></a>
			<br /><span style="font-size: 13px; color:#666;">{$section->description}</span>
			</div>
			<br />
			</div>
		{assign var=haveAny value=1}
		{/if}
		{/foreach}
		</div>
	</div>
{/capture}
{if $haveAny == 1}{$output}{else}<br />{/if}