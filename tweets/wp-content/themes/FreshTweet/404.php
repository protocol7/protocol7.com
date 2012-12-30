<?php get_header(); ?>

<div class="ctop"></div>

	<div id="content"><!-- Content -->
        	<h2 class="title">Unlike life, we have a back button for this error page.</h2>
		<div class="post">
     			<p>Look to your left, good. Now look to your right, great. Now you can visit our <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>">home page</a> and hopefully you won't have to see this page anymore.</p>
			</div>
	        <div id="cfooter">
				<ul>
					<li><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to <?php bloginfo('name'); ?> RSS feed">RSS</a></li>
                </ul>
			<div class="clear"></div>
			</div>
    </div><!-- Content X -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>