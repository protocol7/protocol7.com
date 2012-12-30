<?php
/*

	Custom Functions
	This page holds various functions that extend Wordpress' core functionality. 
	If you like to hack themes instead of use the options page, you can throw out
	both functions.php and themetoolkit.php and still keep this file to retain this core
	functionality.

*/

/* 

	Scott Reilly's "Get Custom Fields" plugin
	Thanks Scott! (http://www.coffee2code.com/)

	Copyright (c) 2004-2005 by Scott Reilly (aka coffee2code)
	
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
	files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
	modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
	Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
	OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
	LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
	IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

if (!isset($wpdb->posts)) {	// For WP 1.2 compatibility
	global $tableposts, $tablepostmeta;
	$wpdb->posts = $tableposts;
	$wpdb->postmeta = $tablepostmeta;
}

// This works inside "the loop"
function c2c_get_custom ($field, $before='', $after='', $none='', $between='', $before_last='') {
	return c2c__format_custom($field, (array)get_post_custom_values($field), $before, $after, $none, $between, $before_last);
} //end c2c_get_custom()

// This works outside "the loop"
function c2c_get_recent_custom ($field, $before='', $after='', $none='', $between=', ', $before_last='', $limit=1, $unique=false, $order='DESC', $include_static=true, $show_pass_post=false) {
	global $wpdb;
	if (empty($between)) $limit = 1;
	if ($order != 'ASC') $order = 'DESC';
	$now = current_time('mysql');

	$sql = "SELECT ";
	if ($unique) $sql .= "DISTINCT ";
	$sql .= "meta_value FROM $wpdb->posts AS posts, $wpdb->postmeta AS postmeta ";
	$sql .= "WHERE posts.ID = postmeta.post_id AND postmeta.meta_key = '$field' ";
	$sql .= "AND ( posts.post_status = 'publish' ";
	if ($include_static) $sql .= " OR posts.post_status = 'static' ";
	$sql .= " ) AND posts.post_date < '$now' ";
	if (!$show_pass_post) $sql .= "AND posts.post_password = '' ";
	$sql .= "AND postmeta.meta_value != '' ";
	$sql .= "ORDER BY posts.post_date $order LIMIT $limit";
	$results = array(); $values = array();
	$results = $wpdb->get_results($sql);
	if (!empty($results))
		foreach ($results as $result) { $values[] = $result->meta_value; };
	return c2c__format_custom($field, $values, $before, $after, $none, $between, $before_last);
} //end c2c_get_recent_custom()

/* Helper function */
function c2c__format_custom ($field, $meta_values, $before='', $after='', $none='', $between='', $before_last='') {
	global $field;
	$values = array();
	if (empty($between)) $meta_values = array_slice($meta_values,0,1);
	if (!empty($meta_values))
		foreach ($meta_values as $meta) {
			$meta = apply_filters("the_meta_$field", $meta);
			$values[] = apply_filters('the_meta', $meta);
		}

	if (empty($values)) $value = '';
	else {
		$values = array_map('trim', $values);
		if (empty($before_last)) $value = implode($values, $between);
		else {
			switch ($size = sizeof($values)) {
				case 1:
					$value = $values[0];
					break;
				case 2:
					$value = $values[0] . $before_last . $values[1];
					break;
				default:
					$value = implode(array_slice($values,0,$size-1), $between) . $before_last . $values[$size-1];
			}
		}
	}
	if (empty($value)) {
		if (empty($none)) return;
		$value = $none;
	}
	return $before . $value . $after;
} //end c2c__format_custom()

// Some filters you may wish to perform: (these are filters typically done to 'the_content' (post content))
add_filter('the_meta', 'convert_chars');

// Other optional filters (you would need to obtain and activate these plugins before trying to use these)
if (function_exists('do_textile')) { 			add_filter('the_meta', 'do_textile', 6); }
else if (class_exists('Textile2_New')) {
	$t = new Textile2_New;
	add_filter('the_meta', array(&$t, 'do_textile'), 6); }
else if (function_exists('textile')) { 		add_filter('the_meta', 'textile', 6); }
else if (function_exists('Markdown')) { 		add_filter('the_meta', 'Markdown', 6); }
else { 
	add_filter('the_meta', 'wptexturize');
	add_filter('the_meta', 'wpautop');
}

/*

	Fauna "Noteworthy Link"
	
*/
function noteworthy_link($id, $link = FALSE, $separator = '/', $nicename = FALSE) {
$chain = '';
	$parent = &get_category($id);
if ($nicename) {
	$name = $parent->category_nicename;
} else {
	$name = $parent->cat_name;
}
if ($parent->category_parent) $chain .= get_category_parents($parent->category_parent, $link, $separator, $nicename);
if ($link) {
	if (get_option('fauna_noteworthy') != '') {
		$icon = get_option('fauna_noteworthy');
	} else {
		$icon = "&hearts;";
	}
	$chain .= '<a href="' . get_category_link($parent->cat_ID) . '" title="' . sprintf(__('View all posts in %s','fauna'), $parent->cat_name) . '">' . urldecode ( $icon ) .'</a>' . $separator;
} else {
	$chain .= $name.$separator;
}
return $chain;
}

/*

	Obfuscate email addresses 

*/
function email($email) {
	if (strpos($email, "@")) {
	$email=str_replace("@"," [at] ",$email);
	$email=str_replace("."," [dot] ",$email);
	}
	return $email;
}

/*

	This is needed to be able to localize strings, where "the_authors_post_link()" is needed.
	
*/
function get_the_author_posts_link($idmode='') {
	global $id, $authordata;
	return '<a href="' . get_author_link(0, $authordata->ID, $authordata->user_nicename) . '" title="' . sprintf(__("Posts by %s"), htmlspecialchars(the_author($idmode, false))) . '">' . the_author($idmode, false) . '</a>';
}

/*

	Fauna global variables
	
*/
if (version_compare(get_bloginfo('version'), '2.2', '>')) {
	define('CAT_ASIDES', $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE slug = 'asides' OR slug = 'dailies'"));
	define('CAT_SIDENOTES', $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE slug = 'sidenotes'"));
	define('CAT_NOTEWORTHY', $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE slug = 'noteworthy'"));
} else {
	define('CAT_ASIDES', $wpdb->get_var("SELECT cat_ID FROM $wpdb->terms WHERE category_nicename = 'asides' OR  category_nicename = 'dailies'"));
	define('CAT_SIDENOTES', $wpdb->get_var("SELECT cat_ID FROM $wpdb->terms WHERE category_nicename = 'sidenotes'"));
	define('CAT_NOTEWORTHY', $wpdb->get_var("SELECT cat_ID FROM $wpdb->terms WHERE category_nicename = 'noteworthy'"));
}
define('TOTAL_POSTS', $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish'"));

/*

	"is_index" Conditional Extension
	if is_index() returns true, it means you're on the root of your server.
	
*/
function is_index() {
	if ( $_SERVER["REQUEST_URI"] == "/" || $_SERVER["REQUEST_URI"] == "/index.php") {
		return true;
	} else {
		return false;
	}
}


?>