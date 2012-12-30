<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>

<div id="content">
	<?php get_sidebar(); ?>
	
	<div id="singlepost">					
  	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
		<div class="post" id="post-<?php the_ID(); ?>">								
			<h3><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h3>

			<div class="entry">
				<div id="archivepage">
				<h2 style="float:left;">Archives by Time</h2>
				  <ul style="list-style:none; float:left; padding:0;">
				    <?php wp_get_archives('type=monthly'); ?>
				  </ul>
				<h2 style="float:left;">Archives by Tag</h2>
				  <ul style="list-style:none; padding:0;float:left;">
				     <?php wp_list_cats(); ?>
				  </ul>
				</div>
			</div>
						


		</div>
		<div class="clear"></div>						
		
	<?php endwhile; ?>	
	<?php endif; ?>
		
	</div>
	<?php get_footer(); ?>
</div>


