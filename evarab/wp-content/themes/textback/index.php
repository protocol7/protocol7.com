<?php get_header(); ?>
<div id="hello"><div id="content"><div class="month"><h3 class="monthheader">Have we lost freedom?</h3>
<div id="hello2">
	<!--index.php-->
        <!--the loop-->
	<?php if (have_posts()) : ?>
		<!--the loop-->
		<?php while (have_posts()) : the_post(); ?>
				
			<!--post title as a link-->
				
				
				<!--post text with the read more link-->
					<div id="text"><?php the_content('Read the rest of this entry &raquo;'); ?></div>
				<!--show categories, edit link ,comments-->
		
				<div id="under"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a> | <?php comments_popup_link('0 comments &#187;', '1 comment &#187;', '% comments &#187;'); ?></div>
			
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

	</div></div></div></div>

<!--index.php end-->
<!--include sidebar-->
<?php get_sidebar(); ?>
<!--include footer-->
<?php get_footer(); ?>
</li>