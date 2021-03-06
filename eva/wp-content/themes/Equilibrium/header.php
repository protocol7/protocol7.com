<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

	<head profile="http://gmpg.org/xfn/11">
		
		<title>
			<?php if (is_home()) { echo bloginfo('name');
			} elseif (is_404()) {
			echo '404 Not Found';
			} elseif (is_category()) {
			echo 'Category:'; wp_title('');
			} elseif (is_search()) {
			echo 'Search Results';
			} elseif ( is_day() || is_month() || is_year() ) {
			echo 'Archives:'; wp_title('');
			} else {
			echo wp_title('');
			}
			?>
		</title>

	    <meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
		<meta name="description" content="<?php bloginfo('description') ?>" />
		<?php if(is_search()) { ?>
		<meta name="robots" content="noindex, nofollow" /> 
	    <?php }?>
        
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
        <!--[if IE 6]>
        <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/ie6.css" media="screen" />
        <![endif]-->
         <!--[if IE 7]>
        <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/ie7.css" media="screen" />
        <![endif]-->
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
		<?php wp_head(); ?>

	</head>

	<body>
	<div id="page-wrap">
    <div id="top">
		<h1 class="logo"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<ul id="nav">
		<?php wp_list_pages('title_li='); ?>
		</ul>
        </div>
    <div class="clear"></div>