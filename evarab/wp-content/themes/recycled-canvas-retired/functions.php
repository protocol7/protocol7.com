<?php

include(dirname(__FILE__).'/recycledadmin.php');

recycledadmin(
    'mytheme', /* Make yourself at home :
            * Name of the variable that will contain all the options of
            * your theme admin menu (in the form of an array)
            * Name it according to PHP naming rules (http://php.net/variables) */

    array(     
	'shortsubtitle' => 'Subtitle: {textarea|3|20} ## Short summary, one line or less. Supports basic HTML',	
	'useheader' => 'Header Image: {radio|false|None|default|Default|custom|Custom} ## Place custom headers as images/custom-header.jpg',
    'colorsheet' => 'Color Scheme: {radio|light.css|Light Canvas|dark.css|Dark Canvas} ## ',
    'sidebar' => 'SideBar: {radio|right|Right|left|Left|off|Off} ##',
    'boxstyle' => 'Box Style: {boxstyle|blogview|Blog View|boxview|Box View|imageview|Image View|listview| List View} ## ',  
    ),
    __FILE__     /* Parent. DO NOT MODIFY THIS LINE !
              * This is used to check which file (and thus theme) is calling
              * the function (useful when another theme with a Theme Toolkit
              * was installed before */
);
    

// default options : /* default values upon theme install */ 
if (!$mytheme->is_installed()) { 
	$set_defaults['colorsheet'] = 'light.css'; 
	$set_defaults['shortsubtitle'] = 'A little theme for <b>wordpress</b>, please <b>recycle</b>.';
	$set_defaults['sidebar'] = 'right';
	$set_defaults['useheader'] = 'default';
	
	$trigger = rand(1,4); 
	if($trigger == 1) { $set_defaults['boxstyle'] = 'boxview';} 
	else if($trigger == 2) { $set_defaults['boxstyle'] = 'imageview';} 
	else if($trigger == 3 ) {$set_defaults['boxstyle'] = 'blogview';}
	else {$set_defaults['boxstyle'] = 'listview';}

	$result = $mytheme->store_options($set_defaults); 
}

/* Widgetized Sidebar Support */

if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '',
	    'after_widget' => '',
        'before_title' => '<h4><span>',
        'after_title' => '</span></h4>',
    ));


/* Helper Get Functions */

function rr_GetStyleSheet() {
	global $mytheme;
    return $mytheme->option['colorsheet'];
}

function rr_UseHeader() {
	global $mytheme;
    return $mytheme->option['useheader'];
}

function rr_GetSubtitle() {
	global $mytheme;    
	print $mytheme->option['shortsubtitle'];	
}

function rr_ShowSidebar(){
	global $mytheme;
	return $mytheme->option['sidebar'];
}

function rr_GetBoxStyle() {
	global $mytheme;    
	return $mytheme->option['boxstyle'];
}

function rr_GetView() {
	global $mytheme;    
	return $mytheme->option['boxstyle'];
}


/* Bundled Plugins */

/* The Post Image Plugin, edited slightly */

function rr_szub_post_image($args='') {
    parse_str($args);
    if( !isset($default_image) ) $default_image = get_bloginfo('template_directory') . "/styles/images/esh.jpg";
    if( !isset($use_thumb) ) $use_thumb = true;
    if( !isset($img_tag) ) $img_tag = false;
    if( !isset($css_class) ) $css_class = 'post-image';
    if( !isset($customkey) ) $customkey = 'post-image';
    if( !isset($display) ) $display = false;

    return rr_post_image($default_image, $use_thumb, $img_tag, $css_class, $customkey, $display);
}

function rr_post_image($default_image='', $use_thumb=false, $img_tag=true, $css_class='post-image', $customkey='post-image', $display=true) {
    global $post, $posts, $wp_version, $wpdb;
    global $post_image_attachments;

    if( empty($post) )
        return;

    if( !empty($posts) ) {
        foreach($posts as $apost) {
            if( $posts[0] != $apost )
                $IN_ids .= ',';
            $IN_ids .= (int) $apost->ID;
        }
    }

    if( !empty($default_image) ) {
        $img_url = $default_image;
        $img_title = apply_filters('the_title', $post->post_title);
    }
    $img_url = $default_image;

    $post_custom = get_post_custom($post->ID);
    $meta_value = $post_custom["$customkey"][0];

    if( $meta_value ) {
        $img_url = $meta_value;

        $img_title = apply_filters('the_title', $post->post_title);
    } else {
        if( empty($post_image_attachments) ) {
            $record = ( $wp_version < 2.1 ) ? 'post_status' : 'post_type';
            $post_image_attachments = @$wpdb->get_results("SELECT ID, post_parent, post_title, post_content, guid FROM $wpdb->posts WHERE post_parent IN($IN_ids) AND $record = 'attachment' AND post_mime_type LIKE '%image%' ORDER BY post_date ASC");
        }

        foreach( $post_image_attachments as $attachment ) {
            if( $post->ID == $attachment->post_parent ) {
                    if( !$first_attachment ) {
                        $img_url = $attachment->guid;
                        $img_title = apply_filters('the_title', $attachment->post_title);
                        $first_attachment = 1;

                    }
                $postmarked = strpos(strtolower($attachment->post_title), strtolower($customkey));

                $fileimage = explode('.', basename($attachment->guid));

                if( $postmarked == true || $post->ID == $fileimage[0] || $post->post_name == $fileimage[0] ) {
                    $img_url = $attachment->guid;
                    $img_title = apply_filters('the_title', $attachment->post_title);

                    if($postmarked == true) {
                        $img_title = trim(str_replace($customkey, '', $img_title));
                        break;
                    }
                }
            }
        }
        if( $use_thumb && ($img_url != $default_image) ) {
            $img_url = preg_replace('!(\.[^.]+)?$!', __('.thumbnail') . '$1', $img_url, 1);
		}
    }

    $img_path = ABSPATH . str_replace(get_settings('siteurl'), '', $img_url);

    if( !file_exists($img_path) ) {
        return;
    } else {
        if( $img_tag ) {
            $imagesize = @getimagesize($img_url);
            $image = '<img class="' . $css_class . '" src="' . $img_url . '" ' . $imagesize[3] . ' title="' . $img_title . '" alt="' . $img_title . '" />';
        } else {
            $image = $img_url;
        }
    }
    if( $display )
        echo $image;

    return $image;
}

/* The Excerpt Reloaded, edited slightly */

function rr_wp_the_excerpt_reloaded($args='') {
	parse_str($args);
	if(!isset($excerpt_length)) $excerpt_length = 120; // length of excerpt in words. -1 to display all excerpt/content
	if(!isset($allowedtags)) $allowedtags = '<a>'; // HTML tags allowed in excerpt, 'all' to allow all tags.
	if(!isset($filter_type)) $filter_type = 'none'; // format filter used => 'content', 'excerpt', 'content_rss', 'excerpt_rss', 'none'
	if(!isset($use_more_link)) $use_more_link = 1; // display
	if(!isset($more_link_text)) $more_link_text = "(more...)";
	if(!isset($force_more)) $force_more = 1;
	if(!isset($fakeit)) $fakeit = 1;
	if(!isset($fix_tags)) $fix_tags = 1;
	if(!isset($no_more)) $no_more = 0;
	if(!isset($more_tag)) $more_tag = 'div';
	if(!isset($more_link_title)) $more_link_title = 'Continue reading this entry';
	if(!isset($showdots)) $showdots = 1;

	return rr_the_excerpt_reloaded($excerpt_length, $allowedtags, $filter_type, $use_more_link, $more_link_text, $force_more, $fakeit, $fix_tags, $no_more, $more_tag, $more_link_title, $showdots);
}

function rr_the_excerpt_reloaded($excerpt_length=120, $allowedtags='<a>', $filter_type='none', $use_more_link=true, $more_link_text="(more...)", $force_more=true, $fakeit=1, $fix_tags=true, $no_more=false, $more_tag='div', $more_link_title='Continue reading this entry', $showdots=true) {
	if(preg_match('%^content($|_rss)|^excerpt($|_rss)%', $filter_type)) {
		$filter_type = 'the_' . $filter_type;
	}
	echo rr_get_the_excerpt_reloaded($excerpt_length, $allowedtags, $filter_type, $use_more_link, $more_link_text, $force_more, $fakeit, $no_more, $more_tag, $more_link_title, $showdots);
}

function rr_get_the_excerpt_reloaded($excerpt_length, $allowedtags, $filter_type, $use_more_link, $more_link_text, $force_more, $fakeit, $no_more, $more_tag, $more_link_title, $showdots) {
	global $post;

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_'.COOKIEHASH] != $post->post_password) { // and it doesn't match cookie
			if(is_feed()) { // if this runs in a feed
				$output = __('There is no excerpt because this is a protected post.');
			} else {
	            $output = get_the_password_form();
			}
		}
		return $output;
	}

	if($fakeit == 2) { // force content as excerpt
		$text = $post->post_content;
	} elseif($fakeit == 1) { // content as excerpt, if no excerpt
		$text = (empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;
	} else { // excerpt no matter what
		$text = $post->post_excerpt;
	}
	
	if('all' != $allowed_tags) {
		$text = strip_tags($text, $allowedtags);
	}
	

	if($excerpt_length < 0) {
		$output = $text;
	} else {
		if(!$no_more && strpos($text, '<!--more-->')) {
		    $text = explode('<!--more-->', $text, 2);
			$l = count($text[0]);
			$more_link = 1;
		} else {
			$text = explode(' ', $text);
			if(count($text) > $excerpt_length) {
				$l = $excerpt_length;
				$ellipsis = 1;
			} else {
				$l = count($text);
				$more_link_text = '';
				$ellipsis = 0;
			}
		}
		for ($i=0; $i<$l; $i++)
				$output .= $text[$i] . ' ';
	}



//	$output = str_replace(array("\r\n", "\r", "\n", "  "), " ", $output);

	$output = rtrim($output, "\s\n\t\r\0\x0B");
	$output = ($fix_tags) ? $output : balanceTags($output);
	$output .= ($showdots && $ellipsis) ? '...' : '';

	switch($more_tag) {
		case('div') :
			$tag = 'div';
		break;
		case('span') :
			$tag = 'span';
		break;
		case('p') :
			$tag = 'p';
		break;
		default :
			$tag = 'span';
	}

	if ($use_more_link && $more_link_text) {
		if($force_more) {
			$output .= ' <' . $tag . ' class="more-link"><a href="'. get_permalink($post->ID) . '#more-' . $post->ID .'" title="' . $more_link_title . '">' . $more_link_text . '</a></' . $tag . '>' . "\n";
		} else {
			$output .= ' <' . $tag . ' class="more-link"><a href="'. get_permalink($post->ID) . '" title="' . $more_link_title . '">' . $more_link_text . '</a></' . $tag . '>' . "\n";
		}
	}

	$output = apply_filters($filter_type, $output);

	return $output;
}

/* Weighted Tags, edited slightly */

function rr_weighted_categories($smallest=8, $largest=12, $unit="pt", $exclude='')
{
	$args= array(
	           'show_option_all' => '0',
	           'orderby' => 'name',
			       'order' => 'ASC',
	           'show_last_update' => 0,
	           'style' => 'list',
			       'show_count' => 0, 
	           'hide_empty' => 0, 
	           'use_desc_for_title' => 1,
			       'child_of' => 0, 
	           'feed' => '', 
	           'feed_image' => '', 
	           'exclude' => $exclude,
	           'hierarchical' => true, 
	           'title_li' => __('Categories')
	           );

	$cats = get_categories($args);
	foreach ($cats as $cat)
	{

		$catlink = get_category_link($cat->cat_ID);
		$catname = $cat->cat_name; $count = $cat->category_count;
		if($count > 0) {
			$counts{$catname} = $count;
			$catlinks{$catname} = $catlink;
		}
	}
	
	$spread = max($counts) - min($counts); 
	if ($spread <= 0) { $spread = 1; };
	$fontspread = $largest - $smallest;
	$fontstep = $spread / $fontspread;
	if ($fontstep <= 1) { $fontstep = 1; }	
	
	if ($fontspread <= 0) { $fontspread = 1; }
	foreach ($counts as $catname => $count)
	{
		$catlink = $catlinks{$catname};
		print "<a href=\"$catlink\" title=\"$count entries\" style=\"font-size: ".
		($smallest + ($count/$fontstep))."$unit;\">$catname</a> \n";
	}
}

?>
