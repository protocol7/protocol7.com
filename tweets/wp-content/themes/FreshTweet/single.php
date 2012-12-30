<?php get_header(); ?>

<div class="ctop"></div>
    
	<div id="content"><!-- Content -->
        
		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
       		
			<div class="post" id="post-<?php the_ID(); ?>">

     				<?php the_content(); ?>
				<p class="author">Posted @ <?php the_time('g:i A'); ?> on <?php the_time('F j, Y') ?></p>
			</div>

<?php endwhile; endif; ?>

</div><!-- Content X -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>