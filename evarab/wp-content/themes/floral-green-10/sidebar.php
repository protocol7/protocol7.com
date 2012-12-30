		<!-- Sidebar -->
		<div id="sidebar">
		
			<div class="sidebar-box-feed">
				<a href="<?php bloginfo('rss_url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/sidebar_feed.jpg" alt="RSS Feed" width="200" height="56" /></a>
			</div>
			
					
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
			
			<?php endif; ?>
		
		</div>
		<!-- /Sidebar -->