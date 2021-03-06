<?php get_header(); ?>

<!--search.php-->

        <!--loop-->
	<?php if (have_posts()) : ?>

		Search Results
		
                <!--to create links for the previous entries or the next-->
		<?php next_posts_link('&laquo; Previous Entries') ?>

		<?php previous_posts_link('Next Entries &raquo;') ?>
		

                <!--loop-->
		<?php while (have_posts()) : the_post(); ?>
				
			        <!--permalink of the post title-->
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3>
			
                         <!--post time-->
                          <?php the_time('l, F jS, Y') ?>
		
			<!--show the category, edit link, comments-->
                           Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
			
	        <!--loop-->
		<?php endwhile; ?>

		<!--to create links for the previous entries or the next-->
		<?php next_posts_link('&laquo; Previous Entries') ?>

		<?php previous_posts_link('Next Entries &raquo;') ?>
		
	
        <!--necessary do not delete-->
	<?php else : ?>

		No posts found. Try a different search?
                 <!--include searchform-->
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>
        <!--do not delete-->
	<?php endif; ?>
		
	
<!--search.php end-->
<!--include sidebar-->
<?php get_sidebar(); ?>
<!--include footer-->
<?php get_footer(); ?>