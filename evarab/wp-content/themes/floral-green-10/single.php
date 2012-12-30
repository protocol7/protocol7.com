<?php get_header(); ?>
<?php get_sidebar(); ?>
		
		<!-- Content -->
		<div id="content">
		
			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
			<!-- Post -->
			<div class="post" id="post-<?php the_ID(); ?>">
				<div class="post-title">
					<div class="post-date"><span><?php the_time('d') ?></span><?php the_time('M') ?></div>
					<div class="post-title-right">
						<h2><?php the_title(); ?></h2>
						<div class="post-title-info"><span class="post-title-info-item">In Category</span><?php the_category(', ') ?></div>
						<div class="post-title-info"><span class="post-title-info-item">By</span><span class="post-title-author"><?php the_author() ?></span></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="post-entry">
					<?php the_content('Read more...'); ?>
					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					<?php the_tags('<p>Tags: ', ', ', '</p>'); ?>
					<?php edit_post_link('Edit this entry.','',''); ?>
					<?php comments_template(); ?>
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