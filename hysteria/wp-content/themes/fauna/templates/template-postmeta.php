<?php /*

	Postmeta Template
	This template holds meta information for posts. 
	It is included by sidebar.php.

*/ ?>
<h4><?php _e('This Entry','fauna'); ?></h4>
<a class="feedicon" href="<?php echo get_post_comments_feed_link(); ?>" title="<?php _e('Syndicate Sidenotes using RSS 2.0','fauna') ?>">
<img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon_feed.gif" alt="<?php _e('Feed','fauna'); ?>" /></a>


<?php rewind_posts(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
	<p><?php printf(__('This entry was written %1$s %2$s by %3$s.', 'fauna'), get_the_time(), the_date(null, null, null, false), get_the_author_posts_link()); ?></p>

	<p><?php printf(__('Categorized: %1$s.', 'fauna'), get_the_category_list(', ')); ?>
	<?php if (function_exists('the_tags')) { ?> <?php the_tags(__('Tagged:','fauna'), ", ", ""); ?><?php } ?></p>

<?php endwhile; endif; ?>
<?php rewind_posts(); ?>

<?php include (TEMPLATEPATH . '/templates/template-commentmeta.php'); ?>