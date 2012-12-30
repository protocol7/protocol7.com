<?php /*

	Asides Post Template
	If Asides are used, this template will be used for their layout. 
	It is included by template-postloop.php.
	
*/ ?>
<div class="aside post">

	<?php edit_post_link(__('Edit This','fauna'), '<span class="edit">', '</span>'); ?>
	
	<?php /* Show entry date on everything but permalinks */ if (!is_single()) { ?>
	<p class="commentlink"><?php comments_popup_link(__('Comments (0)','fauna'), __('Comments (1)','fauna'), __('Comments (%)','fauna'), '', ''); ?></p>
	<?php } ?>

	<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link:','fauna') ?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
	<?php the_content(__('Continue reading this entry','fauna').' &raquo;'); ?>
	<?php wp_link_pages('before=<strong>'.__('Page:','fauna'). '&after=</strong>&next_or_number=number&pagelink=%'); ?>

	<!--
	<?php trackback_rdf(); ?>
	-->

</div>