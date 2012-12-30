<div id="sidebar">

<!--sidebar.php-->

<!--searchfield-->
<!--<h2>Search</h2>-->
<?php include (TEMPLATEPATH . '/searchform.php'); ?>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

<!--
<div align="left"><a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('template_url'); ?>/images/feed-icon.gif" alt="Feed" border="0" /></a></div>
<br />
//-->

		

		<h2>About</h2>
		<p>A short detail about your blog, the author, description & more. Nothing lengthy, just an overview.</p>


<!--recent posts-->

	<h2>Recent Posts</h2>
	<ul>
	<?php get_archives('postbypost', 10); ?></ul>

<!--archives ordered per month-->
		<h2>Archives</h2>
		<ul>
		<?php wp_get_archives('type=monthly'); ?></ul>
			
<!--links or blogroll-->
<!--
		<h2>Blogroll</h2>
		<ul><li><a href="http://www.wpthemesfree.com/" title="Wordpress Themes">Wordpress Themes</a></li>
			<?php get_links(-1, '<li>', '</li>', ' - '); ?></ul>
-->
<!--list of categories, order by name, without children categories, no number of articles per category-->
		<h2>Categories</h2>			
		<ul><?php wp_list_cats('sort_column=name'); ?></ul>

		
				
<!--you will set this only at frontpage or of a static page, login logout, register,validate links, link to wordpress -->

	

				<h2>Meta</h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?></ul>
				<!--end of if-->
			


<!--sidebar.php end-->

<?php endif; ?>

</div>