
	<div id="sidebar" style="<?php if(rr_ShowSidebar() == 'left' && is_home()) { echo "float:left;"; } ?>">
		<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar() ) : ?>
								
		<?php if(is_home()) {?>		
			<h4><span>About the Blog</span></h4>
			<p><?php echo get_bloginfo ( 'description' );?></p>

		<?php /* If this is a 404 page */ } else if (is_404()) { ?>
		<?php /* If this is a category archive */ } elseif (is_category()) { ?>
			<p>You are browsing the archives for posts tagged as <strong><?php single_cat_title(''); ?></strong>.</p>

		<?php /* If this is a page */ } elseif (is_page()) { ?>
			<?php if(wp_list_pages("child_of=".$post->ID."&echo=0")) { ?>
			<h4><span>Navigation</span></h4> <ul> <?php wp_list_pages("title_li=&child_of=".$post->ID."&sort_column=menu_order&show_date=modified&date_format=$date_format");?> 	</ul> <?php } ?>
					
			
			<?php /* If this is a dayly archive */ } elseif (is_day()) { ?>
			<p>You are browsing the archives for posts written for the day <strong><?php the_time('l, F jS, Y'); ?></strong>.</p>

			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<p>You are browsing the archives for posts written for <strong><?php the_time('F, Y'); ?></strong>.</p>

      <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<p>You are browsing the archives for posts written for the year <strong><?php the_time('Y'); ?></strong>.</p>

		 <?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
			<p>You are searching for posts containing the text <strong>'<?php echo wp_specialchars($s); ?>'</strong>. </p>

			<?php /* If this is a monthly archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<p>You are currently browsing the <a href="<?php echo bloginfo('home'); ?>/"><?php echo bloginfo('name'); ?></a> weblog archives.</p>

			<?php } ?>
		
		
		<?php include (TEMPLATEPATH . "/searchform.php"); ?><br/>
			<?php if(is_single()) { ?>
			<ul>
				<li style=" text-align:justify;"><?php previous_post_link('%link', 'Previous') ?> | 
				<?php next_post_link('%link', 'Next') ?></li>		
			</ul>
			<?php } ?>
			
			<?php if(is_single()) { ?>
					<h4><span>Recent Posts</span></h4>
					<ul>
			<?php wp_get_archives("type=postbypost&limit=10"); ?>
					</ul>
			<?php } ?>
			
			<h4><span>Monthly Archives</span></h4>
				<ul> <?php wp_get_archives('type=monthly'); ?> </ul>
			
		<h4><span>Posts by Tag</span></h4>
			<ul style="line-height:1;"> <li><?php $minfont = 8; $maxfont = 12; $fontunit = "pt"; rr_weighted_categories($minfont, $maxfont, $fontunit); ?></li> </ul>



		<?php /* If this is the frontpage */ if ( is_home() ) { ?>
		<h4><span>Meta</span></h4>
				<ul>
					<li><?php wp_register('', ''); ?></li>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
					<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
					<?php wp_meta(); ?>
				</ul>
								<?php } ?>


		
		
		<?php endif; ?>
	</div>
	
