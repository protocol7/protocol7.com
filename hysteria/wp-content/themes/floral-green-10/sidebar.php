		<!-- Sidebar -->
		<div id="sidebar">
		
			<div class="sidebar-box"><div class="sidebar-box-top"><div class="sidebar-box-bottom">
				<h3 class="first">Pages</h3>
				<ul>
					<?php wp_list_pages('title_li='); ?>
				</ul>
			</div></div></div>
			
			<div class="sidebar-box"><div class="sidebar-box-top"><div class="sidebar-box-bottom">
				<h3>Categories</h3>
				<ul>
					<?php wp_list_categories('title_li='); ?>
				</ul>
			</div></div></div>
			
			<div class="sidebar-box-feed">
				<a href="<?php bloginfo('rss_url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/sidebar_feed.jpg" alt="RSS Feed" width="200" height="56" /></a>
			</div>
		
			
			<div class="sidebar-box"><div class="sidebar-box-top"><div class="sidebar-box-bottom">
				<h3>Archives</h3>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</div></div></div>
			
			<div class="sidebar-box"><div class="sidebar-box-top"><div class="sidebar-box-bottom">
				<h3>Blog Roll</h3>
				<ul>
					<?php wp_list_bookmarks('categorize=0&title_li='); ?>
				</ul>
			</div></div></div>
			
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
			
			<?php endif; ?>
		
		</div>
		<!-- /Sidebar -->