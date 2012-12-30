<div id="sidebar">

<!--sidebar.php-->

<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar() ) : ?>
	

		<h2>Author</h2>
		<p>A little something about you, the author. Nothing lengthy, just an overview.</p>


<!--recent posts-->

	<h2>Recent Posts</h2>
	<ul>
	<?php get_archives('postbypost', 10); ?>
	</ul>

<!--archives ordered per month-->
		<h2>Archives</h2>
		<ul>
		<?php wp_get_archives('type=monthly'); ?>
		</ul>
			
<!--links or blogroll-->
		<h2>Blogroll</h2>
		<ul><?php get_links(-1, '<li>', '</li>', ' - '); ?></ul>

<!--list of categories, order by name, without children categories, no number of articles per category-->
		<h2>Categories</h2>			
		<ul><?php wp_list_cats('sort_column=name'); ?>
		</ul>

		
				
<!--you will set this only at frontpage or of a static page, login logout, register,validate links, link to wordpress -->

			<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>				

				<h2>Meta</h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
					<?php wp_meta(); ?>
<li><a href="http://rockinthemes.com/free-wordpress-themes/">Free WordPress Themes</a></li>
				</ul>
				<!--end of if-->
			<?php } ?>

<!--searchfield-->
<h2>Search</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			

<!--sidebar.php end-->

<?php endif; ?>

</div>