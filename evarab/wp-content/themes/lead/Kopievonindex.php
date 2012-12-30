<?php get_header(); ?>

<div id="leftcol">

<?php if (have_posts()) : ?>
		
		<?php while (have_posts()) : the_post(); ?>
        

	
    <div id="post-<?php the_ID(); ?>">
<h1><?php the_title(); ?></h1>
<div class="entry"><?php the_content('Den ganzen Beitrag lesen &#187;'); ?></div>

</div>

<div style="padding-bottom:30px;"><p class="postmetadata"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permalink zu <?php the_title(); ?>"><?php the_time('j. F Y') ?></a> | <?php comments_popup_link('0 Kommentare', '1 Kommentar', '% Kommentare'); ?></p></div>

<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Vorherige Eintr&auml;ge') ?></div>
			<div class="alignright"><?php previous_posts_link('N&auml;chste Eintr&auml;ge &raquo;') ?></div>
		</div>
		
	<?php else : ?>

		<h2 class="center">Nicht gefunden</h2>
		<p class="center">Sorry, aber du suchst gerade nach etwas, was hier nicht ist.</p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>
    <div><br/><br/><a href="http://www.amypink.com/legal_notice/">Legal Notice</a> | <a href="http://www.amypink.com/feed/">RSS Feed</a> | &copy; Copyright 2002 - 2008 by Marcel Winatschek. All Rights Reserved.</div>
    </div>

<?php get_sidebar(); ?>
<!--include footer-->
<?php get_footer(); ?>
