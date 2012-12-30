<?php get_header(); ?>

	<div id="body">

		<div id="main"><div class="inner">
		
		<?php if (get_option('fauna_note') != '') { ?>
				<div class="notice"><?php fauna_info('note'); ?></div>
			<?php } ?>

			<?php /* Don't count Sidenotes in the main post loop */
			if (CAT_SIDENOTES != "") {
				query_posts('cat=-'.CAT_SIDENOTES.'&paged='.$paged);
			}
			?>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
				<?php include (TEMPLATEPATH . '/templates/template-postloop.php'); ?>
		
			<?php endwhile; ?>
			
				<?php include (TEMPLATEPATH . '/templates/template-nextprev.php'); ?>

			<?php else : ?>
			
				<?php include (TEMPLATEPATH . '/templates/template-notfound.php'); ?>
			
			<?php endif; ?>
			
		</div></div><!--// #main -->

		<?php get_sidebar(); ?>

	</div><!--// #body -->

<?php get_footer(); ?>