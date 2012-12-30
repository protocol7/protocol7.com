<?php /*

	Sidenote Post Template
	If Sidenotes are used, this template will be used to layout them.
	
*/ ?>
<div class="sidenote post">

	<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link:','fauna'); ?> <?php the_title(); ?>"><?php the_title(); ?></a> <?php comments_popup_link(__('(Comment)','fauna'), __('(1 Comment)','fauna'), __('(% Comments)','fauna'), ('sidenote-permalink'), ('')); ?></h2>
	<?php edit_post_link(__('Edit This','fauna'), '<span class="edit">', '</span>'); ?>
	<?php the_content(__('Continue reading this entry','fauna').' &raquo;'); ?>
	<?php wp_link_pages('before=<strong>'.__('Page:','fauna').' &after=</strong>&next_or_number=number&pagelink=%'); ?>

	<!--
	<?php trackback_rdf(); ?>
	-->

</div>