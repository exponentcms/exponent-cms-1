var used = new Array();
var rows = new Array();
var rowlens = new Array();


var g_row = 0;
var g_pos = 0;
var g_maxRowLength = 0;

var removeIcon = ICON_RELATIVE+"delete.gif";
var removeDisabledIcon = ICON_RELATIVE+"delete.disabled.gif";
var lastTd = null;
var imageSuffix = ".gif";

function newRow() {
	rows.push(new Array());
	rowlens.push(0);
	g_pos = 0;
	g_row = rows.length - 1;
	regenerateTable();
}

function recurseClear(elem) {
	while (elem.childNodes.length) {
		recurseClear(elem.childNodes[0]);
		elem.removeChild(elem.childNodes[0]);
	}
}

function regenerateTable() {
	maxRowLength();

	var tbody = document.getElementById("toolbar_workspace");
	recurseClear(tbody);
	
	for (rownum in rows) {
		var tr = document.createElement("tr");
		tr.setAttribute("id","row"+rownum);
		
		tr.appendChild(addLinkTd(rownum,0));
		for (itemkey in rows[rownum]) {
			tr.appendChild(iconTd(rows[rownum][itemkey],rownum,parseInt(itemkey)));
			tr.appendChild(addLinkTd(rownum,parseInt(itemkey)+1));
		}
		
		for (i = rowlens[rownum]; i < g_maxRowLength; i++) {
			tr.appendChild(blankTd());
			tr.appendChild(blankTd());
		}
		
		tr.appendChild(delRowLinkTd(rownum));
		
		tbody.appendChild(tr);
	}
	
}

function addLinkTd(rownum,pos) {
	var td = document.createElement("td");
	if (document.all) {
		td.attachEvent('onclick',function() {
			clickedTd(event.srcElement,rownum,pos);
		});

		td.attachEvent('onmouseover',function() {
			event.srcElement.style.backgroundColor = '#CCCCCC';
		});

		td.attachEvent('onmouseout',function() {
			unColorLink(event.srcElement,rownum,pos);
		});

		td.width = 2;
		td.height = 20;
		td.style.border = "1px dashed #CCCCCC";
	} else {
		td.setAttribute("onClick","clickedTd(this,"+rownum+","+pos+"); return false;");
		td.setAttribute("onmouseover","this.style.background='grey'");
		td.setAttribute("onmouseout","unColorLink(this,"+rownum+","+pos+")");
		td.setAttribute("width","2");
		td.setAttribute("height","20");
	}
	if (pos == g_pos && rownum == g_row) {
		if (document.all) {
			td.style.backgroundColor = '#0000FF';
			td.style.cursor = 'pointer';
		} else {
			td.setAttribute("style","background-color: blue; cursor: pointer;");
		}
		lastTd = td;
	} else {
		if (document.all) {
			td.style.cursor = 'pointer';
		} else {
			td.setAttribute("style","cursor: pointer;");
		}
	}
	return td;
}

function unColorLink(td,rownum, pos) { 
	if ((g_pos != pos) || (g_row != rownum)) {
		if (document.all) {
			td.style.backgroundColor = 'transparent';
		} else {
			td.style.background = 'transparent';
		}
	}
	else {
		if (document.all) {
			td.style.backgroundColor = '#0000FF';
		} else {
			td.style.background = "blue";
		}
	}
}

function delRowLinkTd(rownum) {
	var td = document.createElement("td");
	
	var img = document.createElement("img");
	if (rows.length == 1 && rows[0].length == 0) {
		img.setAttribute("src",removeDisabledIcon);
	} else {
		img.setAttribute("src",removeIcon);
	}
	img.setAttribute("style","cursor: pointer;");
	if (document.all) {
		img.attachEvent('onclick',function() {
			delRow(rownum);
		});
	} else {
		img.setAttribute("onClick","delRow("+rownum+")");
	}
	
	td.appendChild(img);
	
	return td;
}

function iconTd(icon,rownum, pos) {
	var td = document.createElement("td");
	if (document.all) {
		td.attachEvent('onclick',function() { 
			deleteIconTd(this,rownum,pos);
		});

		td.attachEvent('onmouseover',function() {
			event.srcElement.style.backgroundColor = '#FF0000';
		});
		
		td.attachEvent('onmouseout',function() {
			event.srcElement.style.backgroundColor = 'transparent';
		});
		
		td.style['cursor'] = 'pointer';
		td.style['background-color'] = 'inherit';
		td.style.border = "1px dashed #CCCCCC";
		td.colspan = (toolbarIconSpan(icon)-1)*2+1;
	} else {
		td.setAttribute("onClick","deleteIconTd(this,"+rownum+","+pos+"); return false;");
		td.setAttribute("onmouseover","this.style['background-color']='#FF0000'");
		td.setAttribute("onmouseout","this.style['background-color']='inherit'");
		td.setAttribute("style","cursor: pointer;");
		td.setAttribute("colspan",(toolbarIconSpan(icon)-1)*2+1);
	}
	var img = document.createElement("img");
	img.setAttribute("src",imagePrefix+icon+imageSuffix);
	
	td.appendChild(img);
	
	return td;
}

function deleteIconTd(td,rownum,pos) {
	if (confirm("Are you sure you want to remove this?")) {
		enableToolbox(rownum, rows[rownum][pos])
		rowlens[rownum] -= toolbarIconSpan(rows[rownum][pos]);
		rows[rownum].splice(pos,1);
		regenerateTable();
	}
}

function blankTd() {
	var td = document.createElement("td");
	if (document.all) {
		td.style['backgroundColor'] = '#CCCCCC';
		td.colspan = 1;
	} else {
		td.setAttribute("colspan","1");
		td.setAttribute("style","background-color: lightgrey;");
	}
	
	td.appendChild(document.createTextNode(" "));
	
	return td;
}

function clickedTd(td,new_row,new_pos) {
	g_pos = new_pos;
	g_row = new_row;
	if (lastTd) {
		if (document.all) {
			lastTd.style.backgroundColor = 'transparent';
		} else {
			lastTd.style.background="inherit";
		}
	}
	if (document.all) {
		td.style.backgroundColor = '#0000FF';
	} else {
		td.style.background = "blue";
	}
	lastTd = td;
}

function maxRowLength() {
	g_maxRowLength = 0;
	for (key in rowlens) {
		if (rowlens[key] > g_maxRowLength) g_maxRowLength = rowlens[key];
	}
}

function register(icon) {
	rows[g_row].splice(g_pos,0,icon);
	rowlens[g_row] += (toolbarIconSpan(icon));
	maxRowLength();
	g_pos++;
	regenerateTable();
	disableToolbox(icon);
}

function toolbarIconSpan(icon) {
	var tb_td = document.getElementById("td_"+icon);
	return parseInt(tb_td.getAttribute("colspan"));
}

function enableToolbox(rownum, key) {
	//for (key in rows[rownum]) {
		// clear used
		for (key2 in used) {
			if (used[key2] == key) {
				var td = document.getElementById("td_"+used[key2]);
				var a = document.getElementById("a_"+used[key2]);
				
				td.removeAttribute("style");
				if (document.all) {
					td.onmouseover = ie_highlight;
					td.onmouseout = ie_unhighlight;
					a.onclick = ie_register;
					a.holding = used[key2];
				} else {
					td.setAttribute("onmouseover","this.style.background=\"red\"");
					td.setAttribute("onmouseout","this.style.background=\"white\"");
					a.setAttribute("onClick","register('"+used[key2]+"')");
				}
				used.splice(key2,1);
			}
		}
	//}	
}

function disableToolbox(icon) {
	if (icon != "space" && icon != "separator") {
		used.push(icon);		
		
		var td = document.getElementById("td_"+icon);
		var a = document.getElementById("a_"+icon);
		if (document.all) {
			td.style.backgroundColor = '#CCCCCC';
			td.onmouseover = function() { return false; };
			td.onmouseout = function() { return false; };
			a.onclick = function() { return false; };
		} else {
			td.setAttribute("style","background-color: grey");
			td.removeAttribute("onmouseover");
			td.removeAttribute("onmouseout");
			
			a.removeAttribute("onClick");
		}
	}
}

function delRow(rownum) {
	for (key in rows[rownum]) {
		for (key2 in used) {
			if (used[key2] == rows[rownum][key]) {
				var td = document.getElementById("td_"+used[key2]);
				var a = document.getElementById("a_"+used[key2]);
				
				td.removeAttribute("style");
				if (document.all) {
					td.attachEvent('onmouseover',ie_highlight);
					td.attachEvent('onmouseout',ie_unhighlight);
					a.attachEvent('onclick',ie_register);
					a.holding = used[key2];
				} else {
					td.setAttribute("onmouseover","this.style.background=\"red\"");
					td.setAttribute("onmouseout","this.style.background=\"white\"");
					a.setAttribute("onClick","register('"+used[key2]+"')");
				}
				used.splice(key2,1);
			}
		}
	}
	rows.splice(rownum,1);
	rowlens.splice(rownum,1);
	if (rows.length == 0) {
		rows.push(new Array());
		rowlens.push(0);
	}
	g_pos = 0;
	g_row = 0;
	regenerateTable();
}

function save(frm) {
	var saveStr = "";
	for (i = 0; i < rows.length; i++) {
		for (j = 0; j < rows[i].length; j++) {
			saveStr += rows[i][j];
			if (j != rows[i].length-1) saveStr+=";";
		}
		if (i != rows.length - 1) saveStr += ":";
	}
	
	input = document.getElementById("config_htmlarea");
	input.setAttribute("value",saveStr);
	frm.submit();
}


// Stupid IE event interface functions

function ie_highlight() {
	event.srcElement.style.backgroundColor = '#FF0000';
}

function ie_unhighlight() {
	event.srcElement.style.backgroundColor = 'transparent';
}

function ie_register() {
	register(event.srcElement.holding);
}
