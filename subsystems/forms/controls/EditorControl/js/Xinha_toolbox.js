//##################################################
//#
//# Copyright (c) 2006 Maxim Mueller
//#
//# This file is part of Exponent
//#
//# Exponent is free software; you can redistribute
//# it and/or modify it under the terms of the GNU
//# General Public License as published by the Free
//# Software Foundation; either version 2 of the
//# License, or (at your option) any later version.
//#
//# GPL: http://www.gnu.org/licenses/gpl.txt
//#
//##################################################

//this file provides an Array associating availiable Actions, their Icons, and, if required for this action, their plugins, with their internal ids
//TODO: determine whether the Editor provides a queryable API for that
//TODO: adjust for themes

//TODO: possibly create a subobject WYSIWYG ?
// first = action name
// second = icon location
// third = required plugin

eXp.WYSIWYG_toolboxbuttons =	{
				"about" : ["About Xinha", "external/editors/Xinha/images/ed_about.gif", ""],
				"cut" : ["cut", "external/editors/Xinha/images/ed_cut.gif", ""],
				"createlink" : ["link", "external/editors/Xinha/images/ed_link.gif", ""],
				"insertimage" : ["image", "external/editors/Xinha/images/ed_image.gif", ""],
				"abbreviation" : ["Abbreviation Dialog", "external/editors/Xinha/plugins/Abbreviation/img/ed_abbreviation.gif", "Abbreviation"]
			};