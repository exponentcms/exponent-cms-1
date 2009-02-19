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
 <div class="contentrotatormodule rotate-default">
     {if $moduletitle}<h1>{$moduletitle}</h1>{/if}
     {permissions level=$smarty.const.UILEVEL_PERMISSIONS}
     {if $permissions.administrate == 1}
     	<a href="{link action=userperms _common=1}" title="{$_TR.alt_userperm}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
     	<a href="{link action=groupperms _common=1}" title="{$_TR.alt_groupperm}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
     	<br />
     {/if}
     {/permissions}
     <div class="bodycopy">
         {$content->text}
     </div>
     {permissions level=$smarty.const.UILEVEL_NORMAL}
     {if $permissions.manage == 1}
         <a href="{link action=manage}">{$_TR.manage}</a>
     {/if}
     {/permissions}
    
 </div>
