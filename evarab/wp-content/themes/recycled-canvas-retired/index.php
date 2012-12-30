<?php get_header(); ?>

<div id="content" style="<?php if(rr_ShowSidebar() == 'off') { echo 'margin:0 auto; width:750px;';} ?>">
	<?php if(rr_ShowSidebar() != 'off') { get_sidebar(); } ?>
	
	<div id="postloop" style="<?php if(rr_ShowSidebar() == 'left' && is_home()) { echo "float:left;"; }?>">					  	  
		<?php  include (TEMPLATEPATH . '/postloop.php'); ?>
	</div>
	<div class="clear"></div>		
		 
</div>

<?php get_footer(); ?>
