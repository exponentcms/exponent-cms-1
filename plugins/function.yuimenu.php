<?php

##################################################
#
# Copyright (c) 2007-2008 OIC Group, Inc.
# Written and Designed by Phillip Ball
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

function smarty_function_yuimenu($params,&$smarty) {


	//$str = "";
	echo'<script type="text/javascript">
        function buildmenu () {
            var oMenuBar = new YAHOO.widget.MenuBar("flyoutmenujs", { 
													
														constraintoviewport:false,
														postion:"dynamic",
														visible:true,
														zindex:250,
 														autosubmenudisplay: true, 
														hidedelay: 750, 
														lazyload: true });

            var aSubmenuData = ';
				echo navigationmodule::navtojson();
			echo	';
            oMenuBar.subscribe("beforeRender", function () {

                if (this.getRoot() == this) {
					for (i=0; i<=this.getItems().length; i++){
						var j=i+1;
						//console.debug(this.getItem(4));
	                    this.getItem(i).cfg.setProperty("submenu", aSubmenuData[j]);
					}
					
                }

            });

            oMenuBar.render();         
        
        }
		YAHOO.util.Event.onDOMReady(buildmenu);
    </script>
    ';
	
	
}
	
?>