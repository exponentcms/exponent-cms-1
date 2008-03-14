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
<div class="containermodule edit">
	
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
	<div class="form_header">
		<h1>{if $is_edit}Edit Module{else}Add a Module{/if}</h1>
		{if $can_activate_modules == 1 && $is_edit == 0}
		<p>{$_TR.been_deactivated}<br>
			<a class="managemodules" href="{link module=administrationmodule action=managemodules}">{$_TR.access_manager}</a>
		</p>
		
		{/if}
	</div>
	
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
	
	<fieldset id="moduleControl" class="control">
	<legend>{$_TR.module}</legend>
		<select id="i_mod" name="i_mod" class="select" size="1" onchange="writeViews()" {if $is_edit == 1}disabled {/if}>
			{html_options options=$modules selected=$container->internal->mod}
		</select>
	</fieldset>
	<fieldset id="viewControl" class="control">
	<legend>{$_TR.view}</legend>
		<select id="view" class="select" name="view" size="1" onchange="showPreviewCall()"></select>
	</fieldset>
{if $is_edit == 0}
	<fieldset id="contentsourcecontrol" class="control">
		<legend>{$_TR.source}</legend>
			<label id="sourceControl">
				<input type='radio' class="radiobutton" name='i_src' value='new_source' id='r_new_source' onclick='activate("New");' />
				<span class="radiolabel">{$_TR.new_content}</span>
			</label>
			<label id="sourceControl">
				<input type='radio' class="radiobutton" name='i_src' value='existing_source' id='r_existing_source' onclick='activate("Existing");' />
				<span class="radiolabel"><a id="existing_source_link" href="javascript:pickSource()" >{$_TR.existing_content}</a></span>
				<input type="hidden" id="existing_source" name="existing_source" value="" />
			</label>
		<span id='noSourceMessageTD'></span><!-- do we need this? -->
	</fieldset>
{/if}
	<fieldset id="titleControl" class="control">
		<legend>{$_TR.title}</legend>
		<input type="text" class="text" size="31" name="title" id="title" value="{$container->title}" onchange="showPreviewCall()" />
	</fieldset>
<fieldset id="descriptionControl" class="control">
	<legend>{$_TR.description}</legend>
	<textarea rows="5" cols="30" class="textarea" id="ta_description" {if $container->is_existing}disabled{/if} name="description">{$locref->description}</textarea>
</fieldset>

{control type=buttongroup submit="save" cancel="Cancel" name="buttons"}

<!-- div id="buttonControl" class="control">
	<input type="submit" class="button" value="{$_TR.save}" onclick="return validateNew()" />
	<input type="button" class="button" value="{$_TR.cancel}" onclick="document.location.href = '{$back}'" />
</div -->

</form>
<script type='text/javascript' src='{$smarty.const.PATH_RELATIVE}js/ContainerSourceControl.js'></script>
<script type="text/javascript" defer='1'>
var sourceInit = false;
writeViews();
activate("New");
sourcePicked("{$container->internal->src}","{$locref->description|escape:"javascript"}");
</script>

{/if}
</div>




