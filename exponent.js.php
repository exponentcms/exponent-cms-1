<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright 2006 Maxim Mueller
# Written and Designed by James Hunt
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

//Initialize exponent Framework
include_once("exponent.php");
?>
// exponent Javascript Support Systems

// Patch the String object, to make string parsing a little easier in exponent
String.prototype.isValid = function (alpha,numeric,others) {
	for (var i = 0; i < this.length; i++) {
		if (alpha && this.isAlpha(i)) continue;
		if (numeric && this.isNumeric(i)) continue;
		var isGood = false;
		for (var j = 0; j < others.length; j++) {
			if (others[j] == this.charAt(i)) {
				isGood = true;
				continue;
			}
		}
		if (!isGood) return false;
	}
	return true;
}

String.prototype.isNumeric = function(index) {
	var charcode = this.charCodeAt(index);
	return (
		(charcode >= 48 && charcode < 48+10)
	);
}

String.prototype.isAlpha = function(index) {	
	var charcode = this.charCodeAt(index);
	return (
		(charcode >= 65 && charcode < 65+26) ||
		(charcode >= 97 && charcode < 97+26)
	);
}

String.prototype.trim = function() {
	str = this;
	while (1) {
		if (str.substring(str.length - 1, str.length) != " ") break;
		str = str.substr(0,str.length - 1);
	}
	while (1 && str.length > 0) {
		if (str.substring(0,1) != " ") break;
		str = str.substr(1,str.length - 1);
	}
	return str;
}

//introduction of a common namespace object
//TODO: migrate all of E`s JS API to this new object
eXp = new Object();

eXp.LANG = "<?php echo LANG; ?>";
eXp.PATH_RELATIVE = "<?php echo PATH_RELATIVE; ?>";
eXp.THEME_RELATIVE = "<?php echo THEME_RELATIVE; ?>";
eXp.ICON_RELATIVE = "<?php echo ICON_RELATIVE; ?>";
eXp.onLoadInits = new Array(); // array of functions
eXp.openWindows = new Array(); // array of window references.

eXp.includeOnce = function(id, file) {
		// do we even have to do something ?
		if (!document.getElementById(id)){
		
			//TODO: check for html spelling, right now we assume XHTML
			// get the head element 
			myHeadElem = document.getElementsByTagName("head").item(0);
		
			myNewElem = document.createElement("script");
			myNewElem.setAttribute("id",id);
			myNewElem.setAttribute("type","text/javascript");
			myNewElem.setAttribute("defer","false");
			myNewElem.setAttribute("src",file);
			myHeadElem.appendChild(myNewElem);
		} else {
			//TODO: write handling for overiding of scripts, e.g. by themes.
		};	
};

eXp.initialize = function() {
	for (i = 0; i < eXp.onLoadInits.length; i++) {
		if(typeof(eXp.onLoadInits[i]) == "String") {
			//new style allows for function parameters
			eval(eXp.onLoadInits[i]);
		} else {
			eXp.onLoadInits[i]();
		}
	}
}	

eXp.register = function(func) {
	eXp.onLoadInits.push(func);
}

eXp.getCookie = function(name) {
	cookiestr = document.cookie;
	var nameIndex = cookiestr.indexOf(name);
	if (nameIndex != -1) { // found the named cookie
		var startOffset = nameIndex+name.length+1;
		var endOffset = cookiestr.indexOf(";",startOffset);
		if (endOffset == -1) endOffset = cookiestr.length;
		return cookiestr.substring(startOffset,endOffset);
	}
	return "";
}

eXp.makeLink = function() {
	var args = eXp.makeLink.arguments;
	var link = eXp.PATH_RELATIVE + "index.php?";
	for (var i = 0; i < args.length; i+=2) {
		if(typeof(args[i]) == "object") {
			for (var j = 0; j < args[i].length; j+=2) {
				var w = args[i][j+1];
				if (w != null && w != "") {
					link += escape(args[i][j]) + "=" + escape(args[i][j+1]) + "&";
					//alert(link);
				}
			}
		} else {
			var v = args[i+1];
			if (v != null && v != "") {
				link += escape(args[i]) + "=" + escape(args[i+1]) + "&";
			}
		}
	}

	return link.substr(0,link.length - 1);
}

eXp.openWindow = function(url, name, options) {
	for (key in eXp.openWindows) {
		if (key == name) {
			if (eXp.openWindows[key].focus()) {
				return;
			} else {
				break;
			}
		}
	}
	eXp.openWindows[name] = window.open(url,name,options);
}

eXp.openSelector = function(mod, dest, vmod, vview) {
	var url = eXp.PATH_RELATIVE+"source_selector.php?showmodules="+mod+"&dest="+escape(dest)+"&vmod="+vmod+"&vview="+vview;
	openWindow(url,'sourcePicker','title=no,toolbar=no,width=640,height=480,scrollbars=yes');
}

eXp.openContentSelector = function(mod, dest, view) {
	var url = eXp.PATH_RELATIVE+"content_selector.php?showmodules="+mod+"&dest="+escape(dest)+"&vmod=containermodule&vview="+view;
	eXp.openWindow(url,'contentPicker','title=no,toolbar=no,width=640,height=480,scrollbars=yes');
}

eXp.sourceSelected = function(hidden, showPreview, src, desc) {
	var hidden = document.getElementById(hidden);
	hidden.value = src;
	if (showPreview){
		showPreviewCall();
	}
}

eXp.selectAll = function(prefix, checked) {
	elems = document.getElementsByTagName("input");
	for (var key in elems) {
		if (elems[key].type == "checkbox" && elems[key].name.substr(0,prefix.length) == prefix) {
			elems[key].checked = checked;
		}
	}
}

eXp.isOneSelected = function(prefix, enabledOnly) {
	if (typeof enabledOnly == "undefined") {
		enabledOnly = true;
	}
	elems = document.getElementsByTagName("input");
	for (var key in elems) {
		if (elems[key].type == "checkbox" && elems[key].name.substr(0,prefix.length) == prefix) {
			if (enabledOnly && elems[key].checked && !elems[key].disabled) return true;
			if (!enabledOnly && elems[key].checked) return true;
		}
	}
	return false;
}

eXp.i18n = function(i18n_string) {
	if(eXp._TR[i18n_string]) {
		i18n_string = eXp._TR[i18n_string];
	}
	return i18n_string;
}

//FJD - added for easier javascript debugging

function print_r(input, _indent)
{
	if(typeof(_indent) == 'string') {
		var indent = _indent + ' ';
		var paren_indent = _indent + ' ';
	} else {
		var indent = ' ';
		var paren_indent = '';
	}
	switch(typeof(input)) {
		case 'boolean':
			var output = (input ? 'true' : 'false') + "\n";
			break;
		case 'object':
			if ( input===null ) {
				var output = "null\n";
				break;
			}
			var output = ((input.reverse) ? 'Array' : 'Object') + " (\n";
			for(var i in input) {
				output += indent + "[" + i + "] => " + print_r(input[i], indent);
			}
			output += paren_indent + ")\n";
			break;
		case 'number':
		case 'string':
		default:
			var output = "" + input + "\n";
	}
	return output;
}

// This converts a javascript array to a string in PHP serialized format.
// This is useful for passing arrays to PHP. On the PHP side you can 
// unserialize this string from a cookie or request variable. 

function js_array_to_php_array (a)
{
    var a_php = "";
    var total = 0;
    for (var key in a)
    {
        ++ total;
        a_php = a_php + "s:" +
                String(key).length + ":\"" + String(key) + "\";s:" +
                String(a[key]).length + ":\"" + String(a[key]) + "\";";
    }
    a_php = "a:" + total + ":{" + a_php + "}";
    return a_php;
}

function page(page) {
        var uri = 'index.php?module=travelmodule&_common=1&action=page&ajax_action=1' + '&page=' + page;
        YAHOO.util.Connect.asyncRequest('POST', uri, {
                success : function(o) {
                        document.getElementById('page-objects').innerHTML= o.responseText;
                },
                failure : function(o) {
                        alert('An error was encountered while processing your request. ' + o.statusText);
                },
                timeout : 5000
        });
}
