<?php get_header(); ?>

	<?php if (have_posts()) : ?>
	
		<h4><?php _e('Search Results'); ?></h4>
		
		<?php while (have_posts()) : the_post(); ?>
		
			<div class="post">
	
				<h1 class="posttitle" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h1>
			
				<p class="postmeta"> 
				<?php the_time('F j, Y') ?> @ <?php the_time() ?> 
				&#183; <?php _e('Filed under'); ?> <?php the_category(', ') ?>
				<?php edit_post_link(__('Edit'), ' &#183; ', ''); ?>
				</p>
			
				<div class="postentry">
				<?php the_excerpt() ?>
				</div>
			
				<p class="postfeedback">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>" class="permalink"><?php _e('Permalink'); ?></a>
				<?php comments_popup_link(__('Comments'), __('Comments (1)'), __('Comments (%)'), 'commentslink', __('Comments off')); ?>
				</p>
				
				<!--
				<?php trackback_rdf(); ?>
				-->
			
			</div>
				
		<?php endwhile; ?>

		<?php posts_nav_link(' &#183; ', __('Next entries &raquo;'), __('&laquo; Previous entries')); ?>
		
	<?php else : ?>

		<h2><?php _e('Not Found'); ?></h2>

		<p><?php _e('Sorry, but no posts matched your criteria.'); ?></p>
		
		<h3><?php _e('Search'); ?></h3>
		
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
