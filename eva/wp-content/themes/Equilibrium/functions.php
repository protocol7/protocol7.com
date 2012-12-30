<?php
if ( function_exists('register_sidebar') ) {
   register_sidebar(array(
       'before_widget' => '<li id="%1$s" class="widget %2$s">',
       'after_widget' => '</li>',
       'before_title' => '<h2 class="widgettitle">',
       'after_title' => '</h2>',
   ));
}

function image_attachment($key, $width, $height) {
	global $post;
	$custom_field = get_post_meta($post->ID, $key, true);

	if($custom_field) { //if the user set a custom field
		echo '<img class="lead_image" src="'.$custom_field.'" alt="" width="'.$width.'" height="'.$height.'" />';
	}
	else { //else, return
		return;
	}
}

function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}

add_filter('comments_template', 'legacy_comments');

function legacy_comments($file) {

	if(!function_exists('wp_list_comments')) : // WP 2.7-only check
		$file = TEMPLATEPATH . '/legacy.comments.php';
	endif;

	return $file;
}

?>
<?php 
function eq_comment($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment; ?>

<div class="commentwrap">

<div>
				<ul class="commentmetadata">
				<li><?php echo get_avatar( get_comment_author_email(), '50' ); ?></li>
				<li><?php comment_author_link() ?></li>
				<li><?php comment_date('M j, Y, G:i') ?></li>
                <li><?php edit_comment_link('Edit','',''); ?></li>
                <li><?php echo comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth']));  ?></li>
			</ul>
</div>            
<?php if ($comment->comment_approved == '0') : ?>

<em>Your comment is awaiting moderation.</em>

<?php endif; ?>

<div class="commentpost"><?php comment_text() ?></div>

<div class="clear"></div>
<li <?php comment_class(); ?> id="comment-<?php comment_ID( ); ?>"></li>
</div>
<?php } ?>