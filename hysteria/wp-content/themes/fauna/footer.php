</div><!-- //#wrapper -->

<div id="footer">

<?php /*

Dear Wordpress User,

As you can see a lot of effort, work and time (which includes years of learning) have gone into this wordpress template. I'm proud that you like the design enough to want it on your blog.

Please respect the designer's work by not removing the links from the bottom of the page (footer.php). These links are the only thing encouraging further development of wordpress templates.

Thank you very much :)

*/ ?>

	<p>&copy; <?php echo gmdate(__('Y')); ?> <?php bloginfo('name'); ?>. Design by <a href="http://www.noscope.com/fauna/">Joen Assmusen</a> for <?php wp_footer(); ?><?php if(is_user_logged_in()) { ?> &nbsp; &nbsp; <a href="<?php echo get_option('siteurl'); ?>/wp-admin/"><?php _e('Site Admin', 'fauna'); ?></a><?php } ?></p>
	<!-- Visual theme based on Fauna for Wordpress, http://www.noscope.com/fauna/ -->

	<p class="feeds">
		<a class="feedicon" href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate Entries using RSS 2.0','fauna') ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon_feed.gif" alt="<?php _e('Feed','fauna'); ?>" /> <?php _e('Entries','fauna') ?></a> &nbsp; &nbsp;
		<a class="feedicon" href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('Syndicate Comments using RSS 2.0','fauna') ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon_feed.gif" alt="<?php _e('Feed','fauna'); ?>" /> <?php _e('Comments','fauna') ?></a>
	</p>

</div>

</body>
</html>