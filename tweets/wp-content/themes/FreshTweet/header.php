<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php if (is_single() || is_page() || is_archive()) { wp_title('',true); } else {  } ?> &#8212; <?php bloginfo('name'); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<!-- Additional IE/Win specific style sheet (Conditional Comments) -->
<!--[if lte IE 7]>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/styleie.css" type="text/css" media="projection, screen">
<![endif]-->

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>

</head>

<body>

<div id="container"><!-- Container -->
	<div id="header"><!-- Header -->
			<div id="logo">
				<h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
			</div>
    	<div id="nav">
			<div id="search">
				<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			</div>
        	<ul>
            	<li><a href="/">Blog</a></li>
                <li><a href="/about/">About</a></li>
                <li><a href="http://twitter.com/protocol7">Twitter</a></li>
            </ul>
        </div>
    <div class="clear"></div>
</div><!-- Header X-->