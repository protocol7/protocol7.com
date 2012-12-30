<?php get_header(); ?>


<div id="content">
<div id="postloop">

	
	<?php get_sidebar(); ?>
	

	<?php if (have_posts()) : ?>



	
	<div id="boxlist">	

		<h3 class="archive">Archives -
		<?php /* If this is a category archive */ if (is_category()) { ?>
		Posts tagged as '<?php single_cat_title(''); ?>'

		<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
		Posts written in <?php the_time('l, F jS, Y'); ?>

		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		Posts written in <?php the_time('F, Y'); ?>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		Posts written in <?php the_time('Y'); ?>

	 	<?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
		<p>You have searched the 
		<a href="<?php echo bloginfo('home'); ?>/"><?php echo bloginfo('name'); ?></a> 
		weblog archives for <strong>'<?php echo wp_specialchars($s); ?>'</strong>. 
		If you are unable to find anything in these search results, you can try one of these links.</p>
		<?php } ?>
		</h3>
				

		<?php include 'views/' . rr_GetView() . '.php'; ?>
		

		</div>	
		<div style="clear:left;"></div>		

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Next Entries &raquo;') ?></div>
		</div>
		<?php else : ?> <!-- Else we found no posts at all. -->

		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>