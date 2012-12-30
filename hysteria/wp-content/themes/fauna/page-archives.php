<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>

	<div id="body">

		<div id="main"><div class="inner">

			<div class="box">
			
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
				<?php the_content(__('Continue reading this entry','fauna').' &raquo;'); ?>
				
				<?php wp_link_pages('before=<strong>'.__('Page:','fauna').' &after=</strong>&next_or_number=number&pagelink=%'); ?>
				
				<?php edit_post_link(__('Edit This','fauna'), '<span class="edit">', '</span>'); ?>
				
				<!--
				<?php trackback_rdf(); ?>
				-->
			
				<hr />
				
				<?php endwhile; else: ?><?php endif; ?>
						
				<?php if (function_exists('af_ela_super_archive')) { ?>
				<?php af_ela_super_archive(); ?>
				<?php } ?>
			
				<?php if (!function_exists('af_ela_super_archive') && function_exists('the_tags')) { ?>
				<h2><?php _e('Weighted Tags','fauna'); ?></h2>
				<p><?php wp_tag_cloud('smallest=8&largest=24&number=50'); ?></p>
				<?php } ?>

				<div class="column-left">
					<h2><?php _e('Last 20 Entries','fauna'); ?></h2>
					<ul>
						<?php wp_get_archives('type=postbypost&limit=20'); ?> 
					</ul>
				</div>
				
				<?php if (!function_exists('af_ela_super_archive')) { ?>
				<div class="column-right">
					<h2><?php _e('Archives by Month','fauna'); ?></h2>
					<ul>
						<? wp_get_archives('type=monthly&show_post_count=true'); ?>
					</ul>
				</div>
				<?php } ?>
				
			</div>
			
			<hr />

		</div></div><!--// #main -->

		<?php get_sidebar(); ?>

	</div><!--// #body -->

<?php get_footer(); ?>
