{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
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
<div class="form_title">Manage Themes</div>
<div class="form_header">This page lists all installed themes that are recognized by Exponent.  When you click the 'Preview' link, the site layout will be switched to that theme for the duration of your session.  Other uses will still see the configured theme.  If you log out or close your browser window, the previewing will stop.
<br /><br />
To change the current configured theme, you will have to edit the <a class="mngmntlink administration_mngmntlink" href="{link action=configuresite}">Site Configuration</a>.
<br /><br />
To install a new theme, use the <a class="mngmntlink administration_mngmntlink" href="{link action=upload_extension}">Extension Upload</a> form.
</div>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
{foreach name=t from=$themes key=class item=theme}
	{math equation="x % 2" x=$smarty.foreach.t.iteration assign="even"}
	<tr>
		<td style="background-color: lightgrey"><b>{$theme->name}</b> by {$theme->author}</td>
		<td style="background-color: lightgrey" align="right">
			{if $smarty.const.DISPLAY_THEME_REAL == $class}
				<span style="color: green" />Current</span>
			{/if}
			{if $smarty.const.DISPLAY_THEME == $class and $smarty.const.DISPLAY_THEME != $smarty.const.DISPLAY_THEME_REAL}
				<span style="color: blue" />Previewing</span>
			{/if}
		</td>
	</tr>
	<tr>
		<td align="{if $even == 0}left{else}center{/if}" valign="top" style="padding: 30px; border-left: 1px solid lightgrey; border-bottom: 1px solid lightgrey;">
			{if $even == 0}
				{$theme->description}
			{else}
				<img src="{$theme->preview}" style="broder: 1px solid ligthgrey" />
				<br />
				{if $class != $smarty.const.DISPLAY_THEME}
				[ <a class="mngmntlink administration_mngmntlink" href="{link action=theme_preview theme=$class}">Preview</a> ]
				{else}
				[ Preview ]
				{/if}
				&nbsp;&nbsp;[ <a href="{link module=info action=showfiles type=$smarty.const.CORE_EXT_THEME name=$class}">View Files</a> ]
			{/if}
		</td>
		<td align="{if $even == 1}left{else}center{/if}" style="padding-left: 10px; border-right: 1px solid lightgrey; border-bottom: 1px solid lightgrey;">
			{if $even == 1}
				{$theme->description}
			{else}
				<img src="{$theme->preview}" style="broder: 1px solid ligthgrey" />
				<br />
				{if $class != $smarty.const.DISPLAY_THEME}
				[ <a class="mngmntlink administration_mngmntlink" href="{link action=theme_preview theme=$class}">Preview</a> ]
				{else}
				[ Preview ]
				{/if}
				&nbsp;&nbsp;[ <a href="{link module=info action=showfiles type=$smarty.const.CORE_EXT_THEME name=$class}">View Files</a> ]
			{/if}
		</td>
	</tr>
	<tr><td></td></tr>
{/foreach}
</table>
