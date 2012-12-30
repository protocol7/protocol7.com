<?php get_header(); ?>

	<div id="content">
	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
		
		<div class="post">
			<h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
			<small><b>Skrivet:</b> <?php the_time('F jS, Y') ?> | <b>Av:</b> <?php the_author_posts_link(); ?> | <b>Kategori:</b> <?php the_category(', ') ?> <?php the_tags(' | <b>Tags:</b> ', ', ', ''); ?> <?php if ( $user_ID ) : 
			?> | <b>Modify:</b> <?php edit_post_link(); ?> <?php endif; ?>| <?php comments_popup_link('Inga kommentarer &#187;', '1 kommentar &#187;', '% kommentarer&#187;'); ?></small>
			<?php the_content('Read the rest of this entry &raquo;'); ?>
			 <hr/>
		</div>
		
		<?php comments_template(); ?>
		
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Äldre inlägg') ?></div>
			<div class="alignright"><?php previous_posts_link('Nyare inlägg &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>

	<?php endif; ?>

	</div>
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>