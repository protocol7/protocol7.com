<?php get_header(); ?>
<div class="content">
		<?php if (have_posts()) : ?>

		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php /* If this is a category archive */ if (is_category()) { ?>
		<h2>Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>
		<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2>Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2>Archive for <?php the_time('F jS, Y'); ?></h2>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2>Archive for <?php the_time('F, Y'); ?></h2>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2>Archive for <?php the_time('Y'); ?></h2>
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2>Author Archive</h2>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2>Blog Archives</h2>
		<?php } ?>

		<?php while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<h3><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
            <div class="postMeta-post">
            
            
            <span class="post-category"><p><?php the_category(' / ') ?></p></span> 
			<span class="post-tags"><?php the_tags( '<p>Tags: ', ', ', '</p>'); ?></span> 
            <div class="clear"></div>
            
            <span class="date"><?php the_time('F j, Y') ?></span>
            <span class="comments"><?php comments_popup_link('0', '1', '%'); ?></a></span>
            <div class="clear"></div>
            

            </div> 
            
<?php the_excerpt(); ?>
<p class="moretext"><a href="<?php the_permalink() ?>">Continue Reading...</a></p>	
 			<p><?php edit_post_link('Edit Post', '', '  '); ?></p>
            <p><?php next_post_link('%link', 'Next post'); ?></p>
			<p><?php previous_post_link('%link', 'Previous post'); ?></p>
		</div>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>
</div>
<?php get_sidebar(); ?>
 <div class="clear"></div>
<?php get_footer(); ?>
