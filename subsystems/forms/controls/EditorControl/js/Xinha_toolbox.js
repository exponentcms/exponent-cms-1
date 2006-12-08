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
 	"about" : ["About Xinha", "/external/editors/Xinha/images/ed_about.gif", ""],
        "align_center" : ["Align Center", "/external/editors/Xinha/images/ed_align_center.gif", ""],
        "align_justify" : ["Align Justify", "/external/editors/Xinha/images/ed_align_justify.gif", ""],
        "align_left" : ["Align Left", "/external/editors/Xinha/images/ed_align_left.gif", ""],
        "align_right" : ["Align Right", "/external/editors/Xinha/images/ed_align_right.gif", ""],
        "blank" : ["Blank", "/external/editors/Xinha/images/ed_blank.gif", ""],
        "charmap" : ["Character Map", "/external/editors/Xinha/images/ed_charmap.gif", ""],
        "clearfonts" : ["Clear Fonts", "/external/editors/Xinha/images/ed_clearfonts.gif", ""],
        "color_bg" : ["Background Color", "/external/editors/Xinha/images/ed_color_bg.gif", ""],
        "color_fg" : ["Foreground Color", "/external/editors/Xinha/images/ed_color_fg.gif", ""],
        "copy" : ["Copy", "/external/editors/Xinha/images/ed_copy.gif", ""],
        "custom" : ["Custom", "/external/editors/Xinha/images/ed_custom.gif", ""],
        "cut" : ["Cut", "/external/editors/Xinha/images/ed_cut.gif", ""],
        "delete" : ["Delete", "/external/editors/Xinha/images/ed_delete.gif", ""],
        "format_bold" : ["Bold", "/external/editors/Xinha/images/ed_format_bold.gif", ""],
        "format_italic" : ["Italics", "/external/editors/Xinha/images/ed_format_italic.gif", ""],
        "format_strike" : ["Strike Thru", "/external/editors/Xinha/images/ed_format_strike.gif", ""],
        "format_sub" : ["Subscript", "/external/editors/Xinha/images/ed_format_sub.gif", ""],
        "format_sup" : ["Sup", "/external/editors/Xinha/images/ed_format_sup.gif", ""],
        "format_underline" : ["Underline", "/external/editors/Xinha/images/ed_format_underline.gif", ""],
        "help" : ["Help", "/external/editors/Xinha/images/ed_help.gif", ""],
        "hr" : ["Horizontal Rule", "/external/editors/Xinha/images/ed_hr.gif", ""],
        "html" : ["View Source", "/external/editors/Xinha/images/ed_html.gif", ""],
        "image" : ["Insert Image", "/external/editors/Xinha/images/ed_image.gif", ""],
        "indent_less" : ["Indent Less", "/external/editors/Xinha/images/ed_indent_less.gif", ""],
        "indent_more" : ["Indent More", "/external/editors/Xinha/images/ed_indent_more.gif", ""],
        "killword" : ["Kill Word", "/external/editors/Xinha/images/ed_killword.gif", ""],
        "left_to_right" : ["Left to Right", "/external/editors/Xinha/images/ed_left_to_right.gif", ""],
        "link" : ["Insert Web Link", "/external/editors/Xinha/images/ed_link.gif", ""],
        "list_bullet" : ["Bulleted List", "/external/editors/Xinha/images/ed_list_bullet.gif", ""],
        "list_num" : ["Numeric List", "/external/editors/Xinha/images/ed_list_num.gif", ""],
        "overwrite" : ["Overwrite", "/external/editors/Xinha/images/ed_overwrite.gif", ""],
        "paste" : ["Paste", "/external/editors/Xinha/images/ed_paste.gif", ""],
        "print" : ["Print", "/external/editors/Xinha/images/ed_print.gif", ""],
        "redo" : ["Redo", "/external/editors/Xinha/images/ed_redo.gif", ""],
        "right_to_left" : ["Right to Left", "/external/editors/Xinha/images/ed_right_to_left.gif", ""],
        "rmformat" : ["Remove Format", "/external/editors/Xinha/images/ed_rmformat.gif", ""],
        "save" : ["Save", "/external/editors/Xinha/images/ed_save.gif", ""],
        "saveas" : ["Save As", "/external/editors/Xinha/images/ed_saveas.gif", ""],
        "selectall" : ["Select All", "/external/editors/Xinha/images/ed_selectall.gif", ""],
        "show_border" : ["Show Border", "/external/editors/Xinha/images/ed_show_border.gif", ""],
        "splitblock" : ["Split Block", "/external/editors/Xinha/images/ed_splitblock.gif", ""],
        "splitcel" : ["Split Cell", "/external/editors/Xinha/images/ed_splitcel.gif", ""],
        "undo" : ["Undo", "/external/editors/Xinha/images/ed_undo.gif", ""],
        "word_cleaner" : ["Remove MS Word Formatting", "/external/editors/Xinha/images/ed_word_cleaner.gif", ""],
        "fullscreen_maximize" : ["Maximize Fullscreen", "/external/editors/Xinha/images/fullscreen_maximize.gif", ""],
        "fullscreen_minimize" : ["Minimize", "/external/editors/Xinha/images/fullscreen_minimize.gif", ""],
        "insert_table" : ["Insert Table", "/external/editors/Xinha/images/insert_table.gif", ""],
        "insertfilelink" : ["Insert File Link", "/external/editors/Xinha/images/insertfilelink.gif", ""],
        "insertmacro" : ["Insert Macro", "/external/editors/Xinha/images/insertmacro.png", ""],
        "tidy.gif" : ["Tidy", "/external/editors/Xinha/images/tidy.gif", ""],
        "toggle_borders" : ["Toggle Borders", "/external/editors/Xinha/images/toggle_borders.gif", ""],
};
