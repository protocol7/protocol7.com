
<?php get_header(); ?>	
	<div id="wrapper">
	
		<div id="content-wrapper">
		
			<div id="content">
			
						
				<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>

		
				<div class="post-wrapper">

			<h3 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3>


			<div class="post">

			<?php the_content('Read the rest of this entry &raquo;'); ?>

			</div>
			
			
			</div>


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