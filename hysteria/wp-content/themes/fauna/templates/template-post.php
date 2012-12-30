<?php /*

	Post Template
	This template holds the layout of the main type of post. 
	It is included by template-postloop.php.

*/ ?>
<div class="box post">

	<?php edit_post_link(__('Edit This','fauna'), '<span class="edit">', '</span>'); ?>
	
	<?php /* Show commentlink on everything but permalinks */ if (!is_single() && comments_open() || !is_single() && $comments) { ?>
	<p class="commentlink"><?php comments_popup_link(__('Comments (0)','fauna'), __('Comments (1)','fauna'), __('Comments (%)','fauna'), '', ''); ?></p>
	<?php } ?>
	
	<?php /* Noteworthy link */ if ( in_category(CAT_NOTEWORTHY) ) { ?><div class="noteworthy"><?php echo(noteworthy_link(CAT_NOTEWORTHY,TRUE,'')); ?></div><?php } ?>
	
	<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link:','fauna') ?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>

	<?php if ( !is_single() ) {?>
	<small class="entry-meta">

		<?php _e('Filed in','fauna'); ?> <?php the_category(','); ?>,

		<?php if (function_exists('relativeDate')) { relativeDate(get_the_time('YmdHis')); } else if (function_exists('time_since')) { printf(__('%s ago','fauna'), time_since(abs(strtotime($post->post_date_gmt . " GMT")), time())); } else { the_date(); ?>, <? the_time(); } ?>
		
		<?php if (get_option('fauna_author') != '0') { _e('by','fauna'); ?> <?php the_author_posts_link();	} ?>

		<?php if (function_exists('the_tags')) { ?><?php the_tags("<span class='tags'>", ", ", "</span>"); ?><?php } ?>
		
	</small>
	<?php } ?>
	
	<?php if (!is_single() && !is_home()) {
		the_excerpt(); 
	} else { 
		the_content(__('Continue reading this entry','fauna').' &raquo;');
	} ?>
	
	<?php wp_link_pages('before=<strong>'.__('Page:','fauna').' &after=</strong>&next_or_number=number&pagelink=%'); ?>

	<!--
	<?php trackback_rdf(); ?>
	-->

	<hr />

</div>
