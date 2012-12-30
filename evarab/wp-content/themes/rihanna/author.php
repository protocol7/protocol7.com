<?php get_header(); ?>
<?php 
 if(isset($_GET['author_name'])) :
 $curauth = get_userdatabylogin($author_name);
 else :
 $curauth = get_userdata($author);
 endif;
 ?>

<h4>Profile for <?php echo $curauth->user_firstname; ?> <?php echo $curauth->user_lastname; ?></h4>

<?php get_sidebar(); ?>

<?php get_footer(); ?>


