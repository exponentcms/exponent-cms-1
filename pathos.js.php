<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

//Initialize Pathos Framework
include_once("pathos.php");
?>
// Pathos Javascript Support Systems

var onLoadInits = new Array(); // array of functions

function pathosJSinitialize() {
	for (i = 0; i < onLoadInits.length; i++) {
		onLoadInits[i]();
	}
}

function pathosJSregister(func) {
	onLoadInits.push(func);
}

var PATH_RELATIVE = "<?php echo PATH_RELATIVE; ?>";
var THEME_RELATIVE = "<?php echo THEME_RELATIVE; ?>";
var ICON_RELATIVE = "<?php echo ICON_RELATIVE; ?>";
var SEF_URLS = <?php echo SEF_URLS; ?>;

function pathosGetCookie(name) {
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

/* Not sure if we need ALL of these.
function pathosPutCookie(name,newcookie) {
	var cookiestr = document.cookie;
	var nameIndex = cookiestr.indexOf(name);
	if (nameIndex != -1) { // found the named cookie
		var startOffset = nameIndex+name.length+1;
		var endOffset = cookiestr.lastIndexOf(";",startOffset);
		if (endOffset == -1) endOffset = cookiestr.length;
		cookiestr = cookiestr.substring(0,nameIndex)+cookiestr.substring(endOffset,cookiestr.length);
	}
	if (cookiestr != "") cookiestr += ";";
	cookiestr += name + "=" + newcookie;
	document.cookie = cookiestr;
}

function pathosParseCookie(cookie) {
	if (cookie == "") return new Array();
	
	var broken_cookie = cookie.split("'");
	for (var i = 0; i < broken_cookie.length; i++) {
		broken_cookie[i] = broken_cookie[i].split("\"");
	}
	return broken_cookie;
}

function pathosUnparseCookie(broken_cookie) {
	if (broken_cookie.length == 0) return "";
	cookiedata = new Array();
	for (var i = 0; i < broken_cookie.length; i++) {
		cookiedata[cookiedata.length] = broken_cookie[i].join("\"");
	}
	str = cookiedata.join("'");
	return str;
}

function pathosUnsetCookieVar(varname,val,broken_cookie) {
	for (var i = 0; i < broken_cookie.length; i++) {
		if (broken_cookie[i][0] == varname) {
			if (val == "" || broken_cookie[i][1] == val) {
				broken_cookie.splice(i,1);
				i--; // needed, because we removed i, so next iteration would skip an element.
			}
		}
	}
}

function pathosSetCookieVar(varname,val,broken_cookie) {
	broken_cookie[broken_cookie.length] = new Array(varname,val);
}
*/

function makeLink() {
	var link = "";
	var args = makeLink.arguments;
	if (SEF_URLS == 0) {
		link = PATH_RELATIVE + "?";
		for (var i = 0; i < args.length; i+=2) {
			var v = args[i+1];
			if (v != null && v != "") {
				link += escape(args[i]) + "=" + escape(args[i+1]) + "&";
			}
		}
		link = link.substr(0,link.length - 1);
	} else {
		link = PATH_RELATIVE + "index.php/";
		for (var i = 0; i < args.length; i+=2) {
			var v = args[i+1];
			if (v != null && v != "") {
				link += escape(args[i]) + "/" + escape(args[i+1]) + "/";
			}
		}
		link = link.substr(0,link.length - 1);
	}
	return link;
}

function openSelector(mod,dest,vmod,vview) {
	var url = PATH_RELATIVE+"source_selector.php?showmodules="+mod+"&dest="+escape(dest)+"&vmod="+vmod+"&vview="+vview;
	window.open(url,'sourcePicker','title=no,toolbar=no,width=640,height=480,scrollbars=yes');
}

// Patch the String object, to make string parsing a little easier in Pathos
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