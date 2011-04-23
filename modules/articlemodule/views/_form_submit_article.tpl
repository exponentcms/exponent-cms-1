{*
 *
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 *}
<div class="form_header">
        <h1>Submit Article</h1>
        <p>To submit an article, simply fill out the form below and attach your article as a Microsoft Word file.</p>
</div>

{form action="save_submission" enctype="multipart/form-data"}
	{control type=text name=title label="Article Title"}
	{control type=text name=submitter_name label="Your Name"}
	{control type=text name=submitter_email label="Your Email Address"}
	{control type=file name=file label="Attach Article (as MS Word Document)"}
	{control type=buttongroup submit="Submit Article" cancel=Cancel}
{/form}
