<?php

/**
 * Tweet This is a plugin for WordPress 2.3 - 2.8.4. Also: WordPress MU.
 * Copyright 2008-2009  Richard X. Thripp  (email : richardxthripp@thripp.com)
 * Freely released under Version 2 of the GNU General Public License as
 * published by the Free Software Foundation, or, at your option, any later
 * version.
 */


/**
 * This file is part of Tweet This v1.6.1, build 025, 2009-09-27.
 * http://richardxthripp.thripp.com/tweet-this
 */


/**
 * This file is for special options not needed by 99% of Tweet This users.
 * Rename this file from tt-config-sample.php to tt-config.php to use it.
 * If you are on WPMU, this file should be in /wp-content/plugins/tweet-this/.
 * Options specified here apply for all blogs on your WPMU installation.
 */


/**
 * Sample usage of the following three definitions: if you have activated
 * multiple social networking services and want to put the links in an
 * unordered list, set TT_PREFIX to "<ul><li>", TT_SUFFIX to "</li></ul>",
 * and TT_SEPARATOR to "</li><li>". Note that if you use this configuration or
 * several others, the links will not display correctly unless Twitter is one
 * of the active social networking services.
 */


/**
 * The text that comes before the Tweet This link(s). Alignment is left, right,
 * or center, depending on what the user has specified in the options.
 */
define('TT_PREFIX', '<p align="' . tt_option('tt_alignment') . '">');


/**
 * The text that comes after the Tweet This link(s).
 */
define('TT_SUFFIX', '</p>');


/**
 * The text that separates the links of different social networking services.
 */
define('TT_SEPARATOR', ' ');


/**
 * True / false. Ignores the options in database and uses whatever is
 * specified as TT_SPECIAL_OPTIONS below.
 */
define('TT_OVERRIDE_OPTIONS', false);


/**
 * True / false. Hides the option menu completely; recommended if
 * TT_OVERRIDE_OPTIONS is true. Otherwise, you can still make changes in the
 * options menu but they will be ignored.
 */
define('TT_HIDE_MENU', false);


/**
 * The options to be overriden. TT_OVERRIDE_OPTIONS must be true for these
 * to be used. To use this feature, go to the options menu and change
 * everything as needed, click "Save Options," click "Import / Export Options,"
 * and copy the text from the export text area. Then paste it below,
 * overwriting everything between the quote marks between the blank lines.
 * Included below are the default options.
 */
define('TT_SPECIAL_OPTIONS',

'a:62:{s:14:"tt_url_service";s:6:"th8.us";s:16:"tt_adjix_api_key";s:0:"";s:8:"tt_ad_vu";s:4:"true";s:21:"tt_custom_url_service";s:47:"http://tinyurl.com/api-create.php?url=[LONGURL]";s:15:"tt_auto_display";s:4:"true";s:13:"tt_tweet_text";s:13:"[TITLE] [URL]";s:12:"tt_link_text";s:15:"Tweet This Post";s:13:"tt_title_text";s:15:"Post to Twitter";s:15:"tt_twitter_icon";s:14:"tt-twitter.png";s:15:"tt_textbox_size";s:2:"60";s:18:"tt_auto_tweet_text";s:28:"New blog post: [TITLE] [URL]";s:12:"tt_alignment";s:4:"left";s:16:"tt_img_css_class";s:7:"nothumb";s:17:"tt_link_css_class";s:2:"tt";s:6:"tt_css";s:26:"img.[IMG_CLASS]{border:0;}";s:13:"tt_plurk_text";s:13:"[TITLE] [URL]";s:18:"tt_plurk_link_text";s:15:"Plurk This Post";s:19:"tt_plurk_title_text";s:13:"Post to Plurk";s:13:"tt_plurk_icon";s:12:"tt-plurk.png";s:17:"tt_buzz_link_text";s:14:"Buzz This Post";s:18:"tt_buzz_title_text";s:18:"Post to Yahoo Buzz";s:12:"tt_buzz_icon";s:11:"tt-buzz.png";s:22:"tt_delicious_link_text";s:9:"Delicious";s:23:"tt_delicious_title_text";s:17:"Post to Delicious";s:17:"tt_delicious_icon";s:16:"tt-delicious.png";s:17:"tt_digg_link_text";s:14:"Digg This Post";s:18:"tt_digg_title_text";s:12:"Post to Digg";s:12:"tt_digg_icon";s:11:"tt-digg.png";s:21:"tt_facebook_link_text";s:8:"Facebook";s:22:"tt_facebook_title_text";s:16:"Post to Facebook";s:16:"tt_facebook_icon";s:15:"tt-facebook.png";s:20:"tt_myspace_link_text";s:7:"MySpace";s:21:"tt_myspace_title_text";s:15:"Post to MySpace";s:15:"tt_myspace_icon";s:14:"tt-myspace.png";s:17:"tt_ping_link_text";s:14:"Ping This Post";s:18:"tt_ping_title_text";s:15:"Post to Ping.fm";s:12:"tt_ping_icon";s:11:"tt-ping.png";s:19:"tt_reddit_link_text";s:6:"Reddit";s:20:"tt_reddit_title_text";s:14:"Post to Reddit";s:14:"tt_reddit_icon";s:13:"tt-reddit.png";s:15:"tt_su_link_text";s:17:"Stumble This Post";s:16:"tt_su_title_text";s:19:"Post to StumbleUpon";s:10:"tt_su_icon";s:9:"tt-su.png";s:5:"tt_30";s:5:"false";s:18:"tt_limit_to_single";s:5:"false";s:17:"tt_limit_to_posts";s:5:"false";s:10:"tt_url_www";s:5:"false";s:8:"tt_plurk";s:5:"false";s:7:"tt_buzz";s:5:"false";s:12:"tt_delicious";s:5:"false";s:7:"tt_digg";s:5:"false";s:11:"tt_facebook";s:5:"false";s:10:"tt_myspace";s:5:"false";s:7:"tt_ping";s:5:"false";s:9:"tt_reddit";s:5:"false";s:5:"tt_su";s:5:"false";s:9:"tt_footer";s:5:"false";s:6:"tt_ads";s:5:"false";s:13:"tt_new_window";s:5:"false";s:11:"tt_nofollow";s:5:"false";s:13:"tt_auto_tweet";s:5:"false";s:19:"tt_twitter_username";s:0:"";}'

); // ***** END SPECIAL OPTIONS ***** //


?>