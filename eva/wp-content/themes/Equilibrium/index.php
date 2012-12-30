<?php get_header(); ?>

<div id="featured"> 
<h2>Featured</h2>
	 <!-- Edit Below -->
	<?php query_posts('cat=ID&showposts=2'); ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="front-post">
    <div class="featured-post">
        <div class="featured-title">
            <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?>
</a></h2>
        </div>
        <div class="featured-image">
            <?php image_attachment('image', 303, 231); ?>
        </div>
    </div> <!-- END Featured-post -->
    <div class="postMeta-featured"><span class="date"><?php the_time('F j, Y') ?></span><span class="comments"><?php comments_popup_link('0', '1', '%'); ?></span></div> <div class="clear"></div>
     <div class="featured-content">
	 <?php the_excerpt(); ?>
	<p class="moretext"><a href="<?php the_permalink() ?>">Continue Reading...</a></p>	
    </div> <!-- END Featured-Content -->
   	</div> 
    <?php endwhile; ?>
    <!-- Edit Below 2 -->
    <?php query_posts('cat=ID&showposts=1&offset=2'); ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="front-post-last"> <!-- Featured-Last -->
    <div class="featured-post">
        <div class="featured-title">
            <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?>
</a></h2>
        </div>
        <div class="featured-image">
            <?php image_attachment('image', 303, 231); ?>
        </div>
    </div> <!-- END Featured-post -->
    <div class="postMeta-featured"><span class="date"><?php the_time('F j, Y') ?></span><span class="comments"><?php comments_popup_link('0', '1', '%'); ?></span></div> <div class="clear"></div>
     <div class="featured-content">
	 <?php the_excerpt(); ?>
	<p class="moretext"><a href="<?php the_permalink() ?>">Continue Reading...</a></p>	
    </div> <!-- END Featured-Content -->
    
   	</div> 
    <?php endwhile; ?>
</div> <!-- END Featured -->
<div class="clear"></div>
<div id="front-bottom">
<div id="latest-wrap">
<h2>Latest Posts</h2>
<div class="content">
<!-- Edit Below 3 --> 
<?php query_posts('cat=-ID&showposts=8'); ?>
				<?php while (have_posts()) : the_post(); ?>
    <div class="latest-post-wrap">
    <div class="latest-post">
                <div class="latest-title">
            <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?>
</a></h2>
        </div>
        <div class="latest-image">
            <?php image_attachment('image', 162, 118); ?>
        </div>
        </div>
             <div class="latest-content">
             <div class="postMeta-front"><span class="date"><?php the_time('F j, Y') ?></span><span class="comments"><?php comments_popup_link('0', '1', '%'); ?></span></div> <div class="clear"></div>
				<p><?php
  $excerpt = get_the_excerpt();
  echo string_limit_words($excerpt,30);
?></p>
<p class="moretext"><a href="<?php the_permalink() ?>">Continue Reading...</a></p>	
    </div> 
        </div>
				<?php endwhile; ?> <!-- END -->
</div>
 <div class="clear"></div>
 </div>
<?php get_sidebar(); ?>
 <div class="clear"></div>
 </div>
<?php get_footer(); ?>