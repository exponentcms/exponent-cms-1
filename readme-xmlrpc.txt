ExponentCMS v0.98 xmlrpc Weblog and News access implementation
Uses xmlrpc php classes by Edd Dumbill

There are NO guarantees this may not open up security vulnerabilites on your site!  Please use with caution!

After installation (into the root ExponentCMS directory), you should be able to edit your weblog and/or news module using a desktop blog editor like MS Word, etc...be advised many are quite finicky.

To use, run your blog editor using several parameters, e.g.
Site/APi Type - Custom Metaweblog
Account Username - admin account on the site
Account Password - site password
Blog Post API URL - http://www.mysite.org/xmlrpc.php (or) http://www.mysite.org/xmlrpc-news.php (for news module)
Picture Options - Use Blog Provider

If all goes well (and sometimes it doesn't), you'll be given a list of all the weblogs on the site.  Most blog editors allow you to choose multiple blogs from the same address.

With the news items, the "draft" flag equates to "not featured" and "publish" equates to "featured."  Otherwise it's similar except to use a different API URL (primarily to reduce confusion)


Currently (v 3) this script:
- Allows you to create new and edit existing blog posts including those with graphics.  You can also set draft or publish status
- (NEW) Allows access to categories (tags), since the weblog module doesn't have categories (tags?)
- (FIXED) On long posts it can lose some of the text after a break/newline, apparently this is a common problem?
- (FIXED?) Sometimes it works and sometime it doesn't.  Not sure why?
