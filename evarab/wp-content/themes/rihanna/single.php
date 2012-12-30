<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<div class="post">
			<h6><?php the_time('F j, Y') ?> @ <?php the_time() ?></h6>
			<h1 class="posttitle" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h1>
			
			
			<div class="postentry">
			<?php the_content("<p>__('Read the rest of this entry &raquo;')</p>"); ?>
			<?php wp_link_pages(); ?>
			
			</div>
			
	<p class="postfeedback"><?php _e('Filed under'); ?> <?php the_category(', ') ?> <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>" class="permalink"><?php _e('Permalink'); ?></a> <?php edit_post_link(__('Edit'), ' &#183; ', ''); ?></p>
			
	<?php comments_template(); ?>
			
		</div>

		
	<?php endwhile; else : ?>

	<div class="post">
		<h4><?php _e('Not Found'); ?></h4>
<div class="postentry">
		<p><?php _e('Sorry, but the page you requested cannot be found.'); ?></p>
		</div>
		</div>
	<?php endif; ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>


