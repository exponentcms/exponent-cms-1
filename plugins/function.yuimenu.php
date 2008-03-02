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
			var oMenu = new YAHOO.widget.Menu("'.$params['buildon'].'", { 
																	position: "static", 
																	hidedelay:	750, 
																	lazyload: true });

			var sidenav = ';
				echo navigationmodule::navtojson();
			echo ';
			oMenu.subscribe("beforeRender", function () {

				if (this.getRoot() == this) {
	
				//	console.debug(this.getItems().length);
					
					for (i=0; i<=this.getItems().length; i++){
						//	var j=i+1;
						if(sidenav[i].itemdata!=""){
							//console.debug(sidenav[i].itemdata);
						this.getItem(i).cfg.setProperty("submenu", sidenav[i]);
						}
					}
					
				}

			});

			oMenu.render();			
		
		}
		YAHOO.util.Event.onDOMReady(buildmenu);
	</script>
	';
	
	
}
	
?>