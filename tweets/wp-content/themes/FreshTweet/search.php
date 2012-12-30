<?php get_header(); ?>

<div class="ctop"></div>
    
	<div id="content"><!-- Content -->
        
		<?php if (have_posts()) : ?>
			<h2>Search Results for "<?php echo wp_specialchars($s, 1); ?>"</h2>
<?php while (have_posts()) : the_post(); ?>
		
        	
			<div class="post" id="post-<?php the_ID(); ?>">
     				<?php the_content(); ?>
				<p class="author">Posted at <a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title_attribute(); ?>"><?php the_time('G:i'); ?> on <?php the_time('F j, Y') ?></a></p>
			</div>
        
<?php endwhile; ?>

<?php else : ?>

<div class="post"><!-- Post Starts -->

<h2>No posts found for your search "<?php echo wp_specialchars($s, 1); ?>". Try something else?</h2>

</div><!-- Post Ends -->

<?php endif; ?>

</div><!-- Content X -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>