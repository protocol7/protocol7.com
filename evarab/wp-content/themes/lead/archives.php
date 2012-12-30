<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>



<?php include (TEMPLATEPATH . '/searchform.php'); ?>

Archives by Month:
  <ul>
    <?php wp_get_archives('type=monthly'); ?>
  </ul>

Archives by Subject:
  <ul>
     <?php wp_list_cats(); ?>
  </ul>

	

<?php get_footer(); ?>
