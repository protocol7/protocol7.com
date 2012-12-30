<?php while (have_posts()) : the_post(); ?>
<div class="blog-post">
	<h3><a id="post-<?php the_ID(); ?>" class="<?php if(rr_ShowSidebar() == 'false') { echo "noside-"; } else { echo "side-";}?><?php echo rr_GetBoxStyle(); ?>" href="<?php the_permalink(); ?>">
	<?php the_title(); ?></a><span>Posted <?php the_time('F jS') ?></span>
	</h3>		
			<span class="entry"><?php rr_the_excerpt_reloaded(300, '<a><p><img><blockquote><li><ol><ul><div>', 'none', FALSE);?></span>
				<span class="blog-post-meta">Tagged as <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> | <a href="<?php the_permalink(); ?>">Continue <img style="vertical-align:bottom;border:none;" src="<?php bloginfo('template_directory');?>/styles/images/link.png" alt="linkarrow"/></a></span>
		
</div>
<?php endwhile; ?> <!-- There are no more posts -->