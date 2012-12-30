<?php get_header(); ?>

<div id="content">
	<?php get_sidebar(); ?>
	
	<div id="singlepost">					
  	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
		<div class="post" id="post-<?php the_ID(); ?>">								
			<h3><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h3>

			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

				<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
				
				<a href="<?php trackback_url(true); ?>" rel="trackback">Trackback URL</a> <?php edit_post_link('Edit this entry.', ' | ', ''); ?>	
			</div>
						
			<div class="navigation">
				<div class="alignleft"><?php previous_post_link('&#8592; %link') ?></div>
				<div class="alignright"><?php next_post_link('%link &#8594;') ?></div>					
			</div>
			<div class="clear"></div>	

		</div>
		<div class="clear"></div>						
		
	<?php endwhile; ?>	
	<?php endif; ?>
		
	</div>
	<?php get_footer(); ?>
</div>

