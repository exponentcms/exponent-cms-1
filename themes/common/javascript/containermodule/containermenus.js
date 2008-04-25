YAHOO.namespace ("expadminmenus");

YAHOO.util.Event.onDOMReady(function(){

	var containerModuleMenusloader = new YAHOO.util.YUILoader({
	    require: ["menu"],
		base : eXp.URL_FULL+'external/yui/build/',
	    loadOptional: false,
	    onSuccess: function() {
			
			 
		
			eXp.containerModuleMenus = function () {
				var E =YAHOO.util.Event,
					D =YAHOO.util.Dom;
					
				var triggers = YAHOO.util.Dom.getElementsByClassName("modulemenutrigger");

				var containermenu = new YAHOO.widget.Menu("containermodulemenu", { 
					position: "dynamic",
					clicktohide: false,
					classname: "containermenu",
					hidedelay: 350,
					fixedcenter: false
					});

				containermenu.render(document.body);

				YAHOO.util.Event.addListener(triggers, "mouseover", function(e){
					containermenu.hide();
					var el = E.getTarget(e);
					var items = YAHOO.expadminmenus[el.id];
					containermenu.cfg.setProperty("context",[el,"tl","tr"]);
					containermenu.clearContent();
					containermenu.addItems(items);
					containermenu.setItemGroupTitle("For this "+el.getAttribute("rel"), 0);
					containermenu.render();
					containermenu.show();
				});

				function init(){
					containerModuleMenus();
				}
			}();
	    }
	});
	
	containerModuleMenusloader.insert();

});

