<?php
/*
Plugin Name: Tweet This
Plugin URI: http://richardxthripp.thripp.com/tweet-this
Description: Adds a "Tweet This Post" link to every post and page. Shortens URLs. Can automatically tweet new and scheduled blog posts. Highly customizable.
Author: Richard X. Thripp
Version: 1.6.1
Author URI: http://richardxthripp.thripp.com/
Text Domain: tweet-this
*/


/**
 * Tweet This is a plugin for WordPress 2.3 - 2.8.4. Also: WordPress MU.
 * Copyright 2008-2009  Richard X. Thripp  (email : richardxthripp@thripp.com)
 * Freely released under Version 2 of the GNU General Public License as
 * published by the Free Software Foundation, or, at your option, any later
 * version.
 */


/**
 * This is Tweet This v1.6.1, build 025, 2009-09-27.
 */


/**
 * Required before WP 2.6.
 */
if(!defined('WP_CONTENT_URL'))
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if(!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if(!defined('WP_PLUGIN_URL'))
	define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
if(!defined('WP_PLUGIN_DIR'))
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');


/**
 * Definitions.
 */
define('TT_DIR', 'tweet-this');
define('TT_ABSPATH', WP_PLUGIN_DIR . '/' . TT_DIR . '/');
define('TT_URLPATH', WP_PLUGIN_URL . '/' . TT_DIR . '/');
define('TT_VERSION', '1.6.1');
define('TT_BUILD', '025');
define('TT_CONFIG', TT_ABSPATH . 'tt-config.php');
define('TT_OPTIONS', TT_ABSPATH . 'lib/tt-options.php');
define('TT_JSON', TT_ABSPATH . 'lib/json.php');
define('TT_ADJIX_LEN', 21);
define('TT_BITLY_LEN', 19);
define('TT_CUSTOM_LEN', 25);
define('TT_ISGD_LEN', 18);
define('TT_LOCAL_LEN', strlen(get_bloginfo('url')) + 8);
define('TT_METAMARK_LEN', 20);
define('TT_SNURL_LEN', 22);
define('TT_TH8US_LEN', 19);
define('TT_TINYURL_LEN', 25);
define('TT_TWEETBURNER_LEN', 22);
if(file_exists(TT_CONFIG))
	require_once(TT_CONFIG);
if(file_exists(TT_JSON) && !class_exists('Services_JSON'))
	require_once(TT_JSON);


/**
 * Do not edit these here, but rather, in the tt-config.php file.
 */
if(!defined('TT_PREFIX'))
	define('TT_PREFIX', '<p align="' . tt_option('tt_alignment') . '">');
if(!defined('TT_SUFFIX'))
	define('TT_SUFFIX', '</p>');
if(!defined('TT_SEPARATOR'))
	define('TT_SEPARATOR', ' ');
if(!defined('TT_OVERRIDE_OPTIONS'))
	define('TT_OVERRIDE_OPTIONS', true);
if(!defined('TT_HIDE_MENU'))
	define('TT_HIDE_MENU', false);
if(!defined('TT_SPECIAL_OPTIONS'))
	define('TT_SPECIAL_OPTIONS', '');


/**
 * Easy access to Tweet This options stored in an array in wp_options table.
 */
function tt_option($key) {
	if(defined('TT_OVERRIDE_OPTIONS') && defined('TT_SPECIAL_OPTIONS') &&
		TT_OVERRIDE_OPTIONS == true && TT_SPECIAL_OPTIONS != '' &&
		unserialize(TT_SPECIAL_OPTIONS) != false)
			$options = unserialize(TT_SPECIAL_OPTIONS);
	else $options = get_option('tweet_this_settings');
	if($key == 'nw') {
		if($options['tt_new_window'] == 'true')
			return 'target="_blank" ';
		else return '';
	}
	elseif($key == 'nf') {
		if($options['tt_nofollow'] == 'true')
			return 'rel="nofollow" ';
		else return '';
	}
	elseif($key == 'tt_alignment') {
		if($options['tt_alignment'] == '' ||
			($options['tt_alignment'] != 'left' &&
			$options['tt_alignment'] != 'right' &&
			$options['tt_alignment'] != 'center'))
				return 'left';
		else return $options['tt_alignment'];
	}
	elseif($key == 'tt_css' && $options['tt_css'] ==
		'a.[LINK_CLASS]{text-decoration:none;border:0;}')
		return 'img.[IMG_CLASS]{border:0;}';
	else return stripslashes(htmlentities($options[$key]));
}


/**
 * Connects to external URL via curl and returns file.
 */
function tt_read_file($url) {
	if(ini_get('allow_url_fopen') == 1 ||
		strtolower(ini_get('allow_url_fopen')) == 'on') {
		$file = @file_get_contents($url);
		if($file == false) {
			$handle = @fopen($url, 'r');
			$file = @fread($handle, 4096);
			@fclose($handle);
			}
	} else {
		if(function_exists('curl_init')) {
			$ch = curl_init($url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
			$file = curl_exec($ch);
			curl_close($ch);
			}
		}
	if($file != false && $file != '')
		return $file;
}


/**
 * Retrieves the shortened URL for a post, taking into account the user's
 * settings. No arguments. Works on the current post.
 * Must be used within the loop.
 */
function get_tweet_this_short_url($longurl = '', $post_id = '') {
	global $id;
	if($post_id == '')
		$post_id = $id;
	if($longurl != '')
		$purl = $longurl;
	else $purl = get_permalink();
	$cached_url = get_post_meta($post_id, 'tweet_this_url', true);
	if($cached_url && $cached_url != 'getnew')
		return $cached_url;
	else {
		$u = tt_option('tt_url_service');
		if(ini_get('allow_url_fopen') == 1 ||
			strtolower(ini_get('allow_url_fopen')) == 'on' ||
			function_exists('curl_init'))
				$u = $u;
			else $u = 'local';
		switch($u) {
		case 'adjix': {
			if(tt_option('tt_adjix_api_key') == '')
				$adid = '';
			else $adid = '&partnerID=' .
				tt_option('tt_adjix_api_key');
			if(tt_option('tt_ad_vu') != 'true')
				$s = '';
			else $s = '&ultraShort=y';
			$url = tt_read_file('http://api.adjix.com/shrinkLink' .
				'?url=' . $purl . $adid . $s);
			}
			break;
		case 'bit.ly':
			$url = tt_read_file('http://bit.ly/api?url=' . $purl);
			break;
		case 'is.gd':
			$url = tt_read_file('http://is.gd/api.php?longurl=' .
				$purl);
			break;
		case 'metamark':
			$url = tt_read_file('http://metamark.net/api/rest/' .
				'simple?long_url=' . $purl);
			break;
		case 'snurl':
			$url = tt_read_file('http://snurl.com/site/snip?r=' .
				'simple&link=' . $purl);
		case 'th8.us':
		default:
			$url = tt_read_file('http://th8.us/api.php?url=' .
				$purl . '&client=Tweet+This+v' . TT_VERSION .
				'&format=simple');
			break;
		case 'tinyurl':
			$url = tt_read_file('http://tinyurl.com/' .
				'api-create.php?url=' . $purl);
			break;
	// Tweetburner only accepts HTTP POST requests.
		case 'tweetburner': {
			if(function_exists('curl_init')) {
			$ch = curl_init('http://tweetburner.com/links');
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,'link[url]=$purl');
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
			$url = curl_exec($ch);
			curl_close($ch);
			}
			else $u = 'local';
			}
			break;
		case 'local':
			$url = get_bloginfo('url') . '/?p=' . $post_id;
			break;
		case 'custom':
			$url = tt_read_file(str_replace('[LONGURL]',
			urlencode($purl), tt_option('tt_custom_url_service')));
			break;
		}
	// Tweetburner URLs cannot be accessed with the www subdomain.
	// It's not safe to replace http:// with www. outright because if
	// the blog already has the www subdomain you will get www.www.
		if(tt_option('tt_url_www') == 'true' && $u != 'tweetburner') {
			$url =	str_replace('http://', 'www.',
				str_replace('http://www.', 'www.', $url));
			}
	// If short URL > 30 characters, is error, or is malformed, discard.
		if($u != 'local' && (strlen($url) > 30 ||
			strtolower($url) == 'error' || $url == 'www.' ||
			$url == 'http://' || $url == ''))
				$url = get_bloginfo('url') . '/?p=' . $post_id;
		if(tt_option('tt_30') == 'true' && strlen($purl) < 30) {
			$url = $purl;
	// Duplicated code in case the user has checked "Don't shorten URLs
	// under 30 characters" and is using Tweetburner. Tweetburner URLs
	// cannot be accessed with the www prefix but local URLs can.
			if(tt_option('tt_url_www') == 'true')
				$url =	str_replace('http://', 'www.',
					str_replace('http://www.', 'www.',
					$url));
			}
		if($cached_url == 'getnew')
			update_post_meta($post_id, 'tweet_this_url', $url,
			'getnew');
		else add_post_meta($post_id, 'tweet_this_url', $url, true);
		return $url;
	}
}


/**
 * Echoes the above function for convenience.
 */
function tweet_this_short_url() {
	echo	get_tweet_this_short_url();
}


/**
 * Parses the post title. Truncates with an ellipse if the title is too long.
 */
function get_tweet_this_trim_title($title = '', $tweet_text = '') {
	if($title == '')
		$title = get_the_title();
	if($tweet_text == '')
		$tweet_text = tt_option('tt_tweet_text');
	$title = @html_entity_decode($title, ENT_COMPAT, 'UTF-8');
	$tt_len = max((strlen($tweet_text) - 12), 1);
	$url_len = tt_url_service_len();
	if(strlen($title) > (140 - $tt_len - $url_len))
		$title = substr($title, 0, (137 - $tt_len - $url_len)) . '...';
	return urlencode($title);
}


/**
 * Echoes the above function for convenience.
 */
function tweet_this_trim_title() {
	echo	get_tweet_this_trim_title();
}


/**
 * Returns the text for the Tweet This link, which can be specified in
 * Settings > Tweet This. Default is "Tweet This Post."
 */
function tweet_this_link_text($link_text = '', $service = 'twitter') {
	if($link_text == '') {
		if($service == 'twitter' || $service == '') {
			if(tt_option('tt_link_text') == '')
				$link_text = __('Tweet This Post',
					'tweet-this');
			else $link_text = tt_option('tt_link_text');
		}
		elseif($service == 'plurk') {
			if(tt_option('tt_plurk_link_text') == '')
				$link_text = __('Plurk This Post',
					'tweet-this');
			else $link_text = tt_option('tt_plurk_link_text');
		}
		elseif($service == 'buzz') {
			if(tt_option('tt_buzz_link_text') == '')
				$link_text = __('Buzz This Post',
					'tweet-this');
			else $link_text = tt_option('tt_buzz_link_text');
		}
		elseif($service == 'delicious') {
			if(tt_option('tt_delicious_link_text') == '')
				$link_text = __('Delicious', 'tweet-this');
			else $link_text = tt_option('tt_delicious_link_text');
		}
		elseif($service == 'digg') {
			if(tt_option('tt_digg_link_text') == '')
				$link_text = __('Digg This Post',
					'tweet-this');
			else $link_text = tt_option('tt_digg_link_text');
		}
		elseif($service == 'facebook') {
			if(tt_option('tt_facebook_link_text') == '')
				$link_text = __('Facebook', 'tweet-this');
			else $link_text = tt_option('tt_facebook_link_text');
		}
		elseif($service == 'myspace') {
			if(tt_option('tt_myspace_link_text') == '')
				$link_text = __('MySpace', 'tweet-this');
			else $link_text = tt_option('tt_myspace_link_text');
		}
		elseif($service == 'ping') {
			if(tt_option('tt_ping_link_text') == '')
				$link_text = __('Ping This Post',
					'tweet-this');
			else $link_text = tt_option('tt_ping_link_text');
		}
		elseif($service == 'reddit') {
			if(tt_option('tt_reddit_link_text') == '')
				$link_text = __('Reddit', 'tweet-this');
			else $link_text = tt_option('tt_reddit_link_text');
		}
		elseif($service == 'su') {
			if(tt_option('tt_su_link_text') == '')
				$link_text = __('Stumble This Post',
					'tweet-this');
			else $link_text = tt_option('tt_su_link_text');
		}
	}
	$link_s = array('[TITLE]', '[URL]');
	$link_r = array(get_the_title(), get_tweet_this_short_url());
	$link_text = str_replace($link_s, $link_r, $link_text);
	return $link_text;
}


/**
 * Returns the pop-up title attribute for the Tweet This link, which can be
 * specified in Settings > Tweet This. Default is "Post to Twitter."
 */
function tweet_this_title_text($title_text = '', $service = 'twitter') {
	if($title_text == '') {
		if($service == 'twitter' || $service == '') {
			if(tt_option('tt_title_text') == '')
				$title_text = __('Post to Twitter',
					'tweet-this');
			else $title_text = tt_option('tt_title_text');
		}
		elseif($service == 'plurk') {
			if(tt_option('tt_plurk_title_text') == '')
				$title_text = __('Post to Plurk',
					'tweet-this');
			else $title_text = tt_option('tt_plurk_title_text');
		}
		elseif($service == 'buzz') {
			if(tt_option('tt_buzz_title_text') == '')
				$title_text = __('Post to Yahoo Buzz',
					'tweet-this');
			else $title_text = tt_option('tt_buzz_title_text');
		}
		elseif($service == 'delicious') {
			if(tt_option('tt_delicious_title_text') == '')
				$title_text = __('Post to Delicious',
					'tweet-this');
			else $title_text =
				tt_option('tt_delicious_title_text');
		}
		elseif($service == 'digg') {
			if(tt_option('tt_digg_title_text') == '')
				$title_text = __('Post to Digg',
					'tweet-this');
			else $title_text = tt_option('tt_digg_title_text');
		}
		elseif($service == 'facebook') {
			if(tt_option('tt_facebook_title_text') == '')
				$title_text = __('Post to Facebook',
					'tweet-this');
			else $title_text = tt_option('tt_facebook_title_text');
		}
		elseif($service == 'myspace') {
			if(tt_option('tt_myspace_title_text') == '')
				$title_text = __('Post to MySpace',
					'tweet-this');
			else $title_text = tt_option('tt_myspace_title_text');
		}
		elseif($service == 'ping') {
			if(tt_option('tt_ping_title_text') == '')
				$title_text = __('Post to Ping.fm',
					'tweet-this');
			else $title_text = tt_option('tt_ping_title_text');
		}
		elseif($service == 'reddit') {
			if(tt_option('tt_reddit_title_text') == '')
				$title_text = __('Post to Reddit',
					'tweet-this');
			else $title_text = tt_option('tt_reddit_title_text');
		}
		elseif($service == 'su') {
			if(tt_option('tt_su_title_text') == '')
				$title_text = __('Post to StumbleUpon',
					'tweet-this');
			else $title_text = tt_option('tt_su_title_text');
		}
	}
	$title_s = array('[TITLE]', '[URL]');
	$title_r = array(get_the_title(), get_tweet_this_short_url());
	$title_text = str_replace($title_s, $title_r, $title_text);
	return $title_text;
}


/**
 * Determines whether the Tweet This link can be displayed on the current post.
 * If true, this function returns what you put in. If false, it returns blank.
 * This function is complicated because the user can specify that links be
 * hidden on posts and/or only shown on single posts, and this can be
 * over-ridden for individual posts and pages.
 */
function tt_display_limits($item) {
	global $preview;
	global $post;
	if(!$preview && $post->post_status != 'draft') {
		if(tt_option('tt_limit_to_posts') == 'true') {
			if(tt_option('tt_limit_to_single') == 'true') {
				if(is_single())
					return $item;
			} else {
				if(!is_page())
					return $item;
				}
			}
		if(tt_option('tt_limit_to_single') == 'true') {
			if(tt_option('tt_limit_to_posts') == 'true') {
				if(is_single())
					return $item;
			} else {
				if(is_singular())
					return $item;
			}
		}
	if(tt_option('tt_limit_to_posts') != 'true' &&
		tt_option('tt_limit_to_single') != 'true')
		return $item;
	}
}


/**
 * Generates the Twitter URL; the direct URL to tweet a post to Twitter,
 * taking into account the user's settings.
 */
function get_tweet_this_url($tweet_text = '', $service = '', $longurl = '',
	$title = '', $post_id = '') {
	if($tweet_text == '') {
		if($service == 'twitter' || $service == '') {
			if(tt_option('tt_tweet_text') == '')
				$tweet_text = '[TITLE] [URL]';
			else $tweet_text = tt_option('tt_tweet_text');
			$path = 'http://twitter.com/home/?status=';
		}
		elseif($service == 'plurk') {
			if(tt_option('tt_plurk_text') == '')
				$tweet_text = '[TITLE] [URL]';
			else $tweet_text = tt_option('tt_plurk_text');
			$path = 'http://plurk.com/?status=';
		}
	}
	$tweet_s = array('[TITLE]', '[URL]', ' ');
	$tweet_r = array(get_tweet_this_trim_title($title, $tweet_text),
		get_tweet_this_short_url($longurl, $post_id), '+');
	$tweet_text = str_replace($tweet_s, $tweet_r, $tweet_text);
	$item = $path . $tweet_text;
	return tt_display_limits($item);
}


/**
 * Echoes the above function for convenience.
 */
function tweet_this_url($tweet_text = '') {
	echo	get_tweet_this_url($tweet_text);
}


/**
 * This is the core function of the plugin. Generates the Tweet This links,
 * including images, text, a title attributes, and new window and nofollow
 * preferences. Relies on a myriad of other functions, and takes a slew of
 * arguments to be used for every social networking service Tweet This offers.
 * This function can also be manually placed into the theme with the
 * arguments customized to your liking, and "Insert Tweet This links"
 * disabled in the options (so they don't appear twice).
 */
function get_tweet_this($service = 'twitter', $tweet_text = '',
	$link_text = '', $title_text = '', $icon_file = '', $a_class = '',
	$img_class = '', $img_alt = '') {
	if($a_class == '') {
		if(tt_option('tt_link_css_class') == '')
			$a_class = 'tt';
		else $a_class = tt_option('tt_link_css_class');
		}
	if($img_class == '') {
		if(tt_option('tt_img_css_class') == '')
			$img_class = 'nothumb';
		else $img_class = tt_option('tt_img_css_class');
		}
	$url = get_tweet_this_url($tweet_text, $service);
	$title = tweet_this_title_text($title_text, $service);
	$link = tweet_this_link_text($link_text, $service);
	if($service == 'twitter' || $service == '') {
		if($img_alt == '')
			$img_alt = __('Post to Twitter', 'tweet-this');
		if($icon_file == '') {
			if(tt_option('tt_twitter_icon') == '')
				$icon_file = 'tt-twitter.png';
			else $icon_file = tt_option('tt_twitter_icon');
			}
		}
	if($service == 'plurk') {
		if($img_alt == '')
			$img_alt = __('Post to Plurk', 'tweet-this');
		if($icon_file == '') {
			if(tt_option('tt_plurk_icon') == '')
				$icon_file = 'tt-plurk.png';
			else $icon_file = tt_option('tt_plurk_icon');
			}
		}
	if($service == 'buzz') {
		if($img_alt == '')
			$img_alt = __('Post to Yahoo Buzz', 'tweet-this');
		if($icon_file == '') {
			if(tt_option('tt_buzz_icon') == '')
				$icon_file = 'tt-buzz.png';
			else $icon_file = tt_option('tt_buzz_icon');
			}
		$url = 'http://buzz.yahoo.com/submit?submitUrl=' .
			get_permalink() . '&amp;submitHeadline=' .
			get_tweet_this_trim_title();
		}
	if($service == 'delicious') {
		if($img_alt == '')
			$img_alt = __('Post to Delicious', 'tweet-this');
		if($icon_file == '') {
			if(tt_option('tt_delicious_icon') == '')
				$icon_file = 'tt-delicious.png';
			else $icon_file = tt_option('tt_delicious_icon');
			}
		$url = 'http://delicious.com/post?url=' . get_permalink() .
			'&amp;title=' . get_tweet_this_trim_title();
		}
	if($service == 'digg') {
		if($img_alt == '')
			$img_alt = __('Post to Digg', 'tweet-this');
		if($icon_file == '') {
			if(tt_option('tt_digg_icon') == '')
				$icon_file = 'tt-digg.png';
			else $icon_file = tt_option('tt_digg_icon');
			}
		$url = 'http://digg.com/submit?url=' . get_permalink() .
			'&amp;title=' . get_tweet_this_trim_title();
		}
	if($service == 'facebook') {
		if($img_alt == '')
			$img_alt = __('Post to Facebook', 'tweet-this');
		if($icon_file == '') {
			if(tt_option('tt_facebook_icon') == '')
				$icon_file = 'tt-facebook.png';
			else $icon_file = tt_option('tt_facebook_icon');
			}
		$url = 'http://www.facebook.com/share.php?u=' .
			get_permalink() . '&amp;t=' .
			get_tweet_this_trim_title();
		}
	if($service == 'myspace') {
		if($img_alt == '')
			$img_alt = __('Post to MySpace', 'tweet-this');
		if($icon_file == '') {
			if(tt_option('tt_myspace_icon') == '')
				$icon_file = 'tt-myspace.png';
			else $icon_file = tt_option('tt_myspace_icon');
			}
		$url = 'http://www.myspace.com/Modules/PostTo/Pages/' .
			'?l=3&amp;u=' . get_permalink() . '&amp;t=' .
			get_tweet_this_trim_title() . '&amp;c=%3Cp%3E' .
			'Powered+by+%3Ca+href%3D%22http%3A%2F%2F' .
			'richardxthripp.thripp.com%2Ftweet-this%22%3E' .
			'Tweet+This%3C%2Fa%3E%3C%2Fp%3E';
		}
	if($service == 'ping') {
		if($img_alt == '')
			$img_alt = __('Post to Ping.fm', 'tweet-this');
		if($icon_file == '') {
			if(tt_option('tt_ping_icon') == '')
				$icon_file = 'tt-ping.png';
			else $icon_file = tt_option('tt_ping_icon');
			}
		$url = 'http://ping.fm/ref/?method=microblog&amp;title=' .
			get_tweet_this_trim_title() . '&amp;link=' .
			get_permalink();
		}
	if($service == 'reddit') {
		if($img_alt == '')
			$img_alt = __('Post to Reddit', 'tweet-this');
		if($icon_file == '') {
			if(tt_option('tt_reddit_icon') == '')
				$icon_file = 'tt-reddit.png';
			else $icon_file = tt_option('tt_reddit_icon');
			}
		$url = 'http://reddit.com/submit?url=' . get_permalink() .
			'&amp;title=' . get_tweet_this_trim_title();
		}
	if($service == 'su') {
		if($img_alt == '')
			$img_alt = __('Post to StumbleUpon', 'tweet-this');
		if($icon_file == '') {
			if(tt_option('tt_su_icon') == '')
				$icon_file = 'tt-su.png';
			else $icon_file = tt_option('tt_su_icon');
			}
		$url = 'http://stumbleupon.com/submit?url=' . get_permalink() .
			'&amp;title=' . get_tweet_this_trim_title();
		}
	$icon = TT_URLPATH . 'icons/' . $icon_file;
	if($icon_file != 'noicon')
		$item = '<a ' . tt_option('nw') . tt_option('nf') .
			'class="' . $a_class . '" href="' . $url .
			'" title="' . $title . '"><img class="' .
			$img_class . '" src="' . $icon . '" alt="' .
			$img_alt . '" /></a>';
	if($link != '[BLANK]')
		$item .= ' <a ' . tt_option('nw') . tt_option('nf') .
			'class="' . $a_class . '" href="' . $url .
			'" title="' . $title . '">' . $link . '</a>';
	if(($service == 'twitter' || $service == '') &&
		($icon_file == 'textbox')) {
		$textbox = tt_option('tt_textbox_size');
		if($textbox == '')
			$textbox = '60';
		if($link == '[BLANK]')
			$link = __('Tweet This Post', 'tweet-this');
		$item =	'<form action="' . get_bloginfo('wpurl') .
		'/index.php" method="post" id="tt_twitter_box"><fieldset>
		<input type="text" id="tt_twitter_box_text" ' .
		'name="tt_twitter_box_text" class="tt_twitter_box" size="' .
		$textbox . '" value="' . urldecode(html_entity_decode((
		str_replace('http://twitter.com/home/?status=', '', $url)))) .
		'" onchange="ttCharCount();" onclick="ttCharCount();" ' .
		'onkeyup="ttCharCount();" maxlength="140" />
		<script type="text/javascript">
		//<![CDATA[
		function ttCharCount() {
			var count = document.getElementById' .
				'("tt_twitter_box_text").value.length;
			document.getElementById("tt_char_count")' .
				'.innerHTML = (140 - count) + "' .
				__(' characters remaining', 'tweet-this') . '";
		}
		setTimeout("ttCharCount();", 500);
		document.getElementById("tt_twitter_box").' .
			'setAttribute("autocomplete", "off");
		//]]></script><br /><input type="submit" ' .
		'id="tt_twitter_box_submit" name="tt_twitter_box_submit" ' .
		'value="' . $link . '" class="button-primary" ' .
		'style="margin-top:5px;margin-bottom:-5px;" /> 
		<span id="tt_char_count"></span></fieldset>
		<input type="hidden" name="ttaction" value="tt_twitter_box" />
		</form>';
	}
	return tt_display_limits($item);
}


/**
 * Echoes the above function for convenience.
 */
function tweet_this($service = 'twitter', $tweet_text = '', $link_text = '',
	$title_text = '', $icon_file = '', $a_class = '', $img_class = '',
	$img_alt = '') {
	echo	get_tweet_this($service, $tweet_text, $link_text, $title_text,
		$icon_file, $a_class, $img_class, $img_alt);
}


/**
 * Tweet This options on post edit screen.
 */
function tt_post_options() {
	global $post;
	$tt_auto = get_post_meta($post->ID, 'tt_auto_tweet', true);
	$tt_auto_text = get_post_meta($post->ID, 'tt_auto_tweet_text', true);
	echo	'<div class="postbox"><h3>' . __('Tweet This', 'tweet-this') .
		'</h3><div class="inside"><p>';
	if(tt_option('tt_twitter_username') == '' ||
		get_option('tweet_this_password') == '')
		_e('Please enter your Twitter username and password under ' .
		'Settings > Tweet This', 'tweet-this');
	elseif(get_post_meta($post->ID, 'tt_tweeted', true) == 'true')
		_e('This post has been tweeted. To allow a retweet, set the ' .
		'tt_auto_tweet and tt_tweeted custom fields to false, then ' .
		'save.', 'tweet-this');
	elseif($post->post_status == 'private')
		_e('Cannot tweet a private post.', 'tweet-this');
	else {
		echo	'<script type="text/javascript">
		//<![CDATA[
		function ttCharCount() {
			var count = document.getElementById' .
				'("tt_auto_tweet_text").value.length;
			var count_title = document.getElementById' .
				'("title").value.length;
			document.getElementById("tt_char_count")' .
				'.innerHTML = (140 - count - count_title - ' .
				tt_url_service_len() . ') + "' .
				__(' characters remaining', 'tweet-this') . '";
		}
		setTimeout("ttCharCount();", 500);
		document.getElementById("tt_twitter_box").' .
			'setAttribute("autocomplete", "off");
		ttCharCount();
		//]]></script>
		<label for="tt_auto_tweet" class="selectit"> 
			<input name="tt_auto_tweet" id="tt_auto_' .
			'tweet" type="checkbox"';
		if($tt_auto == 'true' || (tt_option('tt_auto_tweet') == 'true'
			&& $tt_auto != 'false' &&
			$post->post_status != 'publish'))
			echo	' checked="checked"';
		echo	' value="true" /> ' . __('Send to Twitter',
			'tweet-this') . '</label> <input type="text" ' .
			'name="tt_auto_tweet_text" ' .
			'id="tt_auto_tweet_text" size="75" value="';
		if($tt_auto_text != '')
			echo	htmlentities($tt_auto_text);
		elseif(tt_option('tt_auto_tweet_text') != '')
			echo	htmlentities(tt_option('tt_auto_tweet_text'));
		else echo	__('New blog post', 'tweet-this') .
				': [TITLE] [URL]';
		echo	'" onchange="ttCharCount();" ' .
			'onclick="ttCharCount();" onkeyup="ttCharCount();" ' .
			'maxlength="140" /> <span id="tt_char_count"></span>';
	}
	do_action('tt_post_options');
	echo	'</p></div></div>';
}


/**
 * Saves options on post edit screen.
 */
function tt_store_post_options($post_id, $post = false) {
	$post = get_post($post_id);
	if(!$post || $post->post_type == 'revision' ||
		!isset($_POST['tt_auto_tweet_text']))
			return;
	else {
		$tt_auto = $_POST['tt_auto_tweet'];
		$tt_auto_text = $_POST['tt_auto_tweet_text'];
		if($tt_auto != 'true')
			$tt_auto = 'false';
		if(!update_post_meta($post_id, 'tt_auto_tweet', $tt_auto))
			add_post_meta($post_id, 'tt_auto_tweet', $tt_auto);
		if(!empty($tt_auto_text)) {
			if(!update_post_meta($post_id, 'tt_auto_tweet_text',
				$tt_auto_text))
				add_post_meta($post_id, 'tt_auto_tweet_text',
				$tt_auto_text);
		}
	}
}


/**
 * Handles automatic tweeting of posts.
 */
function tt_auto_tweet($post_id) {
	$post = get_post($post_id);
	$tt_auto = get_post_meta($post_id, 'tt_auto_tweet', true);
	$tt_auto_text = get_post_meta($post_id, 'tt_auto_tweet_text', true);
	if($tt_auto_text != '')
		$tweet_text = $tt_auto_text;
	elseif(tt_option('tt_auto_tweet_text') != '')
		$tweet_text = tt_option('tt_auto_tweet_text');
	else $tweet_text = __('New blog post', 'tweet-this') .
		': [TITLE] [URL]';
	if(	$post_id == 0 ||
		get_post_meta($post_id, 'tt_tweeted', true) == 'true' ||
		$tt_auto == 'false' || $post->post_status == 'private' ||
		$post->post_type == 'revision' ||
		tt_option('tt_twitter_username') == '' ||
		get_option('tweet_this_password') == '' ||
		($tt_auto != 'true' && tt_option('tt_auto_tweet') != 'true'))
			return;
	else {
	if(tt_option('tt_twitter_username') != ''
	&& get_option('tweet_this_password') != '') {
		require_once(ABSPATH . WPINC . '/class-snoopy.php');
		$snoop = new Snoopy;
		$snoop->agent = 'Tweet This ' .
			'http://richardxthripp.thripp.com/tweet-this';
		$snoop->rawheaders = array('X-Twitter-Client' => 'Tweet This',
			'X-Twitter-Client-Version' => TT_VERSION,
			'X-Twitter-Client-URL' =>
				'http://richardxthripp.thripp.com/files/' .
				'plugins/tweet-this/tweet-this.xml');
		$snoop->user = tt_option('tt_twitter_username');
		$snoop->pass = get_option('tweet_this_password');
		$snoop->submit('http://twitter.com/statuses/update.json',
			array('status' => urldecode(str_replace('http://' .
			'twitter.com/home/?status=', '',
			get_tweet_this_url($tweet_text, 'twitter',
			get_permalink($post_id), $post->post_title,
			$post_id))), 'source' => 'tweetthis'));
		delete_post_meta($post_id, 'tt_tweeted');
		add_post_meta($post_id, 'tt_tweeted', 'true');
	}}
}


/**
 * Inserts the Tweet This links, including the other social networks.
 */
function insert_tweet_this($content) {
	global $id;
	global $preview;
	global $post;
	$tweet_this_hide = get_post_meta($id, 'tweet_this_hide', true);
	if(($tweet_this_hide && $tweet_this_hide != 'false') || $preview ||
		$post->post_status == 'draft')
			$content = $content;
	else {
		if(tt_option('tt_auto_display') == 'true' ||
			tt_option('tt_plurk') == 'true' ||
			tt_option('tt_buzz') == 'true' ||
			tt_option('tt_delicious') == 'true' ||
			tt_option('tt_digg') == 'true' ||
			tt_option('tt_facebook') == 'true' ||
			tt_option('tt_digg') == 'true' ||
			tt_option('tt_ping') == 'true' ||
			tt_option('tt_reddit') == 'true' ||
			tt_option('tt_su') == 'true')
				$content .= tt_display_limits(TT_PREFIX);
		if(tt_option('tt_auto_display') == 'true') {
			$content .= get_tweet_this('twitter');
			if(tt_option('tt_twitter_icon') == 'textbox' &&
			(tt_option('tt_plurk') == 'true' ||
			tt_option('tt_buzz') == 'true' ||
			tt_option('tt_delicious') == 'true' ||
			tt_option('tt_digg') == 'true' ||
			tt_option('tt_facebook') == 'true' ||
			tt_option('tt_digg') == 'true' ||
			tt_option('tt_ping') == 'true' ||
			tt_option('tt_reddit') == 'true' ||
			tt_option('tt_su') == 'true'))
				$content .= '<p style="margin:4px;"></p>';
			}
		if(tt_option('tt_plurk') == 'true')
			$content .= TT_SEPARATOR . get_tweet_this('plurk');
		if(tt_option('tt_buzz') == 'true')
			$content .= TT_SEPARATOR . get_tweet_this('buzz');
		if(tt_option('tt_delicious') == 'true')
			$content .= TT_SEPARATOR . get_tweet_this('delicious');
		if(tt_option('tt_digg') == 'true')
			$content .= TT_SEPARATOR . get_tweet_this('digg');
		if(tt_option('tt_facebook') == 'true')
			$content .= TT_SEPARATOR . get_tweet_this('facebook');
		if(tt_option('tt_myspace') == 'true')
			$content .= TT_SEPARATOR . get_tweet_this('myspace');
		if(tt_option('tt_ping') == 'true')
			$content .= TT_SEPARATOR . get_tweet_this('ping');
		if(tt_option('tt_reddit') == 'true')
			$content .= TT_SEPARATOR . get_tweet_this('reddit');
		if(tt_option('tt_su') == 'true')
			$content .= TT_SEPARATOR . get_tweet_this('su');
		if(tt_option('tt_auto_display') == 'true' ||
			tt_option('tt_plurk') == 'true' ||
			tt_option('tt_buzz') == 'true' ||
			tt_option('tt_delicious') == 'true' ||
			tt_option('tt_digg') == 'true' ||
			tt_option('tt_facebook') == 'true' ||
			tt_option('tt_myspace') == 'true' ||
			tt_option('tt_ping') == 'true' ||
			tt_option('tt_reddit') == 'true' ||
			tt_option('tt_su') == 'true')
				$content .= tt_display_limits(TT_SUFFIX);
		$h = array('http://www.', 'http://', 'www.');
		$h2 = array('', '', '');
		global $i;
		$url = str_replace($h, $h2, get_tweet_this_short_url());
	}
	return $content;
}


/**
 * CSS inserted using the wp_head hook.
 */
function tweet_this_css() {
	if(tt_option('tt_css') == '')
	echo	'<style type="text/css">img.' . tt_option('tt_img_css_class') .
		'{border:0;}</style>';
	else echo	'<style type="text/css">' .
			str_replace(array('[IMG_CLASS]', '[LINK_CLASS]'),
			array(tt_option('tt_img_css_class'),
			tt_option('tt_link_css_class')), tt_option('tt_css')) .
			'</style>';
}


/**
 * Update the options stored in the database when the user changes them.
 */
function update_tt_options() {
	if(isset($_REQUEST['tt']))
		$new_options = $_REQUEST['tt'];
	$booleans = array('tt_30', 'tt_limit_to_single', 'tt_limit_to_posts',
		'tt_url_www', 'tt_auto_display',
		'tt_plurk', 'tt_buzz', 'tt_delicious', 'tt_digg',
		'tt_facebook', 'tt_myspace', 'tt_ping', 'tt_reddit', 'tt_su',
		'tt_ad_vu', 'tt_footer', 'tt_ads', 'tt_new_window',
		'tt_nofollow', 'tt_auto_tweet');
	foreach($booleans as $key)
		$new_options[$key] = $new_options[$key] ? 'true' : 'false';
	if(tt_option('tt_url_service') != $new_options['tt_url_service'] ||
		tt_option('tt_url_www') != $new_options['tt_url_www'] ||
		tt_option('tt_30') != $new_options['tt_30'] ||
		tt_option('tt_custom_url_service') !=
			$new_options['tt_custom_url_service'])
			global_flush_tt_cache();

    // This section needs to be handled better.

	if($new_options['tt_link_text'] ==
	__('Tweet This Post', 'tweet-this') &&
	($new_options['tt_twitter_icon'] == 'tt-twitter-big1.png' ||
	$new_options['tt_twitter_icon'] == 'tt-twitter-big2.png' ||
	$new_options['tt_twitter_icon'] == 'tt-twitter-big3.png' ||
	$new_options['tt_twitter_icon'] == 'tt-twitter-big4.png' ||
	$new_options['tt_twitter_icon'] == 'tt-twitter-micro3.png' ||
	$new_options['tt_twitter_icon'] == 'tt-twitter-micro4.png' ||
	$new_options['tt_twitter_icon'] == 'de/tt-twitter-big1.png' ||
	$new_options['tt_twitter_icon'] == 'de/tt-twitter-big2.png' ||
	$new_options['tt_twitter_icon'] == 'de/tt-twitter-big3.png' ||
	$new_options['tt_twitter_icon'] == 'de/tt-twitter-big4.png' ||
	$new_options['tt_twitter_icon'] == 'de/tt-twitter-micro3.png' ||
	$new_options['tt_twitter_icon'] == 'de/tt-twitter-micro4.png' ||
	$new_options['tt_twitter_icon'] == 'tt-twitter-micro1.png' ||
	$new_options['tt_twitter_icon'] == 'tt-twitter-micro2.png'))
		$new_options['tt_link_text'] = '[BLANK]';

	if($new_options['tt_plurk_link_text'] ==
	__('Plurk This Post', 'tweet-this') &&
	($new_options['tt_plurk_icon'] == 'tt-plurk-big1.png' ||
	$new_options['tt_plurk_icon'] == 'tt-plurk-big2.png' ||
	$new_options['tt_plurk_icon'] == 'tt-plurk-big3.png' ||
	$new_options['tt_plurk_icon'] == 'tt-plurk-big4.png' ||
	$new_options['tt_plurk_icon'] == 'tt-plurk-micro3.png' ||
	$new_options['tt_plurk_icon'] == 'tt-plurk-micro4.png'))
		$new_options['tt_plurk_link_text'] = '[BLANK]';

	if($new_options['tt_buzz_link_text'] ==
	__('Buzz This Post', 'tweet-this') &&
	($new_options['tt_buzz_icon'] == 'tt-buzz-big1.png' ||
	$new_options['tt_buzz_icon'] == 'tt-buzz-big2.png' ||
	$new_options['tt_buzz_icon'] == 'tt-buzz-big3.png' ||
	$new_options['tt_buzz_icon'] == 'tt-buzz-big4.png' ||
	$new_options['tt_buzz_icon'] == 'tt-buzz-micro3.png' ||
	$new_options['tt_buzz_icon'] == 'tt-buzz-micro4.png'))
		$new_options['tt_buzz_link_text'] = '[BLANK]';

	if($new_options['tt_delicious_link_text'] ==
	__('Delicious', 'tweet-this') &&
	($new_options['tt_delicious_icon'] == 'tt-delicious-big1.png' ||
	$new_options['tt_delicious_icon'] == 'tt-delicious-big2.png' ||
	$new_options['tt_delicious_icon'] == 'tt-delicious-big3.png' ||
	$new_options['tt_delicious_icon'] == 'tt-delicious-big4.png' ||
	$new_options['tt_delicious_icon'] == 'tt-delicious-micro3.png' ||
	$new_options['tt_delicious_icon'] == 'tt-delicious-micro4.png'))
		$new_options['tt_delicious_link_text'] = '[BLANK]';

	if($new_options['tt_digg_link_text'] ==
	__('Digg This Post', 'tweet-this') &&
	($new_options['tt_digg_icon'] == 'tt-digg-big1.png' ||
	$new_options['tt_digg_icon'] == 'tt-digg-big2.png' ||
	$new_options['tt_digg_icon'] == 'tt-digg-big3.png' ||
	$new_options['tt_digg_icon'] == 'tt-digg-big4.png' ||
	$new_options['tt_digg_icon'] == 'tt-digg-micro3.png' ||
	$new_options['tt_digg_icon'] == 'tt-digg-micro4.png'))
		$new_options['tt_digg_link_text'] = '[BLANK]';

	if($new_options['tt_facebook_link_text'] ==
	__('Facebook', 'tweet-this') &&
	($new_options['tt_facebook_icon'] == 'tt-facebook-big1.png' ||
	$new_options['tt_facebook_icon'] == 'tt-facebook-big2.png' ||
	$new_options['tt_facebook_icon'] == 'tt-facebook-big3.png' ||
	$new_options['tt_facebook_icon'] == 'tt-facebook-big4.png' ||
	$new_options['tt_facebook_icon'] == 'tt-facebook-micro3.png' ||
	$new_options['tt_facebook_icon'] == 'tt-facebook-micro4.png'))
		$new_options['tt_facebook_link_text'] = '[BLANK]';

	if($new_options['tt_myspace_link_text'] ==
	__('MySpace', 'tweet-this') &&
	($new_options['tt_myspace_icon'] == 'tt-myspace-big1.png' ||
	$new_options['tt_myspace_icon'] == 'tt-myspace-big2.png' ||
	$new_options['tt_myspace_icon'] == 'tt-myspace-big3.png' ||
	$new_options['tt_myspace_icon'] == 'tt-myspace-big4.png' ||
	$new_options['tt_myspace_icon'] == 'tt-myspace-micro3.png' ||
	$new_options['tt_myspace_icon'] == 'tt-myspace-micro4.png'))
		$new_options['tt_myspace_link_text'] = '[BLANK]';

	if($new_options['tt_ping_link_text'] ==
	__('Ping This Post', 'tweet-this') &&
	($new_options['tt_ping_icon'] == 'tt-ping-big1.png' ||
	$new_options['tt_ping_icon'] == 'tt-ping-big2.png' ||
	$new_options['tt_ping_icon'] == 'tt-ping-big3.png' ||
	$new_options['tt_ping_icon'] == 'tt-ping-big4.png' ||
	$new_options['tt_ping_icon'] == 'tt-ping-micro3.png' ||
	$new_options['tt_ping_icon'] == 'tt-ping-micro4.png'))
		$new_options['tt_ping_link_text'] = '[BLANK]';

	if($new_options['tt_reddit_link_text'] ==
	__('Reddit', 'tweet-this') &&
	($new_options['tt_reddit_icon'] == 'tt-reddit-big1.png' ||
	$new_options['tt_reddit_icon'] == 'tt-reddit-big2.png' ||
	$new_options['tt_reddit_icon'] == 'tt-reddit-big3.png' ||
	$new_options['tt_reddit_icon'] == 'tt-reddit-big4.png' ||
	$new_options['tt_reddit_icon'] == 'tt-reddit-micro3.png' ||
	$new_options['tt_reddit_icon'] == 'tt-reddit-micro4.png'))
		$new_options['tt_reddit_link_text'] = '[BLANK]';

	if($new_options['tt_su_link_text'] ==
	__('Stumble This Post', 'tweet-this') &&
	($new_options['tt_su_icon'] == 'tt-su-big1.png' ||
	$new_options['tt_su_icon'] == 'tt-su-big2.png' ||
	$new_options['tt_su_icon'] == 'tt-su-big3.png' ||
	$new_options['tt_su_icon'] == 'tt-su-big4.png' ||
	$new_options['tt_su_icon'] == 'tt-su-micro3.png' ||
	$new_options['tt_su_icon'] == 'tt-su-micro4.png'))
		$new_options['tt_su_link_text'] = '[BLANK]';

	if(isset($_REQUEST['tt_twitter_username']))
		$new_options['tt_twitter_username'] =
			$_REQUEST['tt_twitter_username'];

	update_option('tweet_this_settings', $new_options);
	if(isset($_REQUEST['tt_twitter_password']))
		update_option('tweet_this_password',
			$_REQUEST['tt_twitter_password']);
	echo	'<br /><div id="message" class="updated fade"><p>' .
		__('Tweet This options saved.', 'tweet-this') . '</p></div>';
}


/**
 * Outputs the list of image options for each social networking service in the
 * options form under Settings > Tweet This. This is a mess.
 */
function tt_image_selection($s) {
	$l = WP_PLUGIN_URL . '/' . TT_DIR . '/icons/';
	$c = ' checked="checked"';
	$z = '.png" /></label> <input type="radio" name="tt[tt_';
	echo	'<p><input type="radio" name="tt[tt_' . $s . '_icon]" id="' .
		$s . '-01" value="tt-' . $s . '.png"';
	if(tt_option('tt_' . $s . '_icon') == 'tt-' . $s . '.png' ||
		tt_option('tt_' . $s . '_icon') == '')
			echo	$c;
	echo	' /> <label for="' . $s . '-01"><img src="' . $l . 'tt-' . $s .
		'.png" alt="tt-' . $s .	$z . $s . '_icon]" id="' . $s .
		'-02" value="tt-' . $s . '-big1.png"';
	if(tt_option('tt_' . $s . '_icon') == 'tt-' . $s . '-big1.png')
		echo	$c;
	echo	' /> <label for="' . $s . '-02"><img src="' . $l . 'tt-' . $s .
		'-big1.png" alt="tt-' . $s . '-big1' . $z . $s .
		'_icon]" id="' . $s . '-03" value="tt-' . $s . '-big2.png"';
	if(tt_option('tt_' . $s . '_icon') == 'tt-' . $s . '-big2.png')
		echo	$c;
	echo	' /> <label for="' . $s . '-03"><img src="' . $l . 'tt-' . $s .
		'-big2.png" alt="tt-' . $s . '-big2' . $z . $s .
		'_icon]" id="' . $s . '-04"  value="tt-' . $s . '-big3.png"';
	if(tt_option('tt_' . $s . '_icon') == 'tt-' . $s . '-big3.png')
		echo	$c;
	echo	' /> <label for="' . $s . '-04"><img src="' . $l . 'tt-' . $s .
		'-big3.png" alt="tt-' . $s . '-big3' . $z . $s .
		'_icon]" id="' . $s . '-05" value="tt-' . $s . '-big4.png"';
	if(tt_option('tt_' . $s . '_icon') == 'tt-' . $s . '-big4.png')
		echo	$c;
	echo	' /> <label for="' . $s . '-05"><img src="' . $l . 'tt-' . $s .
		'-big4.png" alt="tt-' . $s . '-big4' . $z . $s .
		'_icon]" id="' . $s . '-06" value="tt-' . $s . '-micro3.png"';
	if(tt_option('tt_' . $s . '_icon') == 'tt-' . $s . '-micro3.png')
		echo	$c;
	echo	' /> <label for="' . $s . '-06"><img src="' . $l . 'tt-' . $s .
		'-micro3.png" alt="tt-' . $s . '-micro3' . $z . $s .
		'_icon]" id="' . $s . '-07" value="tt-' . $s . '-micro4.png"';
	if(tt_option('tt_' . $s . '_icon') == 'tt-' . $s . '-micro4.png')
		echo	$c;
	echo	' /> <label for="' . $s . '-07"><img src="' . $l . 'tt-' . $s .
		'-micro4.png" alt="tt-' . $s . '-micro4' . $z . $s .
		'_icon]" id="' . $s . '-08" ' . 'value="noicon"';
	if(tt_option('tt_' . $s . '_icon') == 'noicon')
		echo	$c;
	echo	' /> <label for="' . $s . '-08">' .
		__('No icon.', 'tweet-this') . '</label></p>';
	if($s == 'twitter') {
		echo	'<p><input type="radio" name="tt[tt_twitter_icon]" ' .
			'id="twitter-09" value="tt-twitter2.png"';
		if(tt_option('tt_twitter_icon') == 'tt-twitter2.png')
			echo	$c;
		echo	' /> <label for="twitter-09"><img src="' . $l .
			'tt-twitter2.png" alt="tt-twitter2' . $z .
			'twitter_icon]" id="twitter-10" ' .
			'value="de/tt-twitter-big1-de.png"';
		if(tt_option('tt_twitter_icon') == 'de/tt-twitter-big1-de.png')
			echo	$c;
		echo	' /> <label for="twitter-10"><img src="' . $l .
			'de/tt-twitter-big1-de.png" ' .
			'alt="de/tt-twitter-big1-de' . $z . 'twitter_icon]" ' .
			'id="twitter-11" value="de/tt-twitter-big2-de.png"';
		if(tt_option('tt_twitter_icon') == 'de/tt-twitter-big2-de.png')
			echo	$c;
		echo	' /> <label for="twitter-11"><img src="' . $l .
			'de/tt-twitter-big2-de.png" ' .
			'alt="de/tt-twitter-big2-de' . $z . 'twitter_icon]" ' .
			'id="twitter-12" value="de/tt-twitter-big3-de.png"';
		if(tt_option('tt_twitter_icon') == 'de/tt-twitter-big3-de.png')
			echo	$c;
		echo	' /> <label for="twitter-12"><img src="' . $l .
			'de/tt-twitter-big3-de.png" ' .
			'alt="de/tt-twitter-big3-de' . $z . 'twitter_icon]" ' .
			'id="twitter-13" value="de/tt-twitter-big4-de.png"';
		if(tt_option('tt_twitter_icon') == 'de/tt-twitter-big4-de.png')
			echo	$c;
		echo	' /> <label for="twitter-13"><img src="' . $l .
			'de/tt-twitter-big4-de.png" ' .
			'alt="de/tt-twitter-big4-de' . $z . 'twitter_icon]" ' .
			'id="twitter-14" value="de/tt-twitter-micro3-de.png"';
		if(tt_option('tt_twitter_icon') ==
				'de/tt-twitter-micro3-de.png')
			echo	$c;
		echo	' /> <label for="twitter-14"><img src="' . $l .
			'de/tt-twitter-micro3-de.png" ' .
			'alt="de/tt-twitter-micro3-de' . $z .
			'twitter_icon]" id="twitter-15" ' .
			'value="de/tt-twitter-micro4-de.png"';
		if(tt_option('tt_twitter_icon') ==
				'de/tt-twitter-micro4-de.png')
			echo	$c;
		echo	' /> <label for="twitter-15"><img src="' . $l .
			'de/tt-twitter-micro4-de.png" ' .
			'alt="de/tt-twitter-micro4-de.png" /></p>';
		echo	'<p><input type="radio" name="tt[tt_twitter_icon]" ' .
			'id="twitter-16" value="tt-twitter3.png"';
		if(tt_option('tt_twitter_icon') == 'tt-twitter3.png')
			echo	$c;
		echo	' /> <label for="twitter-16"><img src="' . $l .
			'tt-twitter3.png" alt="tt-twitter3' . $z .
			'twitter_icon]" id="twitter-17" ' .
			'value="tt-twitter4.png"';
		if(tt_option('tt_twitter_icon') == 'tt-twitter4.png')
			echo	$c;
		echo	' /> <label for="twitter-17"><img src="' . $l .
			'tt-twitter4.png" alt="tt-twitter4' . $z .
			'twitter_icon]" id="twitter-18" ' .
			'value="tt-twitter-micro1.png"';
		if(tt_option('tt_twitter_icon') == 'tt-twitter-micro1.png')
			echo	$c;
		echo	' /> <label for="twitter-18"><img src="' . $l .
			'tt-twitter-micro1.png" alt="tt-twitter-micro1' . $z .
			'twitter_icon]" id="twitter-19" ' .
			'value="tt-twitter-micro2.png"';
		if(tt_option('tt_twitter_icon') == 'tt-twitter-micro2.png')
			echo	$c;
		echo	' /> <label for="twitter-19"><img src="' . $l .
			'tt-twitter-micro2.png" alt="tt-twitter-micro2' . $z .
			'twitter_icon]" id="twitter-20" value="textbox"';
		if(tt_option('tt_twitter_icon') == 'textbox')
			echo	$c;
		echo	' /> <label for="twitter-20">' .
			__('Editable text box', 'tweet-this') .	'</label> ' .
			'<label class="in">[' . __('Size', 'tweet-this') .
			': <input type="text" name="tt[tt_textbox_size]" ' .
			'id="tt[tt_textbox_size]" size="2" value="';
		if(tt_option('tt_textbox_size') == '')
			echo	'60';
		else echo	tt_option('tt_textbox_size');
		echo	'" />]</label></p>';
		}
}


/**
 * Outputs the select option for each URL service when fed the correct
 * arguments. Used for the options form under Settings > Tweet This.
 */
function tt_url_service($id, $title, $special = '') {
	echo	'<option value="' . $id . '"';
	if(tt_option('tt_url_service') == $id || ($special == 'default' &&
		tt_option('tt_url_service') == ''))
			echo	' selected="selected"';
	echo	'>' . $title . ' ';
	if($id == 'local') {
		$local = get_bloginfo('url') . '/?p=1234';
		if(tt_option('tt_url_www') == 'true')
			$local = str_replace('http://', 'www.',
				str_replace('http://www.', 'www.', $local));
		echo	$local . ' ';
	}
	printf(__('(%1$s Characters)', 'tweet-this'), tt_url_service_len($id));
	if($id == 'local')
		echo	' &nbsp;';
	echo	'</option>';
}


/**
 * Gets the length of the URLs of the current URL shortening service.
 */
function tt_url_service_len($service = '') {
	if($service == '')
		$service = tt_option('tt_url_service');
	$len = constant('TT_' . strtoupper(str_replace('.', '',
		$service)) . '_LEN');
	if($service == 'adjix' && tt_option('tt_ad_vu') == 'true')
		$len -= 4;
	if(tt_option('tt_url_www') == 'true' && $service != 'tweetburner')
		$len -= 3;
	return $len;
}


/**
 * Used in the options form to test the Twitter login credentials.
 * Code and concepts borrowed from Alex King's Twitter Tools plugin.
 */
function tt_login_test($username, $password) {
	require_once(ABSPATH . WPINC . '/class-snoopy.php');
	$snoop = new Snoopy;
	$snoop->agent = 'Tweet This ' .
		'http://richardxthripp.thripp.com/tweet-this';
	$snoop->user = $username;
	$snoop->pass = $password;
	$snoop->fetch('http://twitter.com/statuses/user_timeline.json');
	if(strpos($snoop->response_code, '200')) {
		return	'<font color="green">' . __('Login succeeded, ' .
			'you\'re good to go. Remember to save!',
			'tweet-this') . '</font>';
	} else {
		$json = new Services_JSON();
		$results = $json->decode($snoop->results);
		$error = '<font color="red">' .
			__('Sorry, login failed.', 'tweet-this') . ' ';
		if($results->error == 'This method requires authentication.')
			$error .= __('Your username or password is blank or ' .
				'incorrect.', 'tweet-this');
		else $error .= sprintf(__('Error message from Twitter: %s',
				'tweet-this'), $results->error);
		$error .= '</font>';
		return $error;
	}
}


/**
 * Verifies Twitter credentials and handles forwarding.
 */
function tt_request_handler() {
	if(isset($_REQUEST['ttaction']))
		switch($_REQUEST['ttaction']) {
		case 'tt_login_test':
			$test = @tt_login_test(
				@stripslashes($_POST['tt_twitter_username']),
				@stripslashes($_POST['tt_twitter_password']));
			die(__($test, 'tweet-this'));
			break;
		case 'tt_twitter_box': {
			$link = 'http://twitter.com/home/?status=' .
				urlencode(@html_entity_decode(
				stripslashes($_REQUEST['tt_twitter_box_text']),
				ENT_COMPAT, 'UTF-8'));
			die('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 ' .
			'Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/' .
			'xhtml1-transitional.dtd"><html xmlns="http://www.' .
			'w3.org/1999/xhtml" xml:lang="en" lang="en"><head>' .
			'<title>' . __('Sending you to Twitter...',
			'tweet-this') . '</title><meta http-equiv="content-' .
			'type" content="text/html; charset=utf-8" /><meta ' .
			'http-equiv="refresh" content="0;url=' . $link .
			'" /></head><body><p>' .
			__('Sending you to Twitter...', 'tweet-this') .
			'<a href="' . $link . '">' . __('Click here if ' .
			'nothing happens.', 'tweet-this') .
			'</a></p></body></html>');
		}
	}
}


/**
 * Displays the options form under Settings > Tweet This.
 */
function print_tt_form() {
	if(file_exists(TT_OPTIONS)) {
		if(function_exists('wp_enqueue_script'))
			wp_enqueue_script('jquery');
		require(TT_OPTIONS);
	}
	else echo	'<p>' . __('The file that constructs the options ' .
			'form, /tweet-this/lib/tt-options.php, is missing.',
			'tweet-this') . '</p>';
}


/**
 * Adds the default Tweet This options to the database upon activation of the
 * plugin or resetting of the options. Cleans up some old records from
 * Tweet This v1.1 if they are still present.
 */
function tweet_this_install() {
	$add_options = array('tt_30' => 'false', 'tt_url_service' => 'th8.us',
		'tt_alignment' => 'left', 'tt_limit_to_single' => 'false',
		'tt_limit_to_posts' => 'false', 'tt_url_www' => 'false',
		'tt_footer' => 'false', 'tt_ads' => 'false', 'tt_new_window' =>
		'false', 'tt_nofollow' => 'false', 'tt_img_css_class' =>
		'nothumb', 'tt_link_css_class' => 'tt', 'tt_css' =>
		'img.[IMG_CLASS]{border:0;}', 'tt_adjix_api_key' => '',
		'tt_ad_vu' => 'true', 'tt_custom_url_service' =>
		'http://tinyurl.com/api-create.php?url=[LONGURL]',
		'tt_auto_tweet' => 'false', 'tt_auto_tweet_text' =>
		__('New blog post', 'tweet-this') . ': [TITLE] [URL]',
		tt_twitter_username => '', tt_textbox_size => '60',
		'tt_auto_display' => 'true', 'tt_tweet_text' =>
			'[TITLE] [URL]', 'tt_link_text' =>
			__('Tweet This Post', 'tweet-this'), 'tt_title_text' =>
			__('Post to Twitter', 'tweet-this'),
			'tt_twitter_icon' => 'tt-twitter.png',
		'tt_plurk' => 'false', 'tt_plurk_text' => '[TITLE] [URL]',
			'tt_plurk_link_text' => __('Plurk This Post',
			'tweet-this'), 'tt_plurk_title_text' =>
			__('Post to Plurk', 'tweet-this'), 'tt_plurk_icon' =>
			'tt-plurk.png',
		'tt_buzz' => 'false', 'tt_buzz_link_text' =>
			__('Buzz This Post', 'tweet-this'),
			'tt_buzz_title_text' => __('Post to Yahoo Buzz',
			'tweet-this'), 'tt_buzz_icon' => 'tt-buzz.png',
		'tt_delicious' => 'false', 'tt_delicious_link_text' =>
			__('Delicious', 'tweet-this'),
			'tt_delicious_title_text' => __('Post to Delicious',
			'tweet-this'), 'tt_delicious_icon' =>
			'tt-delicious.png',
		'tt_digg' => 'false', 'tt_digg_link_text' =>
			__('Digg This Post', 'tweet-this'),
			'tt_digg_title_text' => __('Post to Digg',
			'tweet-this'), 'tt_digg_icon' => 'tt-digg.png',
		'tt_facebook' => 'false', 'tt_facebook_link_text' =>
			__('Facebook', 'tweet-this'),
			'tt_facebook_title_text' => __('Post to Facebook',
			'tweet-this'), 'tt_facebook_icon' => 'tt-facebook.png',
		'tt_myspace' => 'false', 'tt_myspace_link_text' =>
			__('MySpace', 'tweet-this'), 'tt_myspace_title_text' =>
			__('Post to MySpace', 'tweet-this'),
			'tt_myspace_icon' => 'tt-myspace.png',
		'tt_ping' => 'false', 'tt_ping_link_text' =>
			__('Ping This Post', 'tweet-this'),
			'tt_ping_title_text' => __('Post to Ping.fm',
			'tweet-this'), 'tt_ping_icon' => 'tt-ping.png',
		'tt_reddit' => 'false', 'tt_reddit_link_text' => __('Reddit',
			'tweet-this'), 'tt_reddit_title_text' =>
			__('Post to Reddit', 'tweet-this'), 'tt_reddit_icon' =>
			'tt-reddit.png',
		'tt_su' => 'false', 'tt_su_link_text' =>
			__('Stumble This Post', 'tweet-this'),
			'tt_su_title_text' => __('Post to StumbleUpon',
			'tweet-this'), 'tt_su_icon' => 'tt-su.png');
	foreach($add_options as $key => $value) {
		if($old = get_option($key)) {
			$add_options[$key] = $old;
			delete_option($key);
			}
		}
	if(get_option('tweet_this_settings') == '')
		add_option('tweet_this_settings', $add_options);
	delete_option('tweet_this_password');
	add_option('tweet_this_password', '');
	delete_option('tt_add_title');
	delete_option('tt_big_icon');
	delete_option('tt_icon');
	delete_option('tt_small_icon');
	delete_option('tweet_this_import_key');
}


/**
 * Inserts the options page into the menu under Settings > Tweet This.
 */
function tweet_this_add_options() {
	if(function_exists('add_options_page')) {
		add_options_page(__('Tweet This Options', 'tweet-this'),
			__('Tweet This', 'tweet-this'), 8,
			__FILE__, 'tweet_this_options');
	}
}


/**
 * Prints the options form and updates the options.
 */
function tweet_this_options() {
	$show_form = 'true';
	if(isset($_REQUEST['submit']))
		update_tt_options();
	elseif(isset($_REQUEST['reset'])) {
		delete_option('tweet_this_settings');
		global_flush_tt_cache();
		tweet_this_install();
	    // Resettting the options requires a page refresh on WP 1.5.
		if(version_compare($GLOBALS['wp_version'], '2.0', '<')) {
			update_option('tweet_this_import_key', 'reset2');
			echo	'<br /><div id="message" class="updated fade">
				<p>' . __('Resetting Tweet This options. ' .
				'Please wait...', 'tweet-this') . '</p></div>
				<meta http-equiv="refresh" content="0">';
			$show_form = 'false';
		}
	}
	elseif(get_option('tweet_this_import_key') == 'reset2') {
		update_option('tweet_this_import_key', 'reset1');
		echo	'<br /><div id="message" class="updated fade"><p>' .
			__('Tweet This options reset successfully!',
			'tweet-this') . '</p></div>';
	}
	elseif(isset($_REQUEST['import'])) {
		global $wpdb;
		$import_options = $_POST['import_content'];
		if(substr($import_options, 0, 1) == 'a') {
			$wpdb->query("UPDATE $wpdb->options SET option_value =
				'$import_options' WHERE option_name =
				'tweet_this_settings'");
				if(function_exists('wp_cache_flush'))
					wp_cache_flush();
				global_flush_tt_cache();
			echo	'<br /><div id="message" class="updated fade">
				<p>' . __('Tweet This options imported ' .
				'successfully!', 'tweet-this') . '</p></div>';
			}
		else echo	'<br /><div id="message" class="error">
				<p>' . __('There is something wrong with the' .
				' provided options. Import aborted.',
				'tweet-this') . '</p></div>';
	}
	if($show_form != 'false')
		print_tt_form();
}


/**
 * Sets one cached URL to "getnew". get_tweet_this_short_url() regenerates
 * the shortened URL based on the the user's settings. This is used when
 * the user edits a post or changes permalinks.
 */
function flush_tt_cache($post_id) {
	$cached_tt_url = get_post_meta($post_id, 'tweet_this_url', true);
	if($cached_tt_url && $cached_tt_url != 'getnew') {
		update_post_meta($post_id, 'tweet_this_url', 'getnew');
	}
}


/**
 * Deletes a cached URL. Triggered when you delete a post.
 */
function delete_tt_cache() {
	global $id;
	delete_post_meta($id, 'tweet_this_url');
}


/**
 * Sets every shortened URL in the database to "getnew", triggering a reindex
 * the first time each post is viewed again. This is used when you change
 * permalink structures or URL services, since the old URLs can no longer be
 * trusted. Meta keys are reused to prevent artifical inflation of ID counter.
 */
function global_flush_tt_cache() {
	global $wpdb;
	$wpdb->query("UPDATE $wpdb->postmeta SET meta_value = 'getnew' WHERE
		meta_key = 'tweet_this_url'");
}


/**
 * Deletes every cached URL upon deactivating the plugin. Dangerous function.
 * I realize some people would prefer not to have their cached URLs deleted
 * when deactivating, as this also inflates the meta key ID counter, but
 * people who are removing Tweet This permanently do not want hundreds of
 * cached URLs to remain in the database, so this function stays.
 */
function global_delete_tt_cache() {
	global $wpdb;
	$wpdb->query("DELETE FROM $wpdb->postmeta " .
		"WHERE meta_key = 'tweet_this_url'");
	delete_option('tweet_this_import_key');
}


/**
 * Loads the textdomain. For internationalization.
 */
function tweet_this_init() {
	if(function_exists('load_plugin_textdomain')) {
		if(version_compare($GLOBALS['wp_version'], '2.6', '<'))
			load_plugin_textdomain('tweet-this',
				'wp-content/plugins/' . TT_DIR . '/languages');
		else load_plugin_textdomain('tweet-this', false,
			TT_DIR . '/languages');
	}
}


/**
 * The footer advertisement for Tweet This. This is inserted into your blog if
 * "Insert Tweet This message in footer" is checked under Settings > Tweet This
 * (default is checked).
 */
function tweet_this_footer() {
	echo	'<p>';
	printf(__('Twitter links powered by ' .
		'<a href="http://richardxthripp.thripp.com/tweet-this">Tweet' .
		' This v%1$s</a>, a WordPress plugin for Twitter.',
		'tweet-this'), TT_VERSION);
	echo	'</p>';
}


/**
 * Inserts Google AdSense ad using my publisher ID into the body of your blog
 * if "Insert Google AdSense ads to support Tweet This" is checked in
 * Settings > Tweet This (default is unchecked). These ads pull in $100 a month
 * for me, paying the whole hosting bill for my URL shortening service, Th8.us,
 * my blog, and my domain fees.
 */
function tweet_this_ad_body($content) {
	if(is_singular())
		return $content .= '<p><script type="text/javascript">
			google_ad_client = "pub-5149869439810473";
			google_ad_slot = "1830968079";
			google_ad_width = 336;
			google_ad_height = 280;
			</script>
			<script type="text/javascript" ' .
			'src="http://pagead2.googlesyndication.com/pagead/' .
			'show_ads.js"></script></p>';
}


/**
 * An ad is also inserted into the footer if the ads option is checked.
 */
function tweet_this_ad_footer() {
	echo	'<p><script type="text/javascript">
		google_ad_client = "pub-5149869439810473";
		google_ad_slot = "6830530578";
		google_ad_width = 728;
		google_ad_height = 90;
		</script>
		<script type="text/javascript" ' .
		'src="http://pagead2.googlesyndication.com/pagead/' .
		'show_ads.js"></script></p>';
}


/**
 * Actions for updating the database and tweeting posts.
 */
add_action('init', 'tweet_this_init');
add_action('init', 'tt_request_handler');
add_action('draft_post', 'tt_store_post_options', 1, 2);
add_action('publish_post', 'flush_tt_cache');
add_action('publish_post', 'tt_auto_tweet', 99);
add_action('save_post', 'tt_store_post_options', 1, 2);
add_action('save_post', 'flush_tt_cache');
add_action('delete_post', 'delete_tt_cache');
add_action('generate_rewrite_rules', 'global_flush_tt_cache');
add_action('edit_form_advanced', 'tt_post_options');
add_filter('the_content', 'insert_tweet_this');
if(TT_HIDE_MENU != true)
	add_action('admin_menu', 'tweet_this_add_options');
if(tt_option('tt_css') != '[BLANK]')
	add_action('wp_head', 'tweet_this_css');
if(tt_option('tt_footer') == 'true')
	add_action('wp_footer', 'tweet_this_footer');
if(tt_option('tt_ads') == 'true') {
	add_action('the_content', 'tweet_this_ad_body');
	add_action('wp_footer', 'tweet_this_ad_footer');}


/**
 * Activation / deactivation hooks.
 */
if(function_exists('register_activation_hook'))
	register_activation_hook(__FILE__, 'tweet_this_install');
if(function_exists('register_deactivation_hook'))
	register_deactivation_hook(__FILE__, 'global_delete_tt_cache');


?>