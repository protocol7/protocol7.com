<?php if(is_home()) {$first_post = true; } else {$first_post = false; } ?>


<?php while (have_posts()) : the_post(); ?>

<?php if($first_post) { ?>
	<?php $imageurl = rr_szub_post_image('use_thumb=0'); ?>
	
<div id="image-callout">
	<div id="caption"><h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> <span>Posted on <?php the_time('F jS') ?></span></h3></div>
		<div id="frame"><img alt="Callout Image" src="<?php echo $imageurl; ?>" /></div>

		<span><?php rr_the_excerpt_reloaded(35, '', 'none', FALSE);?></span>
</div> 


<?php $first_post=false; ?>
<?php } else { ?>
	<?php $imageurl = rr_szub_post_image(); ?>
	
<div class="imagebox" id="post-<?php the_ID(); ?>">
	<h3><a  class="<?php if(rr_ShowSidebar() == 'false') { echo "noside-"; } else { echo "side-";}?><?php echo rr_GetBoxStyle(); ?>" href="<?php the_permalink(); ?>">
	<?php the_title(); ?></a></h3>
	
			<span><img src="<?php echo $imageurl;?>" />
			<p><?php rr_the_excerpt_reloaded(10, '', 'none', FALSE);?></p></span>
	
</div>
<?php } ?>
<?php endwhile; ?> <!-- There are no more posts -->
