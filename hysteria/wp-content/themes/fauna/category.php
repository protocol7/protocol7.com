<?php get_header(); ?>

	<div id="body">
	
		<div id="main"><div class="inner">

			<div class="box">
				<h2><?php _e('Category Archive','fauna'); ?></h2>
				<p><?php printf(__('The following is a list of all entries from the %s category.','fauna'), single_cat_title('', false)) ?></p>
			</div>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
				<?php include (TEMPLATEPATH . '/templates/template-postloop.php'); ?>

			<?php endwhile; ?>

				<?php include (TEMPLATEPATH . '/templates/template-nextprev.php'); ?>
			
			<?php else: ?>

				<?php include (TEMPLATEPATH . '/templates/template-notfound.php'); ?>

			<?php endif; ?>

		</div></div><!--// #main -->

		<?php get_sidebar(); ?>

	</div><!--// #body -->

<?php get_footer(); ?>