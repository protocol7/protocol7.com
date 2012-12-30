<?php get_header(); ?>

<div class="ctop"></div>
    
	<div id="content"><!-- Content -->

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<h2 class="title"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		
		<div class="post" id="post-<?php the_ID(); ?>">
		
				<!-- gallerycode -->
				<p class="attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></p>
				
                <div class="caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?></div>
				
				<!-- gallerycode -->
				
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
				
				<!-- gallerynavigation -->
				<div class="imgnav">
					<div class="imgleft"><?php previous_image_link() ?></div>
					<div class="imgright"><?php next_image_link() ?></div>
				</div>
				<br clear="all" />
				<!-- gallerynavigation -->
<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

</div>

<div class="clear"></div>

<?php comments_template(); ?>

<?php endwhile; ?>

<?php endif; ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
