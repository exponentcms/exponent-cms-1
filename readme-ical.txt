ExponentCMS v0.98 Calendar iCalendar feed implementation

There are NO guarantees this may not open up security vulnerabilites on your site!  Please use with caution!

Install into the root ExponentCMS directory.

To use, click on the ical feed icon next to the calendar module or event title.  This will download the .ics file.  Alternatively, you can copy that shortcut/link and use it as a feed for calendar programs such as MS Outlook (2007/2010) or Google Calendar (others not tested)

The script responds with either an error or displays a copy of the sent e-mail contents.

Currently this action
- requires either an event id or calendar src as a parameter
- adhere's to aggregated/merged calendars
- passes a single event when a event id is passed
- passes all furture events if a calendar module src is passed
- passes "month's" events if a calendar src is passed with a time, defaults to now
