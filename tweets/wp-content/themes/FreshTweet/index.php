<?php get_header(); ?>

<div class="ctop"></div>
    
	<div id="content"><!-- Content -->
        
		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		       		
			<div class="post" id="post-<?php the_ID(); ?>">
     				<?php the_content(); ?>
				<p class="author">Posted at <a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title_attribute(); ?>"><?php the_time('G:i'); ?> on <?php the_time('F j, Y') ?></a></p>
			</div>

<?php endwhile; endif; ?>
	
	        <div id="cfooter">
				<ul>
                    <li class="alt"><?php posts_nav_link('&nbsp; | &nbsp;', __('&laquo; Newer'), __('Older &raquo;')); ?></li>
                </ul>
			</div>

</div><!-- Content Ends -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>