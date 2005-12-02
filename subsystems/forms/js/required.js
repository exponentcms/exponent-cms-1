/*
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 */

g_rgName = new Array();

function trim(s) {
	while (s.charAt(0) == " ") {
		s = s.substr(1);
	}
	return s;
}

function registerRG(name) {
	g_rgName[g_rgName.length] = name;
}

function unregisterRG(name) {
	for (var x = 0; x < g_rgName.length; x++) {
		if (g_rgName[x] == name) {
			g_rgName = "";
		}
	}
}

function checkRG() {
	for (var x = 0; x < g_rgName.length; x++) {
		if (g_rgName[x] != "") {
			alert("Missing required selection for " + unescape(g_rgName[x]));
			return false;
		}
	}
	return true;
}

function checkRequired(locForm) {
  for (field in locForm.elements) {
	if (locForm.elements[field]) {
		if (locForm.elements[field].getAttribute) {
			s = locForm.elements[field].getAttribute("required");
			if (s != null) {
				val = trim(locForm.elements[field].value);
				s = unescape(s);
				if ((s == val) || (val == "")) {
					locForm.elements[field].focus();
					alert(unescape(locForm.elements[field].getAttribute("caption")) + " is a required field.");
					return false;
				}
			}
		}
	}
  }
  if (!checkRG()) return false;
  return true;
}

