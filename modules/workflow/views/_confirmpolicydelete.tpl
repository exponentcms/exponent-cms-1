{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
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
<div>
{$_TR.warning}
<br /><hr size="1" /><br />

<table cellpadding="4" cellspacing="1" width="100%" border="0">
	<tr>
		<td class="header workflow_header">{$_TR.title}</td>
		<td class="header workflow_header"> {$_TR.version}</td>
		<td class="header workflow_header">{$_TR.module}</td>
	</tr>
{foreach from=$affected key=type item=posts}
{foreach from=$posts item=post}
	<tr>
		<td>{$post->title}</td>
		<td>{$post->current_major}.{$post->current_minor}</td>
		<td>{$post->module}</td>
	</tr>
{/foreach}
{foreachelse}
{$_TR.no_paths}
{/foreach}
</table>
<br /><hr size="1" />
<a class="mngmntlink workflow_mngmntlink" href="{link action=admin_deletepolicy id=$policy->id}">{$_TR.delete}</a>
</div>