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
{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.configure == 1 or $permissions.administrate == 1}
<div class="adminmenubar">
{assign var=i value=0}
<div id="admincontrolpanel" class="yuiadminmenubar yuiadminmenubarnav">
    <div class="bd">
        <ul id="admin-top-ul" class="first-of-type">
{foreach name=cat from=$menu key=cat item=items}

{assign var=perm_name value=$check_permissions[$cat]}
{if $permissions[$perm_name] == 1}
	
            <li class="yuiadminmenubaritem first-of-type">
				<a class="yuiadminmenubaritemlabel" href="javascript:void(0)">{$cat}</a>
                <div id="sub-{$i}" class="yuiadminmenu">
                    <div class="bd">
                        <ul class="admin-first-of-type">
						{foreach name=links from=$items item=info}
                            <li class="yuiadminmenuitem"><a class="yuiadminmenuitemlabel" href="{link module=$info.module action=$info.action}">{$info.title}</a></li>
                       	{/foreach}
						
 						</ul>            
                    </div>
                </div> 

{/if}
{math equation="x+1" x=$i assign=i}
{/foreach}
                   
            </li>
        </ul>            
    </div>
</div>
</div>{/if}
{/permissions}

<script>
{literal}
YAHOO.util.Event.onContentReady("admincontrolpanel",function(){
var aMenuBar = new YAHOO.widget.MenuBar("admincontrolpanel", { visibility:true, autosubmenudisplay: true, hidedelay: 750, lazyload: true });

aMenuBar.render();
});
{/literal}
</script>


