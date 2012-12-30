<?php get_header(); ?>

<div class="ctop"></div>
    
<div id="content"><!-- Content Starts -->

<?php if (have_posts()) : ?>
<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

<?php /* If this is a category archive */ if (is_category()) { ?>				
	<h2 class="title">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>
		
<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
	<h2 class="title">Archive for <?php the_time('F jS, Y'); ?></h2>
		
<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
	<h2 class="title">Archive for <?php the_time('F, Y'); ?></h2>

<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
	<h2 class="title">Archive for <?php the_time('Y'); ?></h2>
		
<?php /* If this is a search */ } elseif (is_search()) { ?>
	<h2 class="title">Search Results</h2>
		
<?php /* If this is an author archive */ } elseif (is_author()) { ?>
	<h2 class="title">Author Archive</h2>

<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
	<h2 class="title">Blog Archive</h2>

<?php /* If this is a tag archive */ } elseif (is_tag()) { ?>
	<h2 class="title">Archive for the Tag &#8216;<?php single_tag_title(); ?>&#8217;</h2>

<?php } ?>

<?php while (have_posts()) : the_post(); ?>
<br />

	<div class="post" id="post-<?php the_ID(); ?>">
     				<?php the_content(); ?>
				<p class="author">Posted at <a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title_attribute(); ?>"><?php the_time('G:i'); ?> on <?php the_time('F j, Y') ?></a></p>
	</div>

<?php endwhile; endif; ?>

	        <div id="cfooter">
				<ul>
                    <li class="alt"><?php posts_nav_link('&nbsp; | &nbsp;', __('&laquo; Newer'), __('Older &raquo;')); ?></li>
                </ul>
			</div>

</div><!-- Content Ends -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>