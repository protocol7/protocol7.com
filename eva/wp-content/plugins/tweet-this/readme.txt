=== Tweet This ===
Contributors: richardxthripp
Donate link: http://richardxthripp.thripp.com/donate
Tags: twitter, tweets, url shortening, urls, links, social, connections, networking, sharing, bookmarking, integration, automatic, tweeting, plurk, yahoo buzz, delicious, digg, facebook, myspace, ping.fm, reddit, stumbleupon
Requires at least: 2.3
Tested up to: 2.8.4
Stable tag: 1.6.1

Adds a "Tweet This Post" link to every post and page. Shortens URLs.
Can automatically tweet new and scheduled blog posts. Highly customizable.

== Description ==

Adds a "Tweet This Post" link to every post and page. Shortens URLs.
Can automatically tweet new and scheduled blog posts. Customizable
(check screenshots). Also included: Plurk, Yahoo Buzz, Delicious,
Digg, Facebook, MySpace, Ping.fm, Reddit, and StumbleUpon support.

`Version 1.6.1 is a bugfix release:
* Bugfix: Automatic tweeting now works on scheduled posts! In 1.6,
  it would only work on posts set to "publish immediately."
* Bugfix: Double quotes work in "Send to Twitter" box on Write Post screen.
* Bugfix: Single and double quotes now work in Twitter "Editable text box."`

Normally, posting a link to Twitter takes up a lot of space. Though they
shorten URLs with bit.ly, it doesn't happen till after you post your tweet,
so the length of the original URL takes away from your 140 characters.

While your readers might go to bit.ly, copy and paste the blog post's
permalink, shorten the URL, copy that new URL, go to Twitter, and paste it
into the box, this plugin merges all that into one step. 

This plugin fetches a shortened URL from a URL shortening service of your
choice for each of your blog posts' permalinks server-side, then displays a
link to Twitter for each post, with an optional icon (twelve choices).
This is done automatically for each post as needed, but the each shortened URL
is cached (as a custom field in the postmeta table) to keep load times fast.
The cached records are updated or deleted as needed when you edit a post's
permalink, delete a post, change your site's permalink structure,
or change URL services.

You can pick Adjix.com, bit.ly, is.gd, Metamark.net, Snurl.com, Th8.us,
TinyURL.com, Tweetburner.com, local URLs like http://yourblog.com/?p=1234,
or a custom URL service.

This plugin can also tweet new blog posts automatically, if you provide
your Twitter credentials in the options. Then a "Send to Twitter" checkbox
appears when writing a new post, along with a text box so you can change the
tweet text for that specific blog post. As of 1.6.1, auto-tweeting works for
scheduled posts and the tweet will be delayed till the post is published.

Copyright 2008-2009  Richard X. Thripp  ( email : richardxthripp@thripp.com )
Freely released under Version 2 of the GNU General Public License as published
by the Free Software Foundation, or, at your option, any later version.

== Installation ==

1. Upload the `tweet-this` folder to `/wp-content/plugins/`.
2. If you're using WordPress MU and want this plugin active for all blogs,
	move `tweet-this.php` to `/wp-content/mu-plugins/` at this point.
3. Else, activate the plugin through the 'Plugins' menu in WordPress.
4. Tweet This icons should automatically appear on every post and page!
	Go to Settings > Tweet This to change stuff.
5. Optionally, delete readme.txt and the screenshots folder to save space.

NOTICE: If you are upgrading from 1.5.3 or earlier to 1.6.X, and you used
qr.cx, ri.ms, hex.io, lin.cr, idek.net, urlb.at, or h3o.de as your URL
shortening service, you will need to take special action. You can upgrade
without deactivating (just FTP the files or use automatic upgrade),
but after that:

`1. Go to Settings > Tweet This.
2. Choose "Custom" in the URL services list.
3. Open this URL in a new tab:
   http://richardxthripp.thripp.com/tweet-this-custom-url-services
4. Copy the text string for your URL service from that page.
5. Paste the string under "URL of API" in Tweet This.
6. Click "Save Options."`

== Changelog ==

`1.6.1: 2009-09-27: Overdue bugfix release.
* Bugfix: Automatic tweeting now works on scheduled posts! In 1.6,
  it would only work on posts set to "publish immediately."
* Bugfix: Double quotes work in "Send to Twitter" box on Write Post screen.
* Bugfix: Single and double quotes now work in Twitter "Editable text box."

1.6: 2009-08-24: Version 1.6 makes these changes:
* Feature: Automatic tweeting of new posts to Twitter.
* Feature: Can specify custom URL shortening service.
* Feature: Clickable radio buttons in options menu.
* Feature: Better documentation and screenshots.
* Bugfix: Since 1.2: The cached URL for a post would be deleted
  when a comment was added. Now it remains.
* Bugfix: Since 1.2: Tweets would get cut off around 125 characters
  if titles were too long. Now they can go all the way to 140.
* Bugfix: Since 1.4: When link text for Twitter was not [BLANK] but
  link text for another social network was [BLANK],
  "[BLANK]" would be displayed. Now no text is shown.
* Bugfix: Since 1.5: Pressing enter in a text field in the options
  form would trigger "Import Options" button. Now it triggers
  "Save Options" as it should, because the Import / Export
  section has been moved down.
* Bugfix: Since 1.5.3: When Reddit icon was changed but link text
  was left as "Reddit", it would not get changed to "[BLANK]".
* Removal: Qr.cx, ri.ms, hex.io, lin.cr, idek.net, urlb.at, and
  h3o.de have been removed from the URL shortening services.
* Removal: The buggy "Tweets about this post" RSS feature is gone
  (previously only accessible by tt-config.php option).
* Removal: WordPress 1.5 - 2.2.X no longer supported.`

Older versions: http://richardxthripp.thripp.com/tweet-this-version-history

== Frequently Asked Questions ==

= How do I make Tweet This show on posts, but NOT on pages? =

Go to Settings > Tweet This, click "Advanced Options,"
check "Hide Tweet This on pages," and click "Save Options."

= How does automatic tweeting work? =

It works exactly the same as Alex King's Twitter Tools. You enter your Twitter
username and password in the options, they are stored in your WP database, and
when you make a new post, your Twitter status is updated through the Twitter
API using the Snoopy class and the PEAR JSON class. However, Tweet This has
more options. You can change the format of the tweet text (not just the
prefix), and you can change a tweet's post on the write post screen before
publishing. You can also tweet old posts if they have not been tweeted before.

= Is Tweet This compatible with Twitter Tools? =

Yes! You can have them both active at once, and you can use either (or both!)
for the automated tweeting of new blog posts. Twitter Tools has these features
which Tweet This lacks: tweet archival, digest posts, and a sidebar widget.
The focus of Tweet This is to allow your readers to share your material, which
Twitter Tools provides no features for. Together they are a complete solution.

= How does the cache work? =

Cached short URLs are saved to the postmeta table when a visitor views posts.
For any future pageloads, those URLs are loaded, instead of pinging the Th8.us
API (or bit.ly, TinyURL, etc.). As long as the post's permalink doesn't change,
the short URL from the third-party service doesn't change.

The cache is invalidated by setting the existing short URLs in the postmeta
table to "getnew" as needed. By reusing the old fields instead of replacing
them, I don't bump up the `meta_id` counter needlessly. When the next person
visits that post, the `get_tweet_this_short_url` function in Tweet This
sees this and gets a new short URL.

What triggers a cached URL as invalid? When you save a post (including editing
and publishing), the cache is invalidated in case you changed the permalink.
Secondly, when you change URL services under Settings > Tweet This or change
permalink structures under Options > Permalinks, all the cached URLs are set
to "getnew". Finally, if you change "Use 'www.' instead of 'http://' in
shortened URLs" or "Don't shorten URLs under 30 characters," or import
new settings, the cache is invalidated. If you move your blog to a different
directory or domain name, just change URL services and then change back
to trigger a refresh of the cache.

When you deactivate the plugin, all the cached URLs are deleted.

= How does importing and exporting options work? =

In the options menu, there is a section titled "Import / Export Options." This
is as simple as can be: the export is a raw dump of the `tweet_this_settings`
row from the `wp_options` table, and to import, you just paste that dump in the
import text area. Click "Import Options," and your current options will be
replaced. Your Twitter password is NOT included in the exported options.

= How does setting a custom short URL service work? =

You enter the API's path with [LONGURL] as the long URL. For TinyURL,
for example, you would enter "http://tinyurl.com/api-create.php?url=[LONGURL]".
Then Tweet This uses that service for all its short URLs. The API must accept
HTTP GET requests (not POST), and it must output a plain-text short URL
(no HTML, XML, or arrays) with the http prefix.

= How does the editable Tweet This text box work? =

One of the options for the Twitter icon in the Tweet This Options is
"Editable text box." This gives your readers a text box with character counter
so they can change the tweet on your site before going to Twitter. When you
click the submit button, an interstitial is loaded which parses and sends the
new tweet text, forwarding the reader to Twitter. Then it is the same as the
regular options: the reader can edit the tweet further on Twitter, or submit.

= How does the tt-config.php file work? =

Tweet This ships with the file named as `tt-config-sample.php`. This way,
if you rename it to tt-config.php to use it, you can still upload future
versions of Tweet This right over the old directory, because your customized
tt-config.php file will not be overwritten. Once you rename it, there are
several options you can set in it that are too advanced or cannot be included
in the regular options menu.

= Can I use the Tweet This functions in my theme? =

Yes. Within the loop, these functions are available:

`tweet_this($service, $tweet_text, $link_text, $title_text, $icon_file,
$a_class, $img_class, $img_alt) : Echoes a Tweet This link. This is only
useful if you disable automatic insertion in the settings.  You can leave
the arguments blank like '' to use your settings from the options page.
These values are permitted for the $services argument: 'twitter', 'plurk',
'buzz', 'delicious', 'digg', 'facebook', 'myspace', 'ping', 'reddit', 'su'.
The $icon_file argument is for the filename of an image from the
/tweet-this/icons/ folder. Example: tweet_this('twitter',
'@richardxthripp [TITLE] [URL]', '[BLANK]', 'Share on Twitter [[URL]]',
'de/tt-twitter-big3-de.png', 'tweet-this', 'tt-image', 'Post to Twitter').
$icon_file can be set to "noicon" for a text-only link.

tweet_this_url($tweet_text, $service) : Echoes the Tweet This URL, which is
like http://twitter.com/home/?status=Tweet+This+http://37258.th8.us by
default. Optional tweet_text argument overrides "Tweet Text" from the
options menu. $service can be 'twitter' or 'plurk', or omitted for Twitter.
Sample usage: tweet_this_url('@richardxthripp [TITLE] [URL]', 'twitter').

tweet_this_short_url() : Just echoes the short URL for the post
(Th8.us, TinyURL, etc.), cached if possible.

tweet_this_trim_title() : URL-encodes get_the_title(), truncates it at the
nearest word if it's overly long, and echoes.`

You can prefix these functions with `get_` to return the data without echoing,
for further manipulation by PHP.

Also available:

`tt_option($key) : like get_option(), but specifically for Tweet This
settings. Useful for retrieving settings from the database.
Example: tt_option('tt_url_service').`

= Can I disable Tweet This on a specific post or page? =

Yes: add a custom field titled `tweet_this_hide` with the value of "true".

= Why doesn't Tweet This show when I preview a draft post? =

Because I'd have to fetch a short URL if it did, and the permalink for the
post isn't set yet. It would be something like /?p=1, which would just
waste an entry in TinyURL or another service's database.

= Which short URL service do you recommend? =

Th8.us because I created it. It is now simple and reliable, and it uses cool
virtual subdomains. is.gd and bit.ly are good and short, but they both produce
case-sensitive URLs. TinyURL is the de facto and probably most reliable choice.

= Can I just use the Tweet This functions without it adding icons to my blog? =

Sure! Activate the plugin, go to Settings > Tweet This,
uncheck "Insert Tweet This," and click "Save Options."

= If I change URL services, will the old URLs continue to work? =

Yes. The short URLs are on third-party servers (Th8.us, bit.ly, TinyURL, etc.),
and they should never delete them.

= Why can't Tweetburner URLs have the "www." prefix? =

Because Tweetburner does not work with the www subdomain;
use it and your URLs just redirect to the Tweetburner home page.

== Screenshots ==

1. Tweet This options page.
2. Advanced options.
3. Extended social networking services.
4. Import / Export options.
5. A post with Tweet This links.
6. Arriving at Twitter after clicking link.
7. Auto-tweet options on Write Post screen.