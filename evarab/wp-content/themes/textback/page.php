<?php get_header(); ?>
<div id="hello"><div id="content"><div class="month"><h3 class="monthheader">You are delicious.</h3>
<div id="hello2">
<!--page.php-->
    <!--loop-->
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
                 <!--post title-->
			
                              <!--post with more link -->
				<div id="text"><?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?></div>

	                       <!--if you paginate pages-->
	
	<!--end of post and end of loop-->
	  <?php endwhile; endif; ?>

         <!--edit link-->

	
</div></div></div></div>
<!--page.php end-->
<!--include sidebar-->
<?php get_sidebar(); ?>
<!--include footer-->
<?php get_footer(); ?>