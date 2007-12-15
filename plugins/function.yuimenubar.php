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

function smarty_function_yuimenubar($params,&$smarty) {
	
	echo'<script type="text/javascript">

	            // Initialize and render the menu bar when it is available in the DOM

	            function buildmenu() {

	                // "beforerender" event handler for the menu bar

	                function onMenuBarBeforeRender(p_sType, p_sArgs) {

	                    var oSubmenuData = {';
					
								global $sections;
								$menu = "";
								$startdepth = 0;
								foreach ($sections as $key=>$section) {
									if ((count($sections)-1)==$key){ $last = 1; } else {$last = 0; }

									$nextkey = $key+1;
									$previouskey = $key-1; 

									if ($last==true){
										$nextdepth = $startdepth;
									}else{
										$nextdepth = $sections[$nextkey]->depth;
									}


									if ($section->depth==0){
										echo '"'.$section->name.'": [ ';
									} else {
									echo '{ text: "'.addslashes($section->name).$section->depth.'", url: "'.$section->link.'"';
									echo $sections[$nextkey]->depth > $section->depth ? ', submenu: { id: "'.$section->name.'", itemdata: [' : '}';
									echo $sections[$nextkey]->depth == $section->depth ? "," : "";
									}

									if($sections[$nextkey]->depth == 0 && $section->depth ==0 && $last != true){
										echo '],';
									}else if($sections[$nextkey]->depth == 0 && $section->depth == 0 && $last == true){
										echo ']';
									}

									if ($nextdepth < $section->depth){
										//echo "!!!$nextdepth!$last!".count($sections)."!!".$key;
										$looper = $section->depth - $nextdepth;
										//echo $looper;
										//echo $nextdepth;
										for ($l=$looper; $l>=1; $l--){
											//echo $l."L";
											//echo $nextdepth."N";
											if($l==1 && $nextdepth==0){
												echo ']';
											}else{
												echo ']}}';
											}
											if($l==1 && $last!=1 && $nextdepth!=$section->depth){
												echo ',';
											}
										}
									}		
								}
							
												
					
						echo' };

	                    // Add a submenu to each of the menu items in the menu bar
						for (i=0; i<=this.getItems().length; i++){
							itemtext = this.getItem(i).cfg.getProperty("text");
						//	alert(this.getItem(i).cfg.getProperty("text"));
		                    this.getItem(i).cfg.setProperty("submenu", { id: itemtext, itemdata: oSubmenuData[itemtext] });							
						}
	                    //this.getItem(0).cfg.setProperty("submenu", { id: "Home", itemdata: oSubmenuData["Home"] });
	                   // this.getItem(1).cfg.setProperty("submenu", { id: "Government", itemdata: oSubmenuData["Government"] });
	                    //this.getItem(2).cfg.setProperty("submenu", { id: "entertainment", itemdata: oSubmenuData["entertainment"] });
	                    //this.getItem(3).cfg.setProperty("submenu", { id: "information", itemdata: oSubmenuData["information"] });
	                }


	                /*
	                     Instantiate the menubar.  The first argument passed to the 
	                     constructor is the id of the element in the DOM that 
	                     represents the menubar; the second is an object literal 
	                     representing a set of configuration properties for 
	                     the menubar.
	                */

	                var oMenuBar = new YAHOO.widget.MenuBar("'.$params['buildon'].'", { autosubmenudisplay: true, showdelay: 250, hidedelay:  750, lazyload: true });


	                // Subscribe to the "beforerender" event

	                oMenuBar.beforeRenderEvent.subscribe(onMenuBarBeforeRender);


	                /*
	                     Call the "render" method with no arguments since the markup for 
	                     this menu already exists in the DOM.
	                */

	                oMenuBar.render();            

	            }
				YAHOO.util.Event.onDOMReady(buildmenu)
	        </script>';
		
}
	
?>

