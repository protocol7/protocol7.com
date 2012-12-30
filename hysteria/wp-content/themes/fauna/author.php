<?php get_header(); ?>

	<div id="body">

		<div id="main"><div class="inner">

			<div class="box">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<?php if ($about_text != true) { /* This shows the "about text" only once */ ?>

				<h2><?php printf(__('About %s','fauna'), get_the_author()) ?></h2>
				<? $author_nicename = $wpdb->get_var("SELECT user_nicename FROM $wpdb->users WHERE ID = " . $author); ?>

				<?php
				if(isset($_GET['author_name'])) :
				$curauth = get_userdatabylogin($author_name);
				else :
				$curauth = get_userdata(intval($author));
				endif;
				?>

				<p><?php echo $curauth->description; ?></p>
				
				<ul>
					<li><?php _e('Full name:','fauna') ?> <?php echo $curauth->first_name; ?> <?php echo $curauth->last_name; ?></li>
					<?php if($curauth->user_url) { ?><li><?php _e('Web site:','fauna') ?> <a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></li><?php } ?>
					<?php if($curauth->aim) { ?><li><?php _e('Contact via AIM (AOL Instant Messenger):','fauna') ?> <?php echo email($curauth->aim); ?></li><?php } ?>
					<?php if($curauth->yim) { ?><li><?php _e('Contact via YIM (Yahoo Instant Messenger):','fauna') ?> <?php echo email($curauth->yim); ?></li><?php } ?>
					<?php if($curauth->jabber) { ?><li><?php _e('Contact via Jabber / Google Talk:','fauna') ?> <?php echo email($curauth->jabber); ?></li><?php } ?>
				</ul>

				<h2><?php printf(__("Entries Authored by %s",'fauna'), get_the_author()) ?></h2>

				<p><?php printf(__('You can follow entries authored by %1$s via an <a href="%2$s" title="RSS 2.0">author-only RSS feed</a>.','fauna'), get_the_author(), get_author_rss_link(0, $author, $author_nicename)) ?></p>
				<p><?php printf(__('%1$s has authored %2$s entries on this weblog:','fauna'), get_the_author(), get_the_author_posts()) ?></p>

				<ul><?php } $about_text = true; ?>
					<li><a href="<?php the_permalink() ?>" title="<?php _e('Permanent Link:','fauna') ?> <?php the_title(); ?>"><?php the_title(); ?></a></li>
					<?php endwhile; ?>
				</ul>

				<hr />

			</div>

				<?php include (TEMPLATEPATH . '/templates/template-nextprev.php'); ?>

			<?php else : ?>

				<?php include (TEMPLATEPATH . '/templates/template-notfound.php'); ?>

			<?php endif; ?>

			<hr />

		</div></div><!--// #main -->

		<?php get_sidebar(); ?>

	</div><!--// #body -->

<?php get_footer(); ?>