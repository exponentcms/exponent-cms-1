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
 <script type="text/javascript" />
{literal}
if (!document.body.appendChild) {
	alert("Your browser does not support the necessary javascript for this operation");
	history.go(-1);
}
{/literal}
</script>
{$js_init}

{if $nomodules == 1}
	<b>The administrator has disabled all modules.  You will not be able to add new ones.</b>
{else}
<form name="form" method="post" action="?" enctype="">
	{if $is_edit}<input type="hidden" name="id" value="{$container->id}" />
	{/if}<input type="hidden" name="rank" value="{$container->rank}" />
	<input type="hidden" name="module" value="containermodule" />
	<input type="hidden" name="src" value="{$loc->src}" />
	<input type="hidden" name="int" value="{$loc->int}" />
	{if $rerank == 1}<input type="hidden" name="rerank" value="1" />{/if}
	<input type="hidden" name="action" value="save" />
	{if $is_edit == 1}
	<input type="hidden" id="existing_source" name="existing_source" value="{$container->internal->src}" />
	{/if}
	
	<table cellspacing="0" cellpadding="0" width="100%">
		<tr><td valign="top">
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td valign="top">Module</td>
				<td style='padding-left: 5px;' valign="top">
					<select id="i_mod" name="i_mod" size="1" onChange="writeViews()" {if $is_edit == 1}disabled {/if}>
						{html_options options=$modules selected=$container->internal->mod}
					</select>
				</td>
			</tr>
			<tr>
				<td valign="top">View</td>
				<td style='padding-left: 5px;' valign="top">
					<select id="view" name="view" size="1" onChange="showPreviewCall()"></select>
				</td>
			</tr>
			<tr>
				<td valign="top">Title</td>
				<td style='padding-left: 5px;' valign="top">
					<input type="text" name="title" id="title" value="{$container->title}" onChange="showPreviewCall()" />
				</td>
			</tr>
			{if $is_edit == 0}
			<tr>
				<td valign="top">Source</td>
				<td style='padding-left: 5px;' valign="top">
					<table cellpadding='0' cellspacing='0' border='0'>
						<tr>
							<td>
								<input type='radio' name='i_src' value='new_source' id='r_new_source' onClick='activate("New");' />
							</td>
							<td>Create New Content&nbsp;</td>
						</tr>
						<tr>
							<td>
								<input type='radio' name='i_src' value='existing_source' id='r_existing_source' onClick='activate("Existing");' />
							</td>
							<td>
								<a id="existing_source_link" class='mngmntlink container_mngmntlink' href='' onClick="pickSource(); return false;">Use Existing Content</a>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input type="hidden" id="existing_source" name="existing_source" value="" />
							</td>
						</tr>
						<tr>
							<td colspan='2' id='noSourceMessageTD'></td>
						</tr>
					</table>
	
				</td>
			</tr>
			{/if}
			<tr><td valign="top">Description:&nbsp;</td><td>
				Enter a short description of how this module will be used.
				<textarea rows="5" cols="30" id="ta_description" {if $container->is_existing}disabled{/if} name="description">{$locref->description}</textarea>
			</td></tr>
			<tr><td></td><td>
				<input type="submit" value="Save" onClick="return validateNew()" />
				<input type="button" value="Cancel" onClick="document.location.href = '{$back}'" />
			</td></tr>
		</table>	
		</td><td width="50%">
			<b>Preview of Content:</b><br />
			<iframe id="iframePreview" src="{$smarty.const.PATH_RELATIVE}modules/containermodule/nosourceselected.php" width="100%" height="250" style="border: 1px dashed #DDD;"></iframe>
		</td></tr>
	</table>
</form>

<script type='text/javascript' src='{$smarty.const.PATH_RELATIVE}js/ContainerSourceControl.js'></script>
<script type="text/javascript" defer='1'>
var sourceInit = false;
writeViews();
activate("New");
sourcePicked("{$container->internal->src}","{$locref->description|escape:"javascript"}");
</script>
<script type="text/javascript">
{literal}
if (!document.body.appendChild) {
	alert("Your browser is not supported");
	history.go(-1);
}
{/literal}
</script>

{/if}