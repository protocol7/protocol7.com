<?php /*

	Category list Template
	This template displays a list of categories and special categories. 
	It is included by sidebar.php.

*/ ?>
<h4><?php _e('Categories','fauna'); ?></h4>
<ul>
<?php $feed = get_bloginfo('stylesheet_directory') . "/images/icon_feed.gif";
wp_list_categories("feed=".__('Syndicate using RSS 2.0','fauna')."&children=1&hide_empty=0&sort_column=name&title_li=0&show_count=1&optioncount=1&feed_image=".$feed."&exclude=".CAT_SIDENOTES.",".CAT_ASIDES.",".CAT_NOTEWORTHY.",".get_option('default_link_category')." '") ?>
</ul>

<?php if (CAT_NOTEWORTHY != "" || CAT_SIDENOTES != "") { ?>
<h4><?php _e('Special Categories','fauna'); ?></h4>
<ul>
<?php $feed = get_bloginfo('stylesheet_directory') . "/images/icon_feed.gif";
wp_list_categories("feed=".__('Syndicate using RSS 2.0','fauna')."&children=1&hide_empty=0&sort_column=name&title_li=0&show_count=1&optioncount=1&feed_image=".$feed."&include=".CAT_SIDENOTES.",".CAT_ASIDES.",".CAT_NOTEWORTHY.",".get_option('default_link_category')." '") ?>
</ul>
<?php } ?>