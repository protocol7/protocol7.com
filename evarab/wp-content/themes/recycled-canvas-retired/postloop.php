<div id="boxlist">
	<?php if (have_posts()) : ?>
	
	<?php if(rr_GetView() != "") {
			include (TEMPLATEPATH . '/views/' . rr_GetView() . '.php'); 
		  }
		  else {
			include (TEMPLATEPATH . '/views/boxview.php'); 
		  }
	?>
					
	
	<div style="clear:left;"></div>		
	
	<div class="navigation"><br/>		
		<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries') ?></div>
		<div class="alignright"><?php previous_posts_link('Next Entries &raquo;') ?></div>
	</div>
	<?php else : ?> <!-- Else we found no posts at all. -->
	<?php endif; ?>
</div>			





	