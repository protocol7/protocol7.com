<?php /*

	Sidenotes Template
	This template holds all the sidenotes. 
	It is included by index.php.

*/ ?>
<a class="feedicon" href="<?php echo get_category_rss_link(0, CAT_SIDENOTES, $post->cat_name); ?>" title="<?php _e('Syndicate Sidenotes using RSS 2.0','fauna') ?>">
<img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon_feed.gif" alt="<?php _e('Feed','fauna'); ?>" /></a>
<?php rewind_posts(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<?php include (TEMPLATEPATH . '/templates/template-sidenote.php'); ?>
	 
<?php endwhile; endif; ?>
<?php rewind_posts(); query_posts("showposts=".$posts_per_page); /* Confused? Don't be. This line just resets the hack above so all is normal again. */ ?>