<?php get_header(); ?>


<div id="content">
<div id="postloop">

	<?php get_sidebar(); ?>

	<?php if (have_posts()) : ?>

	<div id="boxlist">	

		<h3 class="archive">Search - Posts containing '<?php echo wp_specialchars($s); ?>'</h3>

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