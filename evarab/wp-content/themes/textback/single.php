<?php get_header(); ?>
<div id="hello"><div id="content"><div class="month"><h3 class="monthheader">Psalms of Planets</h3>
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
	
                       <!--for paginate posts-->
			<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
	
                               <!--all options if pingbacks on or not, if comments are open or not etc-->
				<div id="under"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></div>
						
					
				</p><!--all options over and out-->
	
		
	<!--include comments template-->
	<?php comments_template(); ?>
	
        <!--do not delete-->
	<?php endwhile; else: ?>
	
	Sorry, no posts matched your criteria.

<!--do not delete-->
<?php endif; ?>
	</div></div></div></div>
	
<!--single.php end-->
<!--include sidebar-->
<?php get_sidebar(); ?>
<!--include footer-->
<?php get_footer(); ?>