<?php get_header(); ?>

	<div id="body">

		<div id="main"><div class="inner">
		
			<div class="box">
				<h2><?php _e('Search','fauna'); ?></h2>
				<form method="get" action="<?php echo(get_option('siteurl')); ?>">
				<input name="s" type="text" class="searchbox" value="<?php echo $s; ?>" />
				<input type="submit" value="<?php _e('Search','fauna'); ?>" class="pushbutton" />
				</form>
			</div>

			<div class="box">
				<h2><?php _e('Search Results','fauna'); ?></h2>
				<p><?php printf(__('These are the search results for your search on <strong>"%s"</strong>:','fauna'), $s) ?></p>
			</div>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
				<div class="search-results box">

					<?php edit_post_link(__('Edit This','fauna'), '<span class="edit">', '</span>'); ?>
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent link to %s:', 'fauna'), get_the_title()) ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt() ?>
					<small><?php the_permalink() ?></small>
				
				</div>
			
			<?php endwhile; ?>

				<?php include (TEMPLATEPATH . '/templates/template-nextprev.php'); ?>

			<?php else : ?>

				<?php include (TEMPLATEPATH . '/templates/template-notfound.php'); ?>

			<?php endif; ?>

			<hr />

		</div></div><!--//#main-->

		<?php get_sidebar(); ?>
		
	</div><!--// #body -->

<?php get_footer(); ?>