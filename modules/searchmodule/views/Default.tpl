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
 
<div class="searchmodule searchdefault">
	<form id="searchform" method="post" action="{$smarty.const.URL_FULL}index.php" enctype="">	
		<div>
			<input type="hidden" name="module" id="module" value="searchmodule" />
			<input type="hidden" name="src" id="src" value="{$loc->src}" />
			<input type="hidden" name="int" id="int" value="" />
			<input type="hidden" name="action" id="action" value="search" />
			{if $moduletitle != ""}
				<label for="search_string"><strong>{$moduletitle}</strong></label>{br}
			{else}
				<label for="search_string">{$_TR.keyword}</label>{br}
			{/if}
			<input id="search_string" type="text" name="search_string" size="15" maxlength="255" value="{$_TR.enter_keyword}" onfocus="if (this.value==this.defaultValue) this.value=''; else this.select()" onblur="if (!this.value) this.value=this.defaultValue" />
			<input id="searchbutton" class="button" type="submit" name="search_submit" value="{$_TR.search}" />
		</div>
	</form>
	<p><a class="mngmntlink" href="{link module=searchmodule _common=1 view='Advanced Search' action='show_view'}">{$_TR.advanced}</a></p>
</div>
