<?php if(is_home()) {$first_post = true; } else {$first_post = false; } ?>

<?php while (have_posts()) : the_post(); ?>

	<?php if($first_post) { ?>
	<div id="callout">
		<h3><a href="<?php the_permalink() ?>" class="<?php if(rr_ShowSidebar() == 'off') {echo "noside-callout";} else{ echo "side-callout";}?>" id="post-<?php the_ID(); ?>"><?php the_title(); ?></a> <span>Posted on <?php the_time('F jS') ?></span></h3>
			<span>
	<?php rr_the_excerpt_reloaded(125, '', 'none', FALSE); ?>
				
			</span>
			<span class="blog-post-meta">Tagged as <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> | <a href="<?php the_permalink(); ?>">Continue <img style="vertical-align:bottom; border:none;" src="<?php bloginfo('template_directory');?>/styles/images/link.png" alt="linkarrow"/></a></span>
	</div> 
	
	
	<?php $first_post=false; ?>
	<?php } 
	else { ?> 
		
	

	
	<div class="box">
		<h3><a id="post-<?php the_ID(); ?>" class="<?php if(rr_ShowSidebar() == 'false') { echo "noside-"; } else { echo "side-";}?><?php echo rr_GetBoxStyle(); ?>" href="<?php the_permalink(); ?>">
		<?php the_title(); ?></a></h3>
		
				<span><?php rr_the_excerpt_reloaded(20, '', 'none', FALSE);?> <a href="<?php the_permalink(); ?>">Continue <img style="vertical-align:bottom; border:none; margin:2px;" src="<?php bloginfo('template_directory');?>/styles/images/link.png" alt="linkarrow"/></a></span>
			<div class="extra">
				<?php the_time('F jS') ?> / 0 comments
			</div>		
	</div>

		
	<?php }?>
<?php endwhile; ?> <!-- There are no more posts -->
