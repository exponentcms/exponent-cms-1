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

<div class="textmodule form-edit">
	<div class="form_title">
		{$_TR.form_title}
	</div>
	<div class="form_header">
		<p>{$_TR.form_header}</p>
	</div>
	{form action="save_desc"}
		{control type="hidden" name="id" value=$textitem->id}
		{control type="hidden" name="location_data" value=$textitem->location_data}
		{control type="hidden" name="enable_categories" value=$textitem->enable_categories}	
		{control type="hidden" name="orderby" value=$textitem->orderby}	
		{control type="hidden" name="orderhow" value=$textitem->orderhow}
		{control type="hidden" name="allow_anon_downloads" value=$textitem->allow_anon_downloads}
		{control type="hidden" name="require_agreement" value=$textitem->require_agreement}
		{control type="hidden" name="agreement_body" value=$textitem->agreement_body}
		{control type="hidden" name="enable_rss" value=$textitem->enable_rss}
		{control type="hidden" name="feed_title" value=$textitem->feed_title}
		{control type="hidden" name="feed_desc" value=$textitem->feed_desc}
		{control type="editor" name="description" value=$textitem->description}
		{control type=buttongroup submit="Save" cancel="Cancel"}	
	{/form}
</div>
