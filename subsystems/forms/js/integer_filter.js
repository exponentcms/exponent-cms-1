/*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 */

function integer_filter_class() {
	
	this.on_key_press = function(ptObject, evt) {
		//This will allow backspace to work.
		evt = (evt) ? evt : event;
		sChar = (evt.charCode) ? evt.charCode : evt.keyCode;
		for (var n =0; n < g_aIgnore.length; n++) {
			if (sChar == g_aIgnore[n]) return true;
		}
		var strNewVal = GetResultingValue(ptObject, String.fromCharCode(sChar));
		
		if (this.isValueIllegal(strNewVal)) {
			return false;
		}
		return true;
	}
	
	this.onBlur = function(ptObject) {
		//Do nothing for integer
	}
	
	this.onFocus = function(ptObject) {
		//Do nothing for integer
	}
	
	this.onPaste = function(ptObject, evt) {
		var strNewVal = GetResultingValue(ptObject, String.fromCharCode(evt.charCode));
		alert(strNewVal);
		if (this.isValueIllegal(strNewVal)) {
			return false;
		}
		return true;
	}
	
	this.isValueIllegal = function(strValue) {
		var bIsIllegal = isNaN(parseInt(strValue, 10));
		if (bIsIllegal == false) {
			bIsIllegal = (strValue.match(/[^0-9]/) != null);
		}
		return bIsIllegal;
	}
}

var integer_filter = new integer_filter_class();
