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
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
<div class="hardcoded-menu">
<a id="{$container->info.class}{$container->randomizer}" class="modulemenutrigger hardcoded" href="#" rel="{$container->info.module}">&nbsp;</a>
<span class="modtype viewinfo" title="{$container->info.module}-{$_TR.shown_in|sprintf:$container->view}">
<script>YAHOO.expadminmenus["{$container->info.class}{$container->randomizer}"] =  [{getchromemenu module=$container}]</script>
</div>
{/permissions}
