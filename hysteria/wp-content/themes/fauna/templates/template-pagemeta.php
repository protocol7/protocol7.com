<?php /*

	Pagemeta Template
	This template holds meta information for custom pages. 
	It is included by sidebar.php.

*/ ?>
<?php
$currentpage = $post->ID;
$parent = 1;

while($parent) {
	$subpages = $wpdb->get_row("SELECT ID, post_name, post_parent FROM $wpdb->posts WHERE ID = '$currentpage'");
	$parent = $currentpage = $subpages->post_parent;
}
$parent_id = $subpages->ID;
$haschildren = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_parent = '$parent_id'"); 
$parent_title = $wpdb->get_var("SELECT post_title FROM $wpdb->posts WHERE ID = '$parent_id'");
?>

<?php rewind_posts(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<h4>
<?php global $post; 
echo '<a href="' . apply_filters( 'the_permalink', get_permalink($parent_id) ) . '">' . apply_filters( 'the_title', $parent_title ) . '</a>';
?>
</h4>

<?php endwhile; endif; ?>
<?php rewind_posts(); ?>

<?php if($haschildren) { ?>
	<ul>
		<?php wp_list_pages('sort_column=menu_order&title_li=&child_of='. $parent_id); ?>
	</ul>
<?php } ?>

<?php include (TEMPLATEPATH . '/templates/template-commentmeta.php'); ?>

<?php rewind_posts(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php if (get_post_custom_values('sidebar') != "") { ?>
	<?php echo c2c_get_custom('sidebar'); ?>
<?php } ?>

<?php endwhile; endif; ?>
<?php rewind_posts(); ?>