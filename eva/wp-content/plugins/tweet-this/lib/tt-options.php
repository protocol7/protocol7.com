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
 * This file contains the options menu. It is referenced by tweet-this.php.
 */


	if(TT_OVERRIDE_OPTIONS == true && TT_SPECIAL_OPTIONS != '')
		echo	'<br /><div id="override" class="error" ' .
			'style="width:800px;"><p>' . __('WARNING: ' .
			'TT_OVERRIDE_OPTIONS is set to true in the ' .
			'tt-config.php file in the tweet-this directory. ' .
			'Any options set here will be disregarded; only the ' .
			'options specified in the tt-config.php will be ' .
			'used. Please set TT_OVERRIDE_OPTIONS to false, or ' .
			'set TT_HIDE_MENU to true to remove the options ' .
			'form entirely.', 'tweet-this') . '</p></div>';
	if(isset($_REQUEST['reset']) &&
		version_compare($GLOBALS['wp_version'], '2.0', '>='))
			echo	'<br /><div id="message" class="updated ' .
			'fade"><p>' . __('Tweet This options reset.',
			'tweet-this') . '</p></div>';
	$s = ' selected="selected"';
	$u = tt_option('tt_url_service');
	$v = ' checked="checked"';
	global $wpdb;
	$export_options = $wpdb->get_var("SELECT option_value FROM
		$wpdb->options WHERE option_name = 'tweet_this_settings'");
	$count1 = number_format($wpdb->get_var("SELECT COUNT(*)
		FROM $wpdb->posts WHERE post_status = 'publish'"));
	$count2 = number_format($wpdb->get_var("SELECT COUNT(*) FROM
		$wpdb->postmeta WHERE meta_key = 'tweet_this_url' AND
		meta_value != 'getnew'"));
	if($count2 > $count1)
		$count2 = $count1;
	echo	'<script type="text/javascript">
		function ttTestLogin() {
			var result = jQuery(\'#tt_login_test_result\');
			result.show().addClass(\'tt_login_result_wait\')' .
			'.html(\'Testing...\');
			jQuery.post(
				"' . get_bloginfo('wpurl') . '/index.php"
				, {
					ttaction: "tt_login_test"
					, tt_twitter_username: ' .
					'jQuery(\'#tt_twitter_username\').val()
					, tt_twitter_password: ' .
					'jQuery(\'#tt_twitter_password\').val()
				}
				, function(data) {
					result.html(data).removeClass' .
					'(\'tt_login_result_wait\');
					setTimeout' .
					'(\'ttTestLoginResult();\', 60000);
				}
			);
		}
		function ttTestLoginResult() {
			jQuery(\'#tt_login_test_result\').fadeOut(\'slow\');
		}
		var lastDiv = "";
		function showDiv(divName) {
			if(lastDiv) {
				document.getElementById(lastDiv).className =' .
				' "hiddenDiv";
			}
		if(divName && document.getElementById(divName)) {
			document.getElementById(divName).className = '.
			'"visibleDiv";
			lastDiv = divName;
			}
		}
		var enablepersist = \'on\';
		var collapseprevious = \'no\';
		if(document.getElementById) {
			document.write(\'<style type="text/css">\');
			document.write(\'.switchcontent{display:none;}\');
			document.write(\'</style>\');
			}
		function getElementbyClass(classname) {
			ccollect = new Array();
			var inc = 0;
			var alltags = document.all ? document.all :
			document.getElementsByTagName("*");
			for(i = 0; i<alltags.length; i++) {
				if(alltags[i].className == classname)
					ccollect[inc++] = alltags[i];
				}
			}
		function contractcontent(omit) {
			var inc = 0;
			while(ccollect[inc]) {
				if(ccollect[inc].id != omit)
					ccollect[inc].style.display = \'none\';
					inc++;
				}
			}
		function expandcontent(cid) {
			if(typeof ccollect != \'undefined\') {
				if(collapseprevious=="yes")
					contractcontent(cid);
				document.getElementById(cid).style.display =
				(document.getElementById(cid).style.display !=
					\'block\') ? \'block\' : \'none\'
				}
			}
		function revivecontent() {
			contractcontent(\'omitnothing\');
			selectedItem = getselectedItem();
			selectedComponents = selectedItem.split(\'|\');
			for(i = 0; i<selectedComponents.length-1; i++);
			document.getElementById(selectedComponents[i]).style' .
				'.display = \'block\'
			}
		function get_cookie(Name) {
			var search = Name + \'=\';
			var returnvalue = \'\';
			if(document.cookie.length > 0) {
				offset = document.cookie.indexOf(search);
				if(offset != -1) {
					offset += search.length;
					end = document.cookie.indexOf(\';\',
						offset);
					if(end == -1)
						end = document.cookie.length;
					returnvalue =
						unescape(document.cookie.' .
						'substring(offset, end));
					}
				}
			return returnvalue;
			}
		function getselectedItem() {
			if(get_cookie(window.location.pathname) != \'\') {
				selectedItem = get_cookie(window.location.' .
					'pathname);
				return selectedItem;
				}
			else return \'\';
			}
		function saveswitchstate() {
			var inc = 0;
			selectedItem = \'\';
			while (ccollect[inc]) {
				if(ccollect[inc].style.display == \'block\')
					selectedItem += ccollect[inc].id+\'|\';
				inc++;
				}
			document.cookie=window.location.' .
				'pathname+\'=\'+selectedItem
			}
		function do_onload() {
			uniqueidn = window.location.pathname+\'firsttimeload\';
			getElementbyClass(\'switchcontent\');
			if(enablepersist == \'on\' && typeof ccollect !=
				\'undefined\') {
				document.cookie = (get_cookie(uniqueidn) ==
					\'\') ? uniqueidn+\'=1\' :
					uniqueidn+\'=0\';
				firsttimeload = (get_cookie(uniqueidn) == 1)
					? 1 : 0;
				if(!firsttimeload) revivecontent();
				}
			}
		if(window.addEventListener) window.addEventListener(\'load\',
			do_onload, false);
		else if(window.attachEvent) window.attachEvent(\'onload\',
			do_onload);
		else if(document.getElementById) window.onload = do_onload;
		if(enablepersist == \'on\' && document.getElementById)
			window.onunload = saveswitchstate
		</script>
		<style type="text/css">
		label.in {
			padding-bottom:3px;
		}
		.hiddenDiv {
			display:none;
		}
		.visibleDiv {
			display:block;
		}
		</style>
		<div class="wrap"><h2>';
	printf(__('<a href="http://richardxthripp.thripp.com/tweet-this">' .
		'Tweet This</a> v%1$s Options', 'tweet-this'), TT_VERSION);
	echo	'</h2>';
	if((ini_get('allow_url_fopen') == 0 ||
		strtolower('allow_url_fopen') == 'off') &&
		!function_exists('curl_init'))
			echo	'<div id="curl" class="error" ' .
				'style="width:800px;"><p>' .
				__('Allow_url_fopen and curl are disabled in' .
				' your PHP configuration. All URLs ' .
				'will be served locally, regardless of your ' .
				'chosen URL service. To fix this, try adding' .
				' these lines to your <a href="http://www.' .
				'washington.edu/computing/web/publishing/php' .
				'-ini.html">php.ini file</a>: `extension = ' .
				'curl.so` and `allow_url_fopen = on`.',
				'tweet-this') . '</p></div>';
	echo	'<p>';
	printf(__('You have <strong>%1$s' . '</strong> published posts and ' .
		'pages. Tweet This has short URLs for <strong>%2$s</strong> ' .
		'of them. URLs are cached as needed.', 'tweet-this'),
		$count1, $count2);
	echo	'</p><form id="tweet-this" name="tweet-this" method="post" ' .
		'action="">';
	if(function_exists('wp_nonce_field'))
		wp_nonce_field('update-options');
	echo	'<p>' . __('URL Service', 'tweet-this') . ':
		<select name="tt[tt_url_service]" id="tt[tt_url_service]" ' .
		'onchange="showDiv(this.value);">';
	tt_url_service('adjix', 'Adjix.com');
	tt_url_service('bit.ly', 'bit.ly');
	tt_url_service('is.gd', 'is.gd');
	tt_url_service('metamark', 'Metamark.net');
	tt_url_service('snurl', 'Snurl.com');
	tt_url_service('th8.us', 'Th8.us', 'default');
	tt_url_service('tinyurl', 'TinyURL.com');
	tt_url_service('tweetburner', 'Tweetburner.com');
	tt_url_service('local', __('Local, i.e.'));
	echo	'<option value="custom"';
	if($u == 'custom')
		echo	$s;
	echo	'>' . __('Custom', 'tweet-this') . '</option></select></p>
		<div id="adjix"';
	if($u != "adjix")
		echo	' class="hiddenDiv"';
	echo	'><label class="t" for="tt[tt_adjix_api_key]">' .
		__('Adjix Partner ID', 'tweet-this') . ':</label>
		<input type="text" name="tt[tt_adjix_api_key]" ' .
		'id="tt[tt_adjix_api_key]" size="50" value="' .
		tt_option('tt_adjix_api_key') . '" /><p>
		<label><input type="checkbox" name="tt[tt_ad_vu]"';
	if(tt_option('tt_ad_vu') == 'true')
		echo	$v;
		echo	' /> ';
	printf(__('Use shorter ad.vu URLs (%1$s Characters)', 'tweet-this'),
		(TT_ADJIX_LEN - 4));
	echo	'</label></p></div><div id="custom"';
	if($u != "custom")
		echo	' class="hiddenDiv"';
	echo	'><label class="t" for="tt[tt_custom_url_service]">' .
		__('URL of API', 'tweet-this') . ':</label>
		<input type="text" name="tt[tt_custom_url_service]" ' .
		'id="tt[tt_custom_url_service]" size="80" value="';
	if(tt_option('tt_custom_url_service') == '')
		echo	'http://tinyurl.com/api-create.php?url=[LONGURL]';
	else echo	tt_option('tt_custom_url_service');
	echo	'" /><p style="width:800px;">' . __('The URL service must ' .
		'allow HTTP GET requests, meaning that the long URL is ' .
		'passed to the API as a parameter in the URL itself. HTTP ' .
		'POST is not supported. The output of the API must be a ' .
		'plain-text short URL including the http prefix, ' .
		'<a href="http://th8.us/api.php?url=http://richardxthripp.' .
		'thripp.com/tweet-this">like this</a>. The only valid ' .
		'variable is "[LONGURL]". Please check your blog after ' .
		'specifying the API\'s URL because its validity will not be ' .
		'confirmed. <a href="http://richardxthripp.thripp.com/' .
		'tweet-this-custom-url-services">A list of various URL ' .
		'shortening services is available</a>, including the seven ' .
		'removed in Tweet This 1.6.', 'tweet-this') .
		'</p></div>';
	if(!function_exists('curl_init')) {
		echo	'<div id="tweetburner"';
		if($u != "tweetburner")
			echo	' class="hiddenDiv"';
		echo	' style="width:800px;"><font color="red">' .
			__('Curl, which Tweetburner requires, is disabled ' .
			'in your PHP configuration. Choosing Tweetburner ' .
			'will have the same affect as chosing "Local." To ' .
			'fix this, try adding the line `extension = ' .
			'curl.so` to your <a href="http://www.washington.edu' .
			'/computing/web/publishing/php-ini.html">php.ini ' .
			'file</a>.', 'tweet-this') . '</font></div>';
		}
	echo	'<p>' . __('Set "Link" to "[BLANK]" for no link ' .
		'text (also works for extended services). This will be done ' .
		'automatically if you change the icon.', 'tweet-this') .
		'</p>';
	if(tt_option('tt_twitter_icon') == 'textbox')
		echo	'<p><em>' . __('For the editable text box, the ' .
			'"Link" field serves as the submit button\'s text. ' .
			'"Tweet This Post" will be used if it is set to ' .
			'"[BLANK]".', 'tweet-this') . '</em></p>';
	echo	'<p>' . __('You can add @\'s and hashtags to "Tweet Text," ' .
		'i.e. "@richardxthripp [TITLE] [URL] #photography."',
		'tweet-this') . '</p>';
	echo	'<p><label class="in">
		<input type="checkbox" name="tt[tt_auto_display]"';
	if(tt_option('tt_auto_display') != 'false')
		echo	$v;
	echo	' /> ' . __('Insert Tweet This', 'tweet-this') .
		'</label> | ' . __('Tweet Text', 'tweet-this') .
		': <input type="text" name="tt[tt_tweet_text]"' .
		' id="tt[tt_tweet_text]" size="24" value="';
	if(tt_option('tt_tweet_text') == '')
		echo	'[TITLE] [URL]';
	else echo	tt_option('tt_tweet_text');
	echo	'" /> ' . __('Link', 'tweet-this') .
		': <input type="text" name="tt[tt_link_text]" ' .
		'id="tt[tt_link_text]" size="24" value="';
	if(tt_option('tt_link_text') == '')
		echo	__('Tweet This Post', 'tweet-this');
	else echo	tt_option('tt_link_text');
	echo	'" /> ' . __('Title', 'tweet-this') . ': <input type="text" ' .
		'name="tt[tt_title_text]" id="tt[tt_title_text]" size="24" ' .
		'value="';
	if(tt_option('tt_title_text') == '')
		echo	__('Post to Twitter', 'tweet-this');
	else echo	tt_option('tt_title_text');
	echo	'" /></p>';
	tt_image_selection('twitter');

	echo	'<p><label><input type="checkbox" name="tt[tt_auto_tweet]"';
	if(tt_option('tt_auto_tweet') == 'true')
		echo	$v;
	echo	' /> ' . __('"Send to Twitter" defaults to checked on ' .
		'unpublished posts', 'tweet-this') . '</label></p><p>' .
		__('Twitter Username', 'tweet-this') .
		': <input type="text" name="tt_twitter_username" ' .
		'id="tt_twitter_username" size="21" value="' .
		tt_option('tt_twitter_username') . '" autocomplete="off" /> ' .
		__('Password', 'tweet-this') . ': <input type="password" ' .
		'name="tt_twitter_password" id="tt_twitter_password" ' .
		'size="21" value="' .
		stripslashes(htmlentities(get_option('tweet_this_password'))) .
		'" autocomplete="off" /> Tweet Text: <input type="text" ' .
		'name="tt[tt_auto_tweet_text]" id="tt[tt_auto_tweet_text]" ' .
		'size="33" value="';
	if(tt_option('tt_auto_tweet_text') == '')
		echo	__('New blog post', 'tweet-this') . ': [TITLE] [URL]';
	else echo	tt_option('tt_auto_tweet_text');
	echo	'" /></p><p><input type="button" class="button" ' .
		'name="tt_login_test" id="tt_login_test" value="' .
		__('Test Twitter Login', 'tweet-this') .
		'" onclick="ttTestLogin(); return false;" />
		<span id="tt_login_test_result"></span></p>

		<p onclick="expandcontent(\'s1\')" ' .
		'style="cursor:hand;cursor:pointer;">
		<u><strong>' . __('Advanced Options', 'tweet-this') .
		'</strong></u></p><div id="s1" class="switchcontent"><p>
		<label class="in">' . __('Alignment of Links', 'tweet-this') .
		': </label><select name="tt[tt_alignment]" ' .
		'id="tt[tt_alignment]"><option value="left"';
	if(tt_option('tt_alignment') == 'left')
		echo	$s;
	echo	'>' . __('Left', 'tweet-this') . '</option>
		<option value="right"';
	if(tt_option('tt_alignment') == 'right')
		echo	$s;
	echo	'>' . __('Right', 'tweet-this') . '</option>
		<option value="center"';
	if(tt_option('tt_alignment') == 'center')
		echo	$s;
	echo	'>' . __('Center', 'tweet-this') . ' &nbsp; </option>
		</select> ' . __('Image CSS Class', 'tweet-this') .
		': <input type="text" name="tt[tt_img_css_class]" ' .
		'id="tt[tt_img_css_class]" size="28" value="';
	if(tt_option('tt_img_css_class') == '')
		echo	'nothumb';
	else echo	tt_option('tt_img_css_class');
	echo	'" /> ' . __('Link CSS Class', 'tweet-this') .
		': <input type="text" name="tt[tt_link_css_class]" ' .
		'id="tt[tt_link_css_class]" size="27" value="';
	if(tt_option('tt_link_css_class') == '')
		echo	'tt';
	else echo	tt_option('tt_link_css_class');
	echo	'" /></p><p>' . __('CSS to insert. "[BLANK]" for none. ' .
		'"[IMG_CLASS]" and "[LINK_CLASS]" are also valid variables.',
		'tweet-this') . '</p><p><input type="text" ' .
		'name="tt[tt_css]" id="tt[tt_css]" size="77" value="';
	if(tt_option('tt_css') == '')
		echo	'img.[IMG_CLASS]{border:0;}';
	else echo	tt_option('tt_css');
	echo	'" /></p><p><label>
		<input type="checkbox" name="tt[tt_limit_to_single]"';
	if(tt_option('tt_limit_to_single') == 'true')
		echo	$v;
	echo	' /> ' . __('Only show Tweet This when viewing single posts ' .
		'or pages', 'tweet-this') . '</label></p><p><label>
		<input type="checkbox" name="tt[tt_limit_to_posts]"';
	if(tt_option('tt_limit_to_posts') == 'true')
		echo	$v;
	echo	' /> ' . __('Hide Tweet This on pages', 'tweet-this') .
		'</label></p><p><label>
		<input type="checkbox" name="tt[tt_url_www]"';
	if(tt_option('tt_url_www') == 'true')
		echo	$v;
	echo	' /> ' . __('Use "www." instead of "http://" in shortened ' .
		'URLs', 'tweet-this') . '</label></p><p><label>
		<input type="checkbox" name="tt[tt_30]"';
	if(tt_option('tt_30') == 'true')
		echo	$v;
	echo	' /> ' . __('Don\'t shorten URLs under 30 characters',
		'tweet-this') . '</label></p><p><label>
		<input type="checkbox" name="tt[tt_new_window]"';
	if(tt_option('tt_new_window') == 'true')
		echo	$v;
	echo	' /> ' . __('Open links in new windows', 'tweet-this') .
		'</label></p><p><label>
		<input type="checkbox" name="tt[tt_nofollow]"';
	if(tt_option('tt_nofollow') == 'true')
		echo	$v;
	echo	' /> ' . __('Add nofollow tag to links', 'tweet-this') .
		'</label></p>';
	echo	'<p><label><input type="checkbox" name="tt[tt_footer]"';
	if(tt_option('tt_footer') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert Tweet This message in footer',
		'tweet-this') . '</label></p><p><label>
		<input type="checkbox" name="tt[tt_ads]"';
	if(tt_option('tt_ads') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert Google AdSense ads to support Tweet This',
		'tweet-this') . '</label></p><p><em>' . __('Some advanced ' .
		'options can only be set in the tt-config.php file in the ' .
		'tweet-this directory.', 'tweet-this') . '</em></p></div>
		<p onclick="expandcontent(\'s2\')" style="cursor:hand;' .
		'cursor:pointer;"><u><strong>' .
		__('Extended Services', 'tweet-this') . '</strong></u></p>
		<div id="s2" class="switchcontent"><p><label class="in">
		<input type="checkbox" name="tt[tt_plurk]"';
	if(tt_option('tt_plurk') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert Plurk This', 'tweet-this') .
		'</label> | ' . __('Plurk Text', 'tweet-this') .
		': <input type="text" name="tt[tt_plurk_text]" ' .
		'id="tt[tt_plurk_text]" size="25" value="';
	if(tt_option('tt_plurk_text') == '')
		echo	'[TITLE] [URL]';
	else echo	tt_option('tt_plurk_text');
	echo	'" /> ' . __('Link', 'tweet-this') . ': <input type="text" ' .
		'name="tt[tt_plurk_link_text]" id="tt[tt_plurk_link_text]" ' .
		'size="25" value="';
	if(tt_option('tt_plurk_link_text') == '')
		echo	__('Plurk This Post', 'tweet-this');
	else echo	tt_option('tt_plurk_link_text');
	echo	'" /> ' . __('Title', 'tweet-this') . ': ' .
		'<input type="text" name="tt[tt_plurk_title_text]" ' .
		'id="tt[tt_plurk_title_text]" size="25" ' .
		'value="';
	if(tt_option('tt_plurk_title_text') == '')
		echo	__('Post to Plurk', 'tweet-this');
	else echo	tt_option('tt_plurk_title_text');
	echo	'" /></p>';
	tt_image_selection('plurk');
	echo	'<p><label class="in"><input type="checkbox" ' .
		'name="tt[tt_buzz]"';
	if(tt_option('tt_buzz') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert Buzz This (Yahoo)', 'tweet-this') .
		'</label> | ' . __('Link', 'tweet-this') .
		': <input type="text" name="tt[tt_buzz_link_text]" ' .
		'id="tt[tt_buzz_link_text]" size="42" value="';
	if(tt_option('tt_buzz_link_text') == '')
		echo	__('Buzz This Post', 'tweet-this');
	else echo	tt_option('tt_buzz_link_text');
	echo	'" /> ' . __('Title', 'tweet-this') . ': ' .
		'<input type="text" name="tt[tt_buzz_title_text]" ' .
		'id="tt[tt_buzz_title_text]" size="41" ' .
		'value="';
	if(tt_option('tt_buzz_title_text') == '')
		echo	__('Post to Yahoo Buzz', 'tweet-this');
	else echo	tt_option('tt_buzz_title_text');
	echo	'" /></p>';
	tt_image_selection('buzz');
	echo	'<p><label class="in"><input type="checkbox" ' .
		'name="tt[tt_delicious]"';
	if(tt_option('tt_delicious') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert Delicious', 'tweet-this') .
		'</label> | ' . __('Link', 'tweet-this') .
		': <input type="text" name="tt[tt_delicious_link_text]" ' .
		'id="tt[tt_delicious_link_text]" size="46" value="';
	if(tt_option('tt_delicious_link_text') == '')
		echo	__('Delicious', 'tweet-this');
	else echo	tt_option('tt_delicious_link_text');
	echo	'" /> ' . __('Title', 'tweet-this') . ': ' .
		'<input type="text" name="tt[tt_delicious_title_text]" ' .
		'id="tt[tt_delicious_title_text]" size="46" ' .
		'value="';
	if(tt_option('tt_delicious_title_text') == '')
		echo	__('Post to Delicious', 'tweet-this');
	else echo	tt_option('tt_delicious_title_text');
	echo	'" /></p>';
	tt_image_selection('delicious');
	echo	'<p><label class="in"><input type="checkbox" ' .
		'name="tt[tt_digg]"';
	if(tt_option('tt_digg') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert Digg This', 'tweet-this') .
		'</label> | ' . __('Link', 'tweet-this') .
		': <input type="text" name="tt[tt_digg_link_text]" ' .
		'id="tt[tt_digg_link_text]" size="46" value="';
	if(tt_option('tt_digg_link_text') == '')
		echo	__('Digg This Post', 'tweet-this');
	else echo	tt_option('tt_digg_link_text');
	echo	'" /> ' . __('Title', 'tweet-this') . ': ' .
		'<input type="text" name="tt[tt_digg_title_text]" ' .
		'id="tt[tt_digg_title_text]" size="46" ' .
		'value="';
	if(tt_option('tt_digg_title_text') == '')
		echo	__('Post to Digg', 'tweet-this');
	else echo	tt_option('tt_digg_title_text');
	echo	'" /></p>';
	tt_image_selection('digg');
	echo	'<p><label class="in"><input type="checkbox" ' .
		'name="tt[tt_facebook]"';
	if(tt_option('tt_facebook') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert Facebook', 'tweet-this') .
		'</label> | ' . __('Link', 'tweet-this') .
		': <input type="text" name="tt[tt_facebook_link_text]" ' .
		'id="tt[tt_facebook_link_text]" size="45" value="';
	if(tt_option('tt_facebook_link_text') == '')
		echo	__('Facebook', 'tweet-this');
	else echo	tt_option('tt_facebook_link_text');
	echo	'" /> ' . __('Title', 'tweet-this') . ': ' .
		'<input type="text" name="tt[tt_facebook_title_text]" ' .
		'id="tt[tt_facebook_title_text]" size="46" ' .
		'value="';
	if(tt_option('tt_facebook_title_text') == '')
		echo	__('Post to Facebook', 'tweet-this');
	else echo	tt_option('tt_facebook_title_text');
	echo	'" /></p>';
	tt_image_selection('facebook');
	echo	'<p><label class="in"><input type="checkbox" ' .
		'name="tt[tt_myspace]"';
	if(tt_option('tt_myspace') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert Myspace', 'tweet-this') .
		'</label> | ' . __('Link', 'tweet-this') .
		': <input type="text" name="tt[tt_myspace_link_text]" ' .
		'id="tt[tt_myspace_link_text]" size="46" value="';
	if(tt_option('tt_myspace_link_text') == '')
		echo	__('MySpace', 'tweet-this');
	else echo	tt_option('tt_myspace_link_text');
	echo	'" /> ' . __('Title', 'tweet-this') . ': ' .
		'<input type="text" name="tt[tt_myspace_title_text]" ' .
		'id="tt[tt_myspace_title_text]" size="47" ' .
		'value="';
	if(tt_option('tt_myspace_title_text') == '')
		echo	__('Post to MySpace', 'tweet-this');
	else echo	tt_option('tt_myspace_title_text');
	echo	'" /></p>';
	tt_image_selection('myspace');
	echo	'<p><label class="in"><input type="checkbox" ' .
		'name="tt[tt_ping]"';
	if(tt_option('tt_ping') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert Ping This', 'tweet-this') .
		'</label> | ' . __('Link', 'tweet-this') .
		': <input type="text" name="tt[tt_ping_link_text]" ' .
		'id="tt[tt_ping_link_text]" size="46" value="';
	if(tt_option('tt_ping_link_text') == '')
		echo	__('Ping This Post', 'tweet-this');
	else echo	tt_option('tt_ping_link_text');
	echo	'" /> ' . __('Title', 'tweet-this') . ': ' .
		'<input type="text" name="tt[tt_ping_title_text]" ' .
		'id="tt[tt_ping_title_text]" size="46" ' .
		'value="';
	if(tt_option('tt_ping_title_text') == '')
		echo	__('Post to Ping.fm', 'tweet-this');
	else echo	tt_option('tt_ping_title_text');
	echo	'" /></p>';
	tt_image_selection('ping');
	echo	'<p><label class="in"><input type="checkbox" ' .
		'name="tt[tt_reddit]"';
	if(tt_option('tt_reddit') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert Reddit', 'tweet-this') .
		'</label> | ' . __('Link', 'tweet-this') .
		': <input type="text" name="tt[tt_reddit_link_text]" ' .
		'id="tt[tt_reddit_link_text]" size="47" value="';
	if(tt_option('tt_reddit_link_text') == '')
		echo	__('Reddit This Post', 'tweet-this');
	else echo	tt_option('tt_reddit_link_text');
	echo	'" /> ' . __('Title', 'tweet-this') . ': ' .
		'<input type="text" name="tt[tt_reddit_title_text]" ' .
		'id="tt[tt_reddit_title_text]" size="48" ' .
		'value="';
	if(tt_option('tt_reddit_title_text') == '')
		echo	__('Post to Reddit', 'tweet-this');
	else echo	tt_option('tt_reddit_title_text');
	echo	'" /></p>';
	tt_image_selection('reddit');
	echo	'<p><label class="in"><input type="checkbox" name="tt[tt_su]"';
	if(tt_option('tt_su') == 'true')
		echo	$v;
	echo	' /> ' . __('Insert StumbleUpon', 'tweet-this') .
		'</label> | ' . __('Link', 'tweet-this') .
		': <input type="text" name="tt[tt_su_link_text]" ' .
		'id="tt[tt_su_link_text]" size="44" value="';
	if(tt_option('tt_su_link_text') == '')
		echo	__('Stumble This Post', 'tweet-this');
	else echo	tt_option('tt_su_link_text');
	echo	'" /> ' . __('Title', 'tweet-this') . ': ' .
		'<input type="text" name="tt[tt_su_title_text]" ' .
		'id="tt[tt_su_title_text]" size="43" ' .
		'value="';
	if(tt_option('tt_su_title_text') == '')
		echo	__('Post to StumbleUpon', 'tweet-this');
	else echo	tt_option('tt_su_title_text');
	echo	'" /></p>';
	tt_image_selection('su');
	// This seems pointless, but the Codex recommends it.
	echo	'</div><input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="tt[tt_30], ' .
		'tt[tt_url_service], tt[tt_custom_url_service], ' .
		'tt[tt_alignment], tt[tt_limit_to_single], ' .
		'tt[tt_limit_to_posts], tt[tt_auto_tweet], ' .
		'tt[tt_auto_tweet_text], tt_twitter_username, ' .
		'tt_twitter_password, tt[tt_textbox_size], tt[tt_url_www], ' .
		'tt[tt_footer], tt[tt_ads], tt[tt_new_window], ' .
		'tt[tt_nofollow], tt[tt_img_css_class], ' .
		'tt[tt_link_css_class], tt[tt_css], tt[tt_adjix_api_key], ' .
		'tt[tt_ad_vu], tt[tt_auto_display], tt[tt_tweet_text], ' .
		'tt[tt_link_text], tt[tt_title_text], tt[tt_twitter_icon], ' .
		'tt[tt_plurk], tt[tt_plurk_text], tt[tt_plurk_link_text], ' .
		'tt[tt_plurk_title_text], tt[tt_plurk_icon], tt[tt_buzz], ' .
		'tt[tt_buzz_link_text], tt[tt_buzz_title_text], ' .
		'tt[tt_buzz_icon], tt[tt_delicious], ' .
		'tt[tt_delicious_link_text], tt[tt_delicious_title_text], ' .
		'tt[tt_delicious_icon], tt[tt_digg], tt[tt_digg_link_text], ' .
		'tt[tt_digg_title_text], tt[tt_digg_icon], tt[tt_facebook], ' .
		'tt[tt_facebook_link_text], tt[tt_facebook_title_text], ' .
		'tt[tt_facebook_icon], tt[tt_myspace], ' .
		'tt[tt_myspace_link_text], tt[tt_myspace_title_text], ' .
		'tt[tt_myspace_icon], tt[tt_ping], tt[tt_ping_link_text], ' .
		'tt[tt_ping_title_text], tt[tt_ping_icon], tt[tt_reddit], ' .
		'tt[tt_reddit_link_text], tt[tt_reddit_title_text], ' .
		'tt[tt_reddit_icon], tt[tt_su], tt[tt_su_link_text], ' .
		'tt[tt_su_title_text], tt[tt_su_icon]" />
		<p class="submit" style="padding-top:4px;padding-bottom:0;">
		<input type="submit" name="submit" value="' .
		__('Save Options', 'tweet-this') . '" /> 
		<input type="submit" name="reset" value="' .
		__('Reset Options', 'tweet-this') . '" onclick="return ' .
		'confirm (\'' . __('Are you sure you want to reset Tweet ' .
		'This to its default options?', 'tweet-this') . '\');" /></p>
		<p onclick="expandcontent(\'s3\')" ' .
		'style="cursor:hand;cursor:pointer;"><u><strong>' .
		__('Import / Export Options', 'tweet-this') . '</strong>
		</u></p><div id="s3" class="switchcontent"><p><em>' .
		__('EXPORT', 'tweet-this') . '</em>: ' . __('Excludes your ' .
		'Twitter password. Save the content below to a text file.',
		'tweet-this') . '</p><textarea name="export_content" ' .
		'rows="12" cols="100" onfocus="this.select()" ' .
		'readonly="readonly">' . $export_options . '</textarea>' .
		'<br /><p><em>' . __('IMPORT', 'tweet-this') . '</em>:</p>';

	if(version_compare($GLOBALS['wp_version'], '2.0', '>='))
		echo	'<textarea type="text" name="import_content" ' .
			'rows="12" cols="100" onfocus="this.select()">' .
			__('Paste your options here and click "Import ' .
			'Options." Whatever you paste here will be added ' .
			'as-is to the wp_options table for the ' .
			'tweet_this_options row. Importing options from a ' .
			'previous or future version of Tweet This will ' .
			'cause no harm.', 'tweet-this') . '</textarea>
			<p class="submit" style="padding-top:4px;' .
			'padding-bottom:2px;"><input type="submit" ' .
			'name="import" value="' . __('Import Options',
			'tweet-this') . '" onclick="return confirm (\'' .
			__('Are you sure you want to import these options? ' .
			'Your current options will be overwritten.',
			'tweet-this') . '\');" /></p>';
	else echo	'<p style="width:750px">' . __('Importing options ' .
			'requires WordPress 2.0 or newer. <a href="http://' .
			'codex.wordpress.org/Upgrading_WordPress">Please ' .
			'upgrade</a>. Alternately, open up phpMyAdmin, ' .
			'select your WordPress database, click the ' .
			'"wp_options" table, click "Browse," find the ' .
			'"tweet_this_options" row, click the edit icon, ' .
			'paste your options dump into the "option_value" ' .
			'field, and finally, click "Go."', 'tweet-this') .
			'</p>';
	echo	'</div></form><p>' .
		__('Thanks to <a href="http://blog.assbach.de/2009/01/11/' .
		'freie-tweet-this-buttons/">Sascha Assbach</a> and ' .
		'<a href="http://flickr.com/photos/grahamsblog/2347756292/">' .
		'Graham Smith</a> for the Tweet This buttons.', 'tweet-this') .
		'</p><p>' . __('If you like Tweet This, ' .
		'<a href="http://richardxthripp.thripp.com/donate">make a ' .
		'donation</a> to its development.', 'tweet-this') .
		'</p></div>';


?>