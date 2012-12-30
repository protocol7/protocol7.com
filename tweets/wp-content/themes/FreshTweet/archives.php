<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>

<div class="ctop"></div>
    
	<div id="content"><!-- Content -->

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="post"><!-- Post Starts -->

<h2>Monthly Archives</h2>
	<ul><?php wp_get_archives('type=monthly'); ?></ul>


<h2>Category Archives</h2>
	<ul><?php wp_list_categories('title_li='); ?></ul>

<?php endwhile; endif; ?>

</div><!-- Post Ends -->

</div>
<!-- Content Ends -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>