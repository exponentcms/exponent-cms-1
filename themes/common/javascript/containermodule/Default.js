var contextElements = YAHOO.util.Dom.getElementsByClassName("viewinfo");

for (var i=0;i<=contextElements.length;i++) {
	var tooltip = new YAHOO.widget.Tooltip("tt"+i, { context:contextElements[i] });
}
