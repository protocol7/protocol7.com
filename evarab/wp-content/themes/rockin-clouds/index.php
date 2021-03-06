<?php get_header(); ?>
<div id="content">
	<!--index.php-->
        <!--the loop-->
	<?php if (have_posts()) : ?>
		<!--the loop-->
		<?php while (have_posts()) : the_post(); ?>
				
			<!--post title as a link-->
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
	<p><b>By <?php the_author(); ?></b> | <?php the_time('F j, Y'); ?> </p>
		<div class="postspace2">
	</div>	
				<!--post text with the read more link-->
					<?php the_content('Read the rest of this entry &raquo;'); ?>
				<!--show categories, edit link ,comments-->
		
				<p><b>Topics:</b> <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
	<div class="postspace">
	</div>		
	        <!--end of one post-->
		<?php endwhile; ?>

		<!--navigation-->
                <?php next_posts_link('&laquo; Previous Entries') ?>
		<?php previous_posts_link('Next Entries &raquo;') ?>
		
	<!--do not delete-->
	<?php else : ?>

		Not Found
		Sorry, but you are looking for something that isn't here.
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>
        <!--do not delete-->
	<?php endif; ?>

	

<!--index.php end-->
</div>

<!--include sidebar-->
<?php get_sidebar(); ?>
<!--include footer-->
<?php get_footer(); ?>
