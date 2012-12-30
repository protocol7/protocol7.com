<?php /*

	Next/Prev Template
	This template shows "next posts" and "previous posts" links used by a variety of pages.
	
*/ ?>
<?php if ($wp_query->post_count >= $posts_per_page || is_paged()) { ?>
<div class="box-blank">
    <div class="prev"><? posts_nav_link('','',__('Previous Entries', 'fauna')) ?></div>
    <div class="next"><? posts_nav_link('',__('Next Entries', 'fauna'),'') ?></div>
</div>
<?php } ?>