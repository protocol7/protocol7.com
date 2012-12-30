		<div id="sidebar">
        <ul>
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

		<li><h2>Search</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?><div class="clear"></div></li>

		<?php wp_list_categories('show_count=&title_li=<h2>Categories</h2>'); ?>

		<li><h2>Latest Posts</h2>
				<ul>
				<?php query_posts('showposts=4'); ?>
				<?php while (have_posts()) : the_post(); ?>
				<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</li>
				<?php endwhile; ?>
				</ul>
			</li>

		<li><h2>Recent Comments</h2>
			<ul id="recent_comments">
				<?php
				global $wpdb;
				$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID,
				SUBSTRING(comment_content,1,80) AS com_excerpt
				FROM $wpdb->comments
				LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
				$wpdb->posts.ID)
				WHERE comment_approved = '1' AND comment_type = '' AND
				post_password = ''
				ORDER BY comment_date DESC
				LIMIT 4";
				$comments = $wpdb->get_results($sql);
				$output = $pre_HTML;
				foreach ($comments as $comment) {
				$output .= "\n<li><a href=\"" . get_permalink($comment->ID) .
				"#comment-" . $comment->comment_ID . "\" title=\"on " .
				$comment->post_title . "\">" .strip_tags($comment->com_excerpt).
				"...</a>";
				}
				$output .= $post_HTML;
				echo $output;?>
			</ul> <!-- END -->
			</li>
	
			<?php endif; ?>
		</ul>
        </div>