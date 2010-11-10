{*
 *
 * Copyright (c) 2009 Maia Good.
 *
 * This file is for use with Exponent CMS
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
 * This view of the imagegallerymodule uses a slideshow javascript by Aeron Glenman
 * which is hosted at http://code.google.com/p/slideshow/
 *
 * Slideshow configuration options (visit the code homepage for the newest information)
 *
 * captions - (boolean or Fx options object: default false) Whether to show captions.
 * center - (boolean: default true) Whether the show should attempt to center images.
 * classes - (array) An array of CSS class names to use with styling the show.
 * controller - (boolean or Fx options object: default false) Whether to show controller.
 * delay - (integer: default 2000) The delay between slide changes in milliseconds (1000 = 1 second).
 * duration - (integer: default 750) The duration of the effect in milliseconds (1000 = 1 second).
 * fast - (boolean or integer: default false) Affects how the show behaves when the user is navigating via the controller or keyboard: if false/0 the show will always use transitions; if 1 the show will skip transitions if paused; if true/2 the show will skip all transitions and update the slide change instantly.
 * height - (boolean or integer: default false) Optional height value for the show as a whole integer, if a height value is not given the height of the default image will be used.
 * href - (string: default empty) A single link for the show, inherited from the HTML href of the default image.
 * hu - (string) Path to the image directory, relative or absolute, default is the root directory of the website, use an empty string for the same directory as the webpage.
 * linked - (boolean: default false) Whether the class should automatically link each slide to the fullsize image, useful when mashing Slideshow with Slimbox, Lightbox, etc.
 * loader - (boolean or Fx options object: default object) Show the loader graphic for images being loaded.
 * loop - (boolean: default true) Should the show loop.
 * match - (regexp) A regular expression the class uses to parse the URL, will overwrite "slide" below, default looks for ?slide=n where "n" would be the slide to start from.
 * onStart, onComplete, onEnd - (function) Events that are fired on the start or completion of a slide change and at the end of a non-looping show.
 * overlap - (boolean: default true) Whether images overlap in the basic show, or if the first image transitions out before the second transitions in.
 * paused - (boolean: default false) Whether the show should start paused.
 * random - (boolean: default false) Random show, combined with thumbnails equals very cool!
 * replace - (array) An array, consisting of a regular expression pattern and a string replacement, for building thumbnail filenames based on the name of the original image, default appends a "t" to the image filename before the extension.
 * resize - (string: default "width") Whether the show should attempt to resize images, based on the shortest side (default) or longest side ("length").
 * slide - (integer: default 0) Slide from which to start the show.
 * thumbnails - (boolean or Fx options object: default false) Whether to show thumbnails.
 * titles - (boolean) Whether to use captions for image and thumbnail title attributes.
 * transition - (function: default Sine) Transition to use with base and push type shows (requires Mootools with Fx.Transitions).
 * width - (boolean or integer: default false) Optional width value for the show as a whole integer, if a width value is not given the width of the default image will be used.  
 *
 * For this view the slideshow is setup with settings from the imagegallerymodule
 * height - The height of the tallest image in the gallery
 * width - The width of the tallest image in the gallery  
 * hu - Absolute path to the imagegallery/galleryx directory
 *
 * This view creates valid XHTML strict output.
 *
 * Maia Good - v1.01 - 2009-05-24
 *}
{* For this view the following configuration options can be modified here *}
{assign var=captions value=$__viewconfig.captions}
{assign var=controller value=$__viewconfig.controller}
{assign var=thumbnails value=$__viewconfig.thumbnails}
{assign var=random value=$__viewconfig.random}
{assign var=delay value=$__viewconfig.delay}
{assign var=linked value=$__viewconfig.linked}
{assign var=overlap value=$__viewconfig.overlap}
{assign var=showtype value=$__viewconfig.showtype}

<div class="imagegallerymodule default">
	{if $moduletitle != ""}<h2>{$moduletitle}</h2>{/if}

	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.create == 1}
	<div class="moduleactions">
		<a class="newgallery" href="{link action=edit_gallery}">{$_TR.new_gallery}</a>
		{br}<a href="{link action=reorder_galleries}"><img src="{$smarty.const.ICON_RELATIVE}manage_images.png" />{$_TR.reorder_galleries}</a>{br}
	</div>
	{/if}
	{/permissions}

	{assign var=gallery value=$galleries[0]}
	<h3 class="headline-pix">
	{if $gallery->name}{$gallery->name}{/if}
	{include file="`$smarty.const.BASE`modules/imagegallerymodule/views/_edit_delete.tpl"}
	</h3>

	{* Get largest picture size and largest picture width to set slideshow size correctly *}
	{assign var=slidewidth value=0}
	{assign var=slideheight value=0}
	{assign var=thumbwidth value=0}
	{assign var=thumbheight value=0}
	{foreach from=$gallery->images item=image}
		{if $image->popwidth && ($image->popwidth > $slidewidth)}{assign var=slidewidth value=$image->popwidth}{/if}
		{if $image->popheight && ($image->popheight > $slideheight)}{assign var=slideheight value=$image->popheight}{/if}
		{if $image->twidth && ($image->twidth > $thumbwidth)}{assign var=thumbwidth value=$image->twidth}{/if}
		{if $image->popwidth && ($image->popwidth > $thumbheight)}{assign var=thumbheight value=$images->theight}{/if}
	{/foreach}

	{* Assign defaults if something is missing *}
	{if $slidewidth == 0}{assign var=slidewidth value=515}{/if}
	{if $slideheight == 0}{assign var=slideheight value=388}{/if}
	{if $thumbwidth == 0}{assign var=thumbwidth value=65}{/if}
	{if $thumbheight == 0}{assign var=thumbheight value=65}{/if}

	{* Start slideshow code *}
	{literal}
	<script type="text/javascript" src="{/literal}{$smarty.const.THEME_RELATIVE}{literal}js/mootools.js"></script>
	<script type="text/javascript" src="{/literal}{$smarty.const.THEME_RELATIVE}{literal}js/slideshow.js"></script>
	{/literal}
	{if $showtype == 1}
	{literal}
	<script type="text/javascript" src="{/literal}{$smarty.const.THEME_RELATIVE}{literal}js/slideshow.kenburns.js"></script>
	{/literal}
	{assign var=showtypename value="Slideshow.KenBurns"}
	{elseif $showtype == 2}
	{literal}
	<script type="text/javascript" src="{/literal}{$smarty.const.THEME_RELATIVE}{literal}js/slideshow.push.js"></script>
	{/literal}
	{assign var=showtypename value="Slideshow.Push"}
	{elseif $showtype == 3}
	{literal}
	<script type="text/javascript" src="{/literal}{$smarty.const.THEME_RELATIVE}{literal}js/slideshow.fold.js"></script>
	{/literal}
	{assign var=showtypename value="Slideshow.Fold"}
	{elseif $showtype == 4}
	{literal}
	<script type="text/javascript" src="{/literal}{$smarty.const.THEME_RELATIVE}{literal}js/slideshow.flash.js"></script>
	{/literal}
	{assign var=showtypename value="Slideshow.Flash"}
	{else}
	{assign var=showtypename value="Slideshow"}
	{/if}
	{literal}
	<script type="text/javascript">		
	//<![CDATA[
	  window.addEvent('domready', function(){
		var data = {{/literal}{foreach name="i" from=$gallery->images item=image}
				{literal}'{/literal}{$image->enlarged}{literal}': { caption: '{/literal}{if $image->name != ''}{$image->name|strip_tags:false|escape:"quotes"}{else}slide number {$smarty.foreach.i.iteration}{/if}{literal}',
			thumbnail: '{/literal}{$image->thumbnail}{literal}' }{/literal}
				{if $smarty.foreach.i.iteration < $gallery->images|@count}{literal},{/literal}{/if}
			{/foreach}
			{literal}
		};
		// Note: you can add other options to the slideshow here.  Don't forget to enclose smarty variables in brackets of literals.
		var myShow = new {/literal}{$showtypename}{literal}('show', data, { captions: {/literal}{$captions}{literal}, controller: {/literal}{$controller}{literal}, thumbnails: {/literal}{$thumbnails}{literal}, delay: {/literal}{$delay}{literal}, width: {/literal}{$slidewidth}{literal}, height: {/literal}{$slideheight}{literal}, hu: '{/literal}{$smarty.const.URL_FULL}{$gallery->images[0]->file->directory}{literal}', linked: {/literal}{$linked}{literal}, overlap: {/literal}{$overlap}{literal} , random: {/literal}{$random}{literal}});
		});

	//]]>
	</script>
	{/literal}
	{* End slideshow code *}

	{if $gallery->description}{$gallery->description}{/if}

	{* Some themes require a wrapper which has to be assigned a height in CSS *}
	<div class="slideshowwrapper"> 
		<div id="show" class="slideshow">	
			<div class="slideshow-images">
				<ul>
					{foreach name="i" from=$gallery->images item=image}
						<li>
							{* You can remove the href and configure above linked to false, or add ibox or lightbox type effect *}
							<a href="{$smarty.const.URL_FULL}{$image->file->directory}/{$image->file->filename}" title="{if $image->name != ''}{$image->name|strip_tags:false}{else}slide number {$smarty.foreach.i.iteration}{/if}">
								<img id="g{$gallery->id}i{$smarty.foreach.i.iteration}" src="{$smarty.const.URL_FULL}{$image->file->directory}/{$image->enlarged}" 
									width="{$slidewidth}px"
									height="{if $image->popheight}{$image->popheight}{else}{$gallery->images[0]->popheight}{/if}px"
									alt="{if $image->name}{$image->name}{else}slide number {$smarty.foreach.i.iteration}{/if}"
								/>
							</a>
						</li>
					{/foreach}
				</ul>
			</div>
			
			{* Remove this and set thumbnails to false to have no thumbnails *}
			{if $thumbnails}
			<div class="slideshow-thumbnails">
				<ul>
					{foreach name="i" from=$gallery->images item=image}
						<li>
							<a href="#g{$gallery->id}i{$smarty.foreach.i.iteration}">
								<img src="{$smarty.const.URL_FULL}{$image->file->directory}/{$image->thumbnail}" 
									width="{$thumbwidth}px"
									height="{$thumbheight}px"
									alt="{if $image->name != ''}Thumbnail for {$image->name|strip_tags:false}{else}thumbnail for slide number {$smarty.foreach.i.iteration}{/if}" 
								/>
							</a>
						</li>
					{/foreach}
				</ul>
			</div>
			{/if}
		</div>
	</div>
	{br}
	{if $thumbnails}
	{br}{br}{br}{br}
	{/if}
</div>
