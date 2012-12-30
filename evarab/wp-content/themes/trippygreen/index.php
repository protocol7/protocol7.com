
<?php get_header(); ?>	
	<div id="wrapper">
	
		<div id="content-wrapper">
		
			<div id="content">
			
			
			
				<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>

		
				<div class="post-wrapper">
				

					<div class="date">
						<span class="month"><?php the_time('M') ?></span>
						<span class="day"><?php the_time('j') ?></span>
					</div>

					<div style="float: right; width: 510px; clear: right; margin-top: 10px; margin-bottom: 15px; padding-top: 10px;  margin-left: 5px;">
			<span class="titles"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></span>
			<div><img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon1.gif" alt="icon1" /> <?php the_author() ?> | <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon2.gif" alt="icon2" /> <?php the_category(', ') ?> | <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon4.gif" alt="icon4" /> <?php the_time('m jS, Y') ?><strong>|</strong> <?php edit_post_link('Edit','','<strong>|</strong>'); ?> <img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon3.gif" alt="icon3" /><?php comments_popup_link('No Comments &raquo;', '1 Comment &raquo;', '% Comments &raquo;'); ?></div>
</div>
<div style="clear: both;"></div>

			<div class="post">

			<?php the_content('Read the rest of this entry &raquo;'); ?>

			</div>
			
			</div>
<img src="<?php bloginfo('stylesheet_directory'); ?>/images/post-bg2.gif" style="margin-left: -20px;margin-bottom: 25px;margin-top: 0px; float: left;" />
			<?php comments_template(); ?>

			<?php endwhile; ?>

			   <p class="pagination"><?php next_posts_link('&laquo; Previous Entries') ?> <?php previous_posts_link('Next Entries &raquo;') ?></p>

			<?php else : ?>

			<h2 align="center">Not Found</h2>

			<p align="center">Sorry, but you are looking for something that isn't here.</p>

			<?php endif; ?>
			
			
	
			</div>
		
		</div>
		<?php get_sidebar(); ?>    
		<?php get_footer(); ?>   
	
</body>
</html>