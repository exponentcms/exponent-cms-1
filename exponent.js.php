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

//DEPRECATED: Compatibility layer
function pathosJSinitialize() {
	exponentJSinitialize();
}
function pathosJSregister(func) {
	exponentJSregister(func);
}
function pathosGetCookie(name) {
	return exponentGetCookie(name);
}
//End compatibility layer

//DEPRECATED: functional wrapper around OO API
function exponentJSinitialize() {
	eXp.initialize();	
}

//DEPRECATED: functional wrapper around OO API
function exponentJSregister(func) {
	eXp.register(func);
}

//DEPRECATED: functional wrapper around OO API
function exponentGetCookie(name) {
	eXp.getCookie(name);
}

//DEPRECATED: functional wrapper around OO API
function makeLink() {
	eXp.makeLink();
}

//DEPRECATED: functional wrapper around OO API
function openWindow(url, name, options) {
	eXp.openWindow(url, name, options);
}

//DEPRECATED: functional wrapper around OO API
function openSelector(mod, dest, vmod, vview) {
	eXp.openSelector(mod, dest, vmod, vview);
}

function openContentSelector(mod,dest,view) {
	var url = PATH_RELATIVE+"content_selector.php?showmodules="+mod+"&dest="+escape(dest)+"&vmod=containermodule&vview="+view;
	openWindow(url,'contentPicker','title=no,toolbar=no,width=640,height=480,scrollbars=yes');
}

function sourceSelected(hidden,showPreview,src,desc) {
	var hidden = document.getElementById(hidden);
	hidden.value = src;
	if (showPreview){
	showPreviewCall();
	}
}

function selectAll(prefix,checked) {
	elems = document.getElementsByTagName("input");
	for (var key in elems) {
		if (elems[key].type == "checkbox" && elems[key].name.substr(0,prefix.length) == prefix) {
			elems[key].checked = checked;
		}
	}
}

function isOneSelected(prefix,enabledOnly) {
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
		// if the last sign is a ")", then we are likely to have a function -> new style allows for function parameters
		if (eXp.onLoadInits[i].charAt(eXp.onLoadInits[i].length - 1) == ")") {
			eval(eXp.onLoadInits[i]);
		} else {
			eXp.onLoadInits[i]();
		}
		
	}
}	

eXp.register = function(func) {
	eXp.onLoadInits.push(func);
}

eXp.getCookie() = function(name) {
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
	link = eXp.PATH_RELATIVE + "index.php?";
	for (var i = 0; i < args.length; i+=2) {
		var v = args[i+1];
		if (v != null && v != "") {
			link += escape(args[i]) + "=" + escape(args[i+1]) + "&";
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