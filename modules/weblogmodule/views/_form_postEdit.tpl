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
<div class="weblog postedit">
	<div class="form_header">
		<h1>{if $is_edit == 1}{$_TR.form_title_edit}{else}{$_TR.form_title_new}{/if}</h1>
	</div>
	<hr size="1" />
	{$form_html}
</div>
