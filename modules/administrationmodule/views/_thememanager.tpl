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
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header">{$_TR.form_header}
<br /><br />
<a class="mngmntlink administration_mngmntlink" href="{link action=upload_extension}">{$_TR.new_theme}</a>.
</div>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
{foreach name=t from=$themes key=class item=theme}
	{math equation="x % 2" x=$smarty.foreach.t.iteration assign="even"}
	<tr>
		<td style="background-color: lightgrey"><b>{$theme->name}</b> {$_TR.by} {$theme->author}</td>
		<td style="background-color: lightgrey" align="right">
			{if $smarty.const.DISPLAY_THEME_REAL == $class}
				<span style="color: green" />{$_TR.current}</span>
			{/if}
			{if $smarty.const.DISPLAY_THEME == $class and $smarty.const.DISPLAY_THEME != $smarty.const.DISPLAY_THEME_REAL}
				<span style="color: blue" />{$_TR.previewing}</span>
			{/if}
		</td>
	</tr>
	<tr>
		<td align="{if $even == 0}left{else}center{/if}" valign="top" style="padding: 30px; border-left: 1px solid lightgrey; border-bottom: 1px solid lightgrey;">
			{if $even == 0}
				{$theme->description}
			{else}
				<img src="{$theme->preview}" style="border: 1px solid ligthgrey" />
				<br />
				{if $class != $smarty.const.DISPLAY_THEME}
				[ <a class="mngmntlink administration_mngmntlink" href="{link action=theme_preview theme=$class}">{$_TR.preview}</a> ]
				{else}
				[ {$_TR.preview} ]
				{/if}
				&nbsp;&nbsp;[ <a href="{link module=info action=showfiles type=$smarty.const.CORE_EXT_THEME name=$class}">{$_TR.view_files}</a> ]
			{/if}
		</td>
		<td align="{if $even == 1}left{else}center{/if}" style="padding-left: 10px; border-right: 1px solid lightgrey; border-bottom: 1px solid lightgrey;">
			{if $even == 1}
				{$theme->description}
			{else}
				<img src="{$theme->preview}" style="broder: 1px solid ligthgrey" />
				<br />
				{if $class != $smarty.const.DISPLAY_THEME}
				[ <a class="mngmntlink administration_mngmntlink" href="{link action=theme_preview theme=$class}">{$_TR.preview}</a> ]
				{else}
				[ {$_TR.preview} ]
				{/if}
				&nbsp;&nbsp;[ <a href="{link module=info action=showfiles type=$smarty.const.CORE_EXT_THEME name=$class}">{$_TR.view_files}</a> ]
			{/if}
		</td>
	</tr>
	<tr><td></td></tr>
{/foreach}
</table>
