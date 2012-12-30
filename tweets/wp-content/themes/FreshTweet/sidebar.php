<div id="sidebar"><!-- Sidebar -->

	<div class="side">

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>

		<h2>Recent Posts</h2>
           	<ul>
				<?php query_posts('showposts=10'); ?>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<li><a href="<?php the_permalink() ?>"><?php the_title() ?></a></li>
				<?php endwhile; endif; ?>
           	</ul>
		
		<h2>Categories</h2>
  			<ul>
				<?php wp_list_categories('title_li=0'); ?>
			</ul>
		
		<h2>Archives</h2>
           	<ul>
               	<?php wp_get_archives('type=monthly'); ?>
           	</ul>
			
<?php endif; ?>	

     </div>
            
</div><!-- Sidebar X -->
		
<div class="clear"></div>