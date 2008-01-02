{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by Adam Kessler
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

<div id="upload">
	<form id="uploadFrm" name="uploadFrm" method="post" action="{$smarty.const.URL_FULL}index.php" enctype="multipart/form-data" onsubmit="return uploadFile();">
        	<input type="hidden" name="module" value="cermi" />
                <input type="hidden" name="action" value="upload_standalone" />
                <input type="hidden" name="ajax_action" value="1" />
                <input type="hidden" name="name" value="" />
                <input type="hidden" name="item_type" value="{$item_type}" />
                <input type="hidden" name="item_id" value="{$item_id}" />
                {$_TR.img_upload} <input id="file-name" type="file" name="file" />
                <br /><input id="submit-btn" type="submit" value="Upload" />
	</form>
</div>
