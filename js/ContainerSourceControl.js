if (document.body.appendChild) {
	g_noSourceControl = (document.getElementById("r_existing_source") == null);
	
	//g_ta_description  = document.getElementById("ta_description");
	
	if (!g_noSourceControl) {
		g_radio_existing  = document.getElementById("r_existing_source");
		g_radio_new       = document.getElementById("r_new_source");
		g_existing_link	  = document.getElementById("existing_source_link"); 
		
		g_radio_all = new Array(g_radio_existing, g_radio_new);
	}
	
	g_ctl_module = document.getElementById("i_mod");
	g_ctl_views = document.getElementById("view");
	module = null;
	
	
	function activate(type) {
		if (g_noSourceControl) return;
		
		var elem = document.getElementById("noSourceMessageTD");
		clearList(elem);
		if (!module.var_supportsSources) {
			disableAll();
			elem.appendChild(document.createTextNode("This module does not"));
			elem.appendChild(document.createElement("br"));
			elem.appendChild(document.createTextNode("support Sources."));
			//g_ta_description.value = "";
			return;
		}
		switch (type) {
			case "Existing":
				disableAll();
				g_radio_existing.disabled = false;
				g_radio_existing.checked = true;
				g_existing_link.setAttribute("onclick","pickSource(); return false;");
				//g_existing_link.onclick = function() { pickSource(); return false; }
				
				//clearList(g_ta_description);
				//g_ta_description.value = "";
				//g_ta_description.disabled = true;
				
				g_radio_new.disabled = false;
				showPreviewCall();
				break;
			case "New":
				disableAll();
				g_radio_new.disabled = false;
				g_radio_new.checked = true;
				//g_ta_description.disabled = false
				//clearList(g_ta_description);
				//g_ta_description.value = "";
				g_existing_link.setAttribute("onclick","pickSource(); return false;");
				//g_existing_link.onclick = function() { pickSource(); return false; }
				
				g_radio_existing.disabled = false;
				break;
			case null:
				g_radio_new.disabled = false;
				g_radio_existing.disabled = false;
				//g_ta_description.disabled = false
				g_existing_link.setAttribute("onclick","pickSource(); return false;");
				//g_existing_link.onclick = function() { alert("This module does not support Sources"); return false; }
				break;
		}
		sourceInit = true;
		showPreviewCall();
	}
	
	function disableAll() {
		if (g_noSourceControl) return;
		for (i in g_radio_all) {
			g_radio_all[i].disabled = true;
		}
		g_existing_link.setAttribute("onclick",'alert("This module does not support Sources"); return false;');
	}
	
	// Clears out all of the options in a select box.
	function clearList(list) {
	    //console.debug(list);
		while (list.childNodes.length) {
			list.removeChild(list.childNodes.item(0));
		}
	}
	
	// Update the preview image.
	function showPreview(module) {
		var view = g_ctl_views.selectedIndex;
		
		var modclass = currentModule();
		var module = currentModuleObject();
		var sourceHidden = document.getElementById("existing_source");
		var titleText = document.getElementById("title");
		
		var iframe = document.getElementById("iframePreview");
		if (iframe) {
			if (g_noSourceControl) {
				iframe.src = eXp.PATH_RELATIVE+"mod_preview.php?module="+modclass+"&source="+sourceHidden.value+"&view="+g_ctl_views.options[g_ctl_views.selectedIndex].value+"&title="+escape(titleText.value);
			} else { // Adding new
				if ((sourceHidden.value != "" && g_radio_existing.checked == true) && module.var_supportsSources == 1) {
					iframe.src = eXp.PATH_RELATIVE+"mod_preview.php?module="+modclass+"&source="+sourceHidden.value+"&view="+g_ctl_views.options[g_ctl_views.selectedIndex].value+"&title="+escape(titleText.value);
				} else if (g_radio_new.checked == true || module.var_supportsSources == 0) {
					iframe.src = eXp.PATH_RELATIVE+"mod_preview.php?module="+modclass+"&view="+g_ctl_views.options[g_ctl_views.selectedIndex].value+"&title="+escape(titleText.value);
				} else {
					iframe.src = eXp.PATH_RELATIVE+"modules/containermodule/nosourceselected.php";
				}
			}
		}
	}
	
	// The following function is no longer needed.
	function showPreviewCall() {
		module = currentModuleObject();
		showPreview(module);
	}
	
	// Validate the new source
	function validateNew() {
		if (g_noSourceControl) return true;
		
		if (module.var_supportsSources) {
			if (g_radio_new.checked) return true;
			else {
				var sourceHidden = document.getElementById("existing_source");
				if (sourceHidden.value == "") {
					alert("You have chosen to use existing content, but have not selected any.\n\nTo select content, click the 'Use Existing Content' link on the form.  This will show you the Site Content Selector.");
					return false;
				}
			}
		}
		return true;
	}
	
	function writeViews() {
		clearList(g_ctl_views);
		module = currentModuleObject();
		for (key in module.var_views) {
			view = module.var_views[key];
			el = document.createElement("option");
			var txt=document.createTextNode(view);
			
			el.appendChild(txt);
			el.setAttribute("value",view);
			if (module.var_defaultView == view) {
				el.setAttribute("selected","selected");
			}
			g_ctl_views.appendChild(el);
		}
		sourcePicked("","");
		if (!sourceInit) activate("New");
		activate(null);
	}
	
	function currentModule() {
		if (g_ctl_module.options) {
			return g_ctl_module.options[g_ctl_module.selectedIndex].value;
		} else {
			return g_ctl_module.value;
		}
	}
	
	function currentModuleObject() {
		var mod = currentModule();
		for (key in modnames) {
			if (modnames[key] == mod) return modules[key];
		}
		return null;
	}
	
	function pickSource() {
		activate("Existing");
		var mod = currentModule();
		//var url = PATH_RELATIVE+"source_selector.php?showmodules="+mod+"&dest="+escape("modules/containermodule/picked_source.php?dummy")+"&vmod=containermodule&vview=_sourcePicker";
		//window.open(url,'sourcePicker','title=no,toolbar=no,width=640,height=480,scrollbars=yes');
		openSelector(mod,"modules/containermodule/picked_source.php?dummy","containermodule","_sourcePicker");
	}
	
	function sourcePicked(src,desc) {
		sourceSelected("existing_source",true,src,desc);
		
        // clearList(g_ta_description);
        // g_ta_description.appendChild(document.createTextNode(desc));
		
		showPreviewCall();
	}
}