<?php /*

	Postloop Template
	This template holds the code that includes either posts or asides on the homepage.
	It is included by index.php, category.php, date.php and others.
	
*/ ?>
<?php if ( in_category(CAT_ASIDES) ) { ?>

	<?php // Open single asides div-box
	if ($asidebox != true) { ?><div class="box asides"><? } $asidebox = true; ?>
	
		<?php include (TEMPLATEPATH . '/templates/template-aside.php'); ?>

	<?php // Close single asides div-box on single pages or categories
	if (!is_home()) { $asidebox = false; ?></div><?php } ?>
		
<?php } else { ?>

	<?php // Close single asides div-box
	if ($asidebox == true) { ?></div><?php } $asidebox = false; ?>
				
	<?php include (TEMPLATEPATH . '/templates/template-post.php'); ?>

<?php } ?>

<?php // Close last asides div-box
	if ($asidebox == true) { ?></div><?php } $asidebox = false; ?>