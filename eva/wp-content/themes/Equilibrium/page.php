<?php get_header(); ?>
<div class="content">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<h4><?php the_title(); ?></h4>
			<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
			<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
</div>
<?php get_sidebar(); ?>
 <div class="clear"></div>
<?php get_footer(); ?>
