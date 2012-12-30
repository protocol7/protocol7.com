<?php get_header(); ?>

<div id="content">

<div id="contentleft">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<h1><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	
	<p class="date"><b>Skrevs den </b> | <?php the_time('F j, Y'); ?> | <?php comments_popup_link('Ingen har sagt något :-(', '1 kommentar', '% kommentarer'); ?></p>
	  
	<?php the_content(__('Read more'));?><div style="clear:both;"></div>
<a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="evarab">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
	<div class="bt-links"><strong>Kategori:</strong> <?php the_category(', ') ?><br /><?php the_tags('<strong>Taggat som:</strong> ',' > '); ?></div>
	
	<!--
	<?php trackback_rdf(); ?>
	-->
	
	<h3>Kommentarer</h3>
	<?php comments_template(); // Get wp-comments.php template ?>
	
	<?php endwhile; else: ?>
	
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php endif; ?>

	</div>
	
<?php include(TEMPLATEPATH."/l_sidebar.php");?>

<?php include(TEMPLATEPATH."/r_sidebar.php");?>

</div>

<!-- The main column ends  -->

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20326272-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<?php get_footer(); ?>