<?php get_header(); ?>

<div class="ctop"></div>
    
	<div id="content"><!-- Content -->
        
		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		
        	<h2 class="title"><?php the_title(); ?> <?php edit_post_link('Edit', ' | ', ''); ?></h2>
       		
			<div class="post" id="post-<?php the_ID(); ?>">
     				<?php the_content(); ?>
			</div>
        
	        <div id="cfooter">
				<ul>
					<li><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to <?php bloginfo('name'); ?> RSS feed">RSS</a></li>
                </ul>
			<div class="clear"></div>
			</div>

<?php endwhile; endif; ?>

</div><!-- Content X -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>