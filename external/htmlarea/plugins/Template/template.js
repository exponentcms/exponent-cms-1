// Simple Template (className) plugin for the editor
// Sponsored by http://www.oicgroup.net/
// Implementation by James Hunt for Pathos, http://pathoscms.org
//
// (c) James Hunt and the OIC Group, 2003-2004
// Distributed under the same terms as HTMLArea itself.
// This notice MUST stay intact for use (see license.txt).
//
// $Id$

function Template(editor, params) {
	this.editor = editor;
	var cfg = editor.config;
	var toolbar = cfg.toolbar;
	var self = this;
	var i18n = Template.I18N;
	var plugin_config = params[0];
	var combos = plugin_config.combos;

	var first = true;
	for (var i = combos.length; --i >= 0;) {
		var combo = combos[i];
		var id = "Template-class" + i;
		var template_class = {
			id         : id,
			options    : combo.options,
			action     : function(editor) { self.onSelect(editor, this, combo.context, combo.updatecontextclass); },
			refresh    : function(editor) { self.updateValue(editor, this); },
			context    : combo.context
		};
		cfg.registerDropdown(template_class);

		// prepend to the toolbar
		toolbar[1].splice(0, 0, first ? "separator" : "space");
		toolbar[1].splice(0, 0, id);
		
		if (combo.label)
			toolbar[1].splice(0, 0, "T[" + combo.label + "]");
		first = false;
	}
};

Template._pluginInfo = {
	name          : "Template",
	version       : "1.0",
	developer     : "James Hunt",
	developer_url : "http://www.pathoscms.org/",
	c_owner       : "James Hunt",
	sponsor       : "OIC Group, Inc.",
	sponsor_url   : "http://www.oicgroup.net/",
	license       : "BSD"
};

Template.prototype.onSelect = function(editor, obj, context, updatecontextclass) {
	var tbobj = editor._toolbarObjects[obj.id];
	var index = tbobj.element.selectedIndex;
	var template = tbobj.element.value;
	editor.insertHTML(template);
	tbobj.element.selectedIndex = 0;
};

Template.prototype.updateValue = function(editor, obj) {
};
