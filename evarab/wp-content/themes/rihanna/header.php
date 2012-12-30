<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php bloginfo('name'); ?></title>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<style type="text/css" media="screen">
	@import url( <?php bloginfo('stylesheet_url'); ?> );
</style>


<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" /><?php wp_get_archives('type=monthly&format=link'); ?>

<?php wp_head(); ?>
	
</head>

<body>
<div id="headerwrap">
	<div id="headercolumn1"><h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1></div>
	<div id="headercolumn2"></div>
	<div id="headercolumn3"><?php include (TEMPLATEPATH . '/searchform.php'); ?></div>
</div>

<div id="wrapper">
	<div id="column1">

	<h3>Recent News</h3>
	<ul><?php wp_get_archives('type=postbypost&limit=5'); ?></ul>
	
	<h3>Archives</h3>
 <ul>
  <?php wp_get_archives('type=monthly'); ?>
 </ul>
	
	<h3>Flickr Goodness</h3>
	<div id="flickr-photos">
<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=4&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=78519944@N00"></script>
	</div>

	</div>

	<div id="content">