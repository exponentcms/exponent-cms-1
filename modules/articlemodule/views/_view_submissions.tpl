{*
 *
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
<div class="articlemodule view-submissions">
	<h1>User Submitted Articles</h1>
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	<table>
		<th>Article Title</th>
		<th>Article Submited By</th>
		<th>Download Article</th>
		<th>&nbsp;</th>
		{foreach from=$submissions item=article}
			<tr>
				<td>{$article->title}</td>
				<td>{$article->submitter_name}</td>
				<td><a href="{$smarty.const.URL_FULL}{$article->file->directory}/{$article->file->filename}">Download</a></td>
				<td>
				{if $permissions.manage == 1}
                        		<a href="{link action=delete_article id=$article->id}" onclick="return confirm('Are you sure you want to delete this submission?');">
                                		<img src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
                        		</a>
                		{/if}
				</td>
			</tr>
		{foreachelse}
			<tr><td>No articles have been submitted</td>
		{/foreach}
	</table>
	{/permissions}
</div>
