<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	
	<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style.css" />
	
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style_ie.css" />
	<![endif]-->
	
	<!--[if lt IE 7]>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style_ie6.css" />
	<![endif]-->
	
<?php wp_head(); ?>
</head>

<body>

<!-- Page -->
<div id="page"><div id="page-top"><div id="page-bottom">

	<!-- Header -->
	<div id="header">
	
		<!-- Title -->
		<div id="header-info">
			<h1><a href="<?php echo get_option('home'); ?>/" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
			<div class="description"><?php bloginfo('description'); ?></div>
		</div>
		<!-- /Title -->
		
		<!-- Search -->
		<div id="header-search">
			<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
				<input type="text" value="Search ..." onfocus="if (this.value == 'Search ...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search ...';}" name="s" id="s" />
				<input type="submit" id="searchsubmit" value="" />
			</form>
		</div>
		<!-- /Search -->
	
	</div>
	<!-- /Header -->
	
	<!-- Main -->
	<div id="main">
	