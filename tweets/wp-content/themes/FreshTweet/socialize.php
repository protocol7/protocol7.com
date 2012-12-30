<?php
/*
Template Name: Socialize
*/
?>

<?php get_header(); ?>

<div class="ctop"></div>
    
<div id="content"><!-- Content -->
		
		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
        
		<h2 class="title"><?php the_title(); ?> <?php edit_post_link('Edit', ' | ', ''); ?></h2>
       		
			<div class="post" id="post-<?php the_ID(); ?>">
     				<?php the_content(); ?>
			</div>
        
	        <div id="cfooter">
				<ul>
					<li><?php comments_rss_link('RSS'); ?></li>
                </ul>
			<div class="clear"></div>
			</div>

<?php comments_template(); ?>

<?php endwhile; endif; ?>

</div><!-- Content X -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>