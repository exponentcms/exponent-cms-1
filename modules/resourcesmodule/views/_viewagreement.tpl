{*
 * Copyright (c) 2004-2011 OIC Group, Inc.
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
 *}

<div class="resourcesmodule viewagreement">
	<h1>Confidentiality Agreement</h1>
	<div class="agreement">
		{$config->agreement_body}
	</div>
	<div class="signature">
		<h2>Electronic Signature</h2>
		<p>
			Duis blandit imputo eum, conventio abluo reprobo ut. Ne elit eum ille nulla vulputate capio exerci aliquip iusto sed nostrud. In abdo refero humo qui tego demoveo mos abico, vicis minim vicis refero pneum eligo. Quia delenit ingenium gemino dolore praesent. Ullamcorper duis refero nulla sed ex illum in ratis. Imputo appellatio utinam mauris pagus, at luctus brevitas euismod. Lucidus sudo tum vel typicus macto metuo antehabeo consequat. Ex opto quis populus qui cui distineo immitto, commoveo feugiat praesent euismod vel autem.
		</p>
		{form action="signagreement"}
			{control type="hidden" name="id" value=$id}
			{control type="hidden" name="src" value=$__loc->src}
			<div class="confirmbox">
				{control type="checkbox" name="confirm" label="I hearby certify this personal information <br>to be true" flip=true}
			</div>
			<div class="userdata">
				<span class="ulabel">Name</span><span class="udata">{$user->firstname} {$user->lastname}</span><br>
				<span class="ulabel">Address</span><span class="udata">{$user->address}</span><br>
				<span class="ulabel">&nbsp;</span><span class="udata">{$user->address1}</span><br>
				<span class="ulabel">&nbsp;</span><span class="udata">{$user->city}, {$user->state} {$user->zip}</span><br>
				<span class="ulabel">Phone</span><span class="udata">{$user->workphone}</span><br>
				<span class="ulabel">Email</span><span class="udata">{$user->email}</span>
			</div>
			<div class="textboxes">
				<p>
					Please type your information in the boxes below.  Your entry of your first and last name below indicates your agreement to all terms and provisions to this letter.
				</p>
				<div class="names">
					{control type=text name=firstname label="First Name"}
					{control type=text name=lastname label="Last Name"}
				</div>
				{control type=buttongroup submit="I agree"}
			</div>
		{/form}
	</div>
</div>