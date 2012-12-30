<ul>
	<?php while (have_posts()) : the_post(); ?>
	<div class="list" class="style-<?php echo rr_GetBoxStyle();?>">
		<li><h3><a id="post-<?php the_ID(); ?>" class="<?php if(rr_ShowSidebar() == 'false') { echo "noside-"; } else { echo "side-";}?><?php echo rr_GetBoxStyle(); ?>" href="<?php the_permalink(); ?>">
		<?php the_title(); ?></a><span><?php the_time('F jS') ?> / <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></span>
		</h3>		
				<span><?php rr_the_excerpt_reloaded(25, '', 'none', FALSE);?> <a href="<?php the_permalink(); ?>">Continue <img style="vertical-align:bottom; border:none;" src="<?php bloginfo('template_directory');?>/styles/images/link.png" alt="linkarrow"/></a></span>
			
	</li>
	</div>
	<?php endwhile; ?> <!-- There are no more posts -->

	

</ul>