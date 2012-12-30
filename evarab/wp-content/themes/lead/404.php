<?php get_header(); ?>

<div id="leftcol">

<?php if (have_posts()) : ?>
		
		<?php while (have_posts()) : the_post(); ?>
        

	
    <div id="post-<?php the_ID(); ?>">
<h1>404 - Not Found</h1>
<div class="entry">Der Eintrag konnte nicht gefunden werden.</div>

</div>



<?php endwhile; ?>

	
		
	<?php else : ?>

		<h2 class="center">Nicht gefunden</h2>
		<p class="center">Sorry, aber du suchst gerade nach etwas, was hier nicht ist.</p>
		

	<?php endif; ?>
 <div><br/><br/><a href="http://www.amypink.com/legal_notice/">Legal Notice</a> | <a href="http://twitter.com/amyandpink/">Twitter</a> | <a href="http://www.amypink.com/feed/">RSS Feed</a> | &copy; Copyright 2009 by Marcel Winatschek. All Rights Reserved.</div>
    </div>


<?php get_sidebar(); ?>
<!--include footer-->
<?php get_footer(); ?>
