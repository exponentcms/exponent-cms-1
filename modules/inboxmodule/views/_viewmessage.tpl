{*
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
 *}
<b>{$message->subject}</b>
<div style="padding: 5px; background-color: #CCC;">
{capture assign=date}{$message->date_sent|format_date:$smarty.const.DISPLAY_DATE_FORMAT}{/capture}
{$_TR.sent_when|sprtinf:$date:$message->from_name}
</div>
<div style="padding: 5px; background-color: #DDD;">
{$message->body}
</div>
<a class="mngmntlink inbox_mngmntlink" href="{link action=compose replyto=$message->id}">{$_TR.reply}</a>
<hr size="1" />
<a class="mngmntlink inbox_mngmntlink" href="{link action=inbox}">{$_TR.back}</a>