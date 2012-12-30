<?php

// Widget Settings

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Left Navigation',
		'before_widget' => '<li id="%1$s" class="widget %2$s">', 
	'after_widget' => '</li>', 
	'before_title' => '<h2 class="widgettitle">', 
	'after_title' => '</h2>', 
	));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Right Sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">', 
	'after_widget' => '</li>', 
	'before_title' => '<h2 class="widgettitle">', 
	'after_title' => '</h2>', 
	));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Left Footer',
		'before_widget' => '<li id="%1$s" class="widget %2$s">', 
	'after_widget' => '</li>', 
	'before_title' => '<h2 class="widgettitle">', 
	'after_title' => '</h2>', 
	));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Middle Footer',
		'before_widget' => '<li id="%1$s" class="widget %2$s">', 
	'after_widget' => '</li>', 
	'before_title' => '<h2 class="widgettitle">', 
	'after_title' => '</h2>', 
	));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Right Footer',
		'before_widget' => '<li id="%1$s" class="widget %2$s">', 
	'after_widget' => '</li>', 
	'before_title' => '<h2 class="widgettitle">', 
	'after_title' => '</h2>', 
	));

?>