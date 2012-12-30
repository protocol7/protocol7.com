<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="content">
		<div class="post" id="post-<?php the_ID(); ?>">
			<h3><?php the_title(); ?></h3>
            <div class="postMeta-post">
            
            
            <span class="post-category"><p><?php the_category(' / ') ?></p></span> 
			<span class="post-tags"><?php the_tags( '<p>Tags: ', ', ', '</p>'); ?></span> 
            <div class="clear"></div>
            
            <span class="date"><?php the_time('F j, Y') ?></span>
            <span class="comments"><a href="#comments" title="Jump to the comments"><?php comments_number('0', '1', '%'); ?></a></span>
            <div class="clear"></div>
           
            </div> 
            
			<?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
 			<p><?php edit_post_link('Edit Post', '', '  '); ?></p>
		</div>
<div id="comments">
<?php comments_template(); ?>
		</div>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>
</div>
<?php get_sidebar(); ?>
 <div class="clear"></div>
<?php get_footer(); ?>
