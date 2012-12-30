<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' at'; } ?> <?php bloginfo('name'); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />


<!--we need this for plugins-->
<?php wp_head(); ?>
</head>
<body>

<div id="header">
		<h2><a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h2>
<h3><?php bloginfo('description'); ?></h3>
</div>

<div id="menu">
	<ul>
		<li class="page_item"><a href="<?php echo get_settings('home'); ?>">Home</a></li>
		<?php wp_list_pages('title_li=&depth=1'); ?>

<li><a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('template_url'); ?>/images/feed-icon-16x16.gif" alt="Feed" border="0" /></a></li>

	</ul>

	</div>

<div id="container">

<!--header.php end-->