<?php get_header(); ?>

<div id="leftcol">

<?php if (have_posts()) : ?>
		
		<?php while (have_posts()) : the_post(); ?>
        

	
    <div id="post-<?php the_ID(); ?>">
<h1><?php the_title(); ?></h1>
<div class="entry"><?php the_content('Den ganzen Beitrag lesen &#187;'); ?></div>

</div>

<div style="padding-bottom:30px;"><p class="postmetadata"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permalink zu <?php the_title(); ?>"><?php the_time('j. F Y') ?></a></p></div>

<?php comments_template(); ?>

<?php endwhile; ?>

		
		
	<?php else : ?>

		<h2 class="center">Nicht gefunden</h2>
		<p class="center">Sorry, aber du suchst gerade nach etwas, was hier nicht ist.</p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>
  <div><br/><br/>Design by <a href="http://www.amypink.com/">AMY&amp;PINK</a>.</div>
    </div>

<?php get_sidebar(); ?>
<!--include footer-->
<?php get_footer(); ?>

