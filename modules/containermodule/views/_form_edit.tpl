{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
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
<script type="text/javascript">
{literal}
if (!document.body.appendChild) {
	alert("{$_TR.unsupported_browser}");
	history.go(-1);
}
{/literal}
</script>
{$js_init}

{if $nomodules == 1}
	<b>{$_TR.deactivated_all}</b>
{else}
<form name="form" method="post" action="{$smarty.const.SCRIPT_RELATIVE}{$smarty.const.SCRIPT_FILENAME}?" enctype="">
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
	<input type="hidden" name="current_section" value="{$current_section}" />
	
	<table cellspacing="0" cellpadding="0" width="100%">
		<tr><td valign="top">
		<table cellspacing="0" cellpadding="0" width="100%">
			{if $can_activate_modules == 1 && $is_edit == 0}
			<tr>
				<td></td>
				<td><i>{$_TR.been_deactivated}  <a class="mngmntlink container_mngmntlink" href="{link module=administrationmodule action=managemodules}">{$_TR.access_manager}</a></i></td>
			</tr>
			{/if}
			<tr>
				<td valign="top">{$_TR.module}</td>
				<td style='padding-left: 5px;' valign="top">
					<select id="i_mod" name="i_mod" size="1" onchange="writeViews()" {if $is_edit == 1}disabled {/if}>
						{html_options options=$modules selected=$container->internal->mod}
					</select>
				</td>
			</tr>
			<tr>
				<td valign="top">{$_TR.view}</td>
				<td style='padding-left: 5px;' valign="top">
					<select id="view" name="view" size="1" onchange="showPreviewCall()"></select>
				</td>
			</tr>
			<tr>
				<td valign="top">{$_TR.title}</td>
				<td style='padding-left: 5px;' valign="top">
					<input type="text" name="title" id="title" value="{$container->title}" onchange="showPreviewCall()" />
				</td>
			</tr>
			{if $is_edit == 0}
			<tr>
				<td valign="top">{$_TR.source}</td>
				<td style='padding-left: 5px;' valign="top">
					<table cellpadding='0' cellspacing='0' border='0'>
						<tr>
							<td>
								<input type='radio' name='i_src' value='new_source' id='r_new_source' onclick='activate("New");' />
							</td>
							<td>{$_TR.new_content}&nbsp;</td>
						</tr>
						<tr>
							<td>
								<input type='radio' name='i_src' value='existing_source' id='r_existing_source' onclick='activate("Existing");' />
							</td>
							<td>
								<a id="existing_source_link" class='mngmntlink container_mngmntlink' href='' onclick="pickSource(); return false;">{$_TR.existing_content}</a>
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
			<tr><td valign="top">{$_TR.description}&nbsp;</td><td>
				{$_TR.description_description}
				<textarea rows="5" cols="30" id="ta_description" {if $container->is_existing}disabled{/if} name="description">{$locref->description}</textarea>
			</td></tr>
			<tr><td></td><td>
				<input type="submit" value="{$_TR.save}" onclick="return validateNew()" />
				<input type="button" value="{$_TR.cancel}" onclick="document.location.href = '{$back}'" />
			</td></tr>
		</table>	
		</td><td width="50%">
			<b>{$_TR.preview}</b><br />
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

{/if}