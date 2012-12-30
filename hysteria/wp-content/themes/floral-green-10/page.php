<?php get_header(); ?>
<?php get_sidebar(); ?>
		
		<!-- Content -->
		<div id="content">
		
			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
			<!-- Post -->
			<div class="post" id="post-<?php the_ID(); ?>">
				<div class="post-title">
					<h2><?php the_title(); ?></h2>
				</div>
				<div class="post-entry">
					<?php the_content('Read <span>more...</span>'); ?>
					<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
				</div>
			</div>
			<!-- /Post -->
			<?php endwhile; ?>
			<?php else : ?>
			<!-- Post -->
			<div class="post">
				<div class="post-title">
					<h2>Not Found</h2>
				</div>
				<div class="post-entry">
					<p>Sorry, but you are looking for something that isn't here.</p>
				</div>
			</div>
			<!-- /Post -->
			<?php endif; ?>
			
			<div class="clear"></div>
		
		</div>
		<!-- /Content -->
		
<?php get_footer(); ?>