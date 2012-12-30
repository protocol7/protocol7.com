<?php get_header(); ?>
	<?php if (have_posts()) : ?>
 <?php while (have_posts()) : the_post(); ?>

	<div class="post">
	<h6><?php the_time('F j, Y') ?> @ <?php the_time() ?></h6>
	
	<h1 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h1>
	
	<div class="postentry"><?php the_content("Read rest of story..."); ?></div>
	
	<p class="postfeedback"><?php _e('Filed under'); ?> <?php the_category(', ') ?> <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>" class="permalink"><?php _e('Permalink'); ?></a> &#183; <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> <?php edit_post_link(__('Edit'), ' &#183; ', ''); ?></p>


	</div>
			
<?php endwhile; ?>
	

<?php else : ?>

	<h2><?php _e('Not Found'); ?></h2>

	<p><?php _e('Sorry, but the page you requested cannot be found.'); ?></p>
		
	<h3><?php _e('Search'); ?></h3>
		
	<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>


