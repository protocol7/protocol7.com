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
						<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<div class="post-title-info"><span class="post-title-info-item">In Category</span><?php the_category(', ') ?></div>
						<div class="post-title-info"><span class="post-title-info-item">By</span><span class="post-title-author"><?php the_author() ?></span></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="post-entry">
					<?php the_content('Read more...'); ?>
				</div>
				<div class="post-info">
					<?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="<?php the_permalink() ?>#more-<?php the_ID(); ?>">Read More</a>
				</div>
			</div>
			<!-- /Post -->
			<?php endwhile; ?>
			<!-- Navigation -->
			<div class="navigation">
				<div class="navigation-previous"><?php next_posts_link('&laquo; Previous Entries') ?></div>
				<div class="navigation-next"><?php previous_posts_link('Next Entries &raquo;') ?></div>
			</div>
			<!-- /Navigation -->
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