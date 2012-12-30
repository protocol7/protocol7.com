<?php get_header(); ?>

	<!--the loop-->

		<?php if (have_posts()) : ?>

		 <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
<?php /* If this is a category archive */ if (is_category()) { ?>				
		<?php echo single_cat_title(); ?>
		
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		Archive for <?php the_time('F jS, Y'); ?>
		
	 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		Archive for <?php the_time('F, Y'); ?>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		Archive for <?php the_time('Y'); ?>
		
	  <?php /* If this is a search */ } elseif (is_search()) { ?>
		Search Results
		
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		Author Archive

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		Blog Archives

               <!--do not delete-->
		<?php } ?>


		<!-- navigation-->
               <?php next_posts_link('&laquo; Previous Entries') ?>
		<?php previous_posts_link('Next Entries &raquo;') ?>
		
                <!--loop article begin-->
		<?php while (have_posts()) : the_post(); ?>
		                <!--post title as a link-->
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3>
                                <!--post time-->
				<?php the_time('l, F jS, Y') ?>
				
				<!--optional excerpt or automatic excerpt of the post does not show images-->
				<?php the_excerpt(); ?>
				
		                <!--tags between html p tag posted in categories, edit link, comments-->
		                <?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
		                
				Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>> 

			
	       <!--one post end-->
		<?php endwhile; ?>
                
               <!-- navigation-->
               <?php next_posts_link('&laquo; Previous Entries') ?>
		<?php previous_posts_link('Next Entries &raquo;') ?>
	<!-- do not delete-->
	<?php else : ?>

		Not Found
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

         <!--do not delete-->
	<?php endif; ?>
		
	
<!--archive.php end-->

<!--include sidebar-->
<?php get_sidebar(); ?>
<!--include footer-->
<?php get_footer(); ?>
