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
<div class="moduletitle">Archived Modules</div>
{foreach from=$modules key=class item=data}
<div style="padding-left: 5px; border-top: 1px dashed #DDD; margin-top: 1em;">
{$data.name}
{foreach from=$data.modules key=src item=output}
<div style="margin-left: 10px;" class="container_editbox">
	<div class="container_editheader" align="right">
		<a class="mngmntlink container_mngmntlink" href="{link action=orphanedcontent_delete mod=$class delsrc=$src}">
		Delete Content
		</a>
	</div>
	<div class="container_box">
		<div width="100%" style="width: 100%">
		{$output}
		</div>
	</div>
</div>
{/foreach}
</div>
{foreachelse}
<i>No archived modules were found.</i>
{/foreach}
{if $have_bad_orphans == 1}
<br /><br />
<b><i>Archived Content for Uninstalled Modules was found</i></b> 
{/if}