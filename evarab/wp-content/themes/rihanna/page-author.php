<?php 
/* 
Template Name: All Authors Template 
*/ 

/* 
All Authors WordPress Page template by Kaf Oseo (http://szub.net/) 

The All Authors Page template is based structurally on the WordPress 
1.5 Default (Kubrick) Page template. It may require changes to layout 
to work with other themes. 

The template is commented throughout to explain the various elements 
and provide detailed *clues* on modifying it. 

A few notes: 

1. My comments are for the most part 'tagged' off above the element 
   described in their own php tags (<?php ... ?>. Most reference the 
   first line of code (mainly an if statement), and then explain it. 
   This should make it easier to remove the comments once everything 
   is as you like it (though you can certainly leave them). 

2. You probably don't want lines displayed for some bit of user info 
   if they don't fill out that field in their profile. This is why 
   much of the code is in PHP *if* statements. So: 

        <?php if($user->user_aim) : ?> 
        AIM screen name: <?php echo $user->user_aim; ?> 
        <br /> 
        <?php endif; ?> 

   tests whether the AIM field has been filled in. If not (i.e. it's 
   empty), it returns a false response, and won't display that line. 

3. The header for each author (their name) is placed in an <h3> and 
   given a class of "author-profile". 

*/ 
?> 
<?php get_header(); ?> 

<div id="content" class="narrowcolumn"> 

    <h2>Author Profiles:</h2> 

    <?php 
    /* 
    Set the $order variable to whatever you want to sort the authors' 
    list on. You can use any users table column name; examples are: 
    ID, user_nickname, user_lastname, user_login, user_email. 
    */ 
    ?> 
    <?php 

    $order = 'user_nickname'; 

    $users = $wpdb->get_results("SELECT * FROM $wpdb->users ORDER BY $order"); // query users 
    foreach($users as $user) : // start users' profile "loop" 
    ?> 

    <?php 
    /* 
    This is the users' profile 'loop'. It displays info from a user's 
    profile for every author, along with a few bits of other author- 
    related data. Feel free to add, subtract, change layout; whatever 
    you'd like. 

    These 'tags' will display "publically accessable" user info; what 
    is displayed by each is hopefully self-explanatory: 

        <?php echo $user->ID; ?> 
        <?php echo $user->user_login; ?> 
        <?php echo $user->user_firstname; ?> 
        <?php echo $user->user_lastname; ?> 
        <?php echo $user->user_nickname; ?> 
        <?php echo $user->user_email; ?> 
        <?php echo $user->user_url; ?> 
        <?php echo $user->user_description; ?> 
        <?php echo $user->user_icq; ?> 
        <?php echo $user->user_aim; ?> 
        <?php echo $user->user_msn; ?> 
        <?php echo $user->user_yim; ?> 
        <?php echo $user->user_level; ?> 
        <?php echo $user->user_nicename; ?> 
        <?php echo $user->user_registered; ?> 
    */ 
    ?> 

    <? 
    /* 
    if(('admin' != $user->user_login) && ($user->user_level > 0)) : 
    This skips the admin account and any user at level 0; to display 
    admin, comment out/remove the if line and then uncomment the one 
    after it. H3 element is assigned the CSS class 'author-profile'. 
    */ 
    ?> 
    <?php 
    if(('admin' != $user->user_login) && ($user->user_level > 0)) : 
    //    if($user->user_level > 0) : 
    ?> 

    <h3 id="user-<?php echo $user->ID; ?>" class="author-profile"><?php echo $user->user_firstname; ?> <?php echo $user->user_lastname; ?></h3> 

    <?php 
    /* 
    Tests for and displays an author image. IMG element is assigned 
    the CSS class 'author-image'. 

    Change $image_dir to directory used to store your author images; 
    start from blog root (example: 'wp-content/images'). Filename is 
    based on user_nicename (ex: user login 'Kaf Oseo' = 'kaf-oseo') 
    and 'jpg' file extension. Change $image_file the $author_image 
    variables if you want to use another user profile element (such 
    as user_nickname) or image type. 
    */ 
    ?> 
    <?php 
    $image_dir = 'images'; 
    $image_file = $user->user_nicename; 
    $image_ext = 'jpg'; 

    $image_dir = trim($image_dir, '/'); 
    $author_image =    get_bloginfo('home') . '/' . $image_dir . '/' . $image_file . '.' . $image_ext; 
    if(@file($author_image)) : 
    ?> 
    <img class="user-image" src="<?php echo $author_image; ?>" alt="<?php echo $user->user_login; ?>" title="<?php echo $user->user_login; ?>" /> 
    <?php endif; ?> 

    <?php 
    /* 
    if($user->user_description) 
    Tests for and displays user description (i.e. Profile). 
    */ 
    ?> 
    <?php if($user->user_description) : ?> 
    <p> 
    <strong>Profile:</strong> 
    <br /> 
    <?php echo $user->user_description; ?> 
    </p> 
    <?php endif; ?> 

    <p> 
    <?php 
    /* 
    if(get_usernumposts($user->ID) > 0) 
    Tests for and displays post count and link to posts for author. 
    */ 
    ?> 
    <?php if(get_usernumposts($user->ID) > 0) : ?> 
    Number of posts: <a href="<?php get_author_link(true, $user->ID, "$user->user_nicename"); ?>"><?php echo get_usernumposts($user->ID); ?></a> 
    <br /> 
    <?php endif; ?> 

    <?php 
    /* 
    if($user->user_email) 
    Tests for and displays email; uses WP's antispambot() function 
    to encode the address so it's displayed in browser but not read 
    by emailbots. 
    */ 
    ?> 
    <?php if($user->user_email) : ?> 
    Email address: 
    <a href="mailto:<?php echo antispambot($user->user_email, 1); ?>"><?php echo antispambot($user->user_email); ?></a> 
    <br /> 
    <?php endif; ?> 

    <?php 
    /* 
    if($user->user_url) 
    Tests for and displays web site. 
    */ 
    ?> 
    <?php if($user->user_url) : ?> 
    Web site: <a href="<?php echo $user->user_url; ?>"><?php echo $user->user_url; ?></a><br /> 
    <br /> 
    <?php endif; ?> 

    <?php 
    /* 
    if($user->user_aim) 
    Tests for and displays AIM screen name. 

    Those that follow do the same for MSN IM ID, Yahoo IM ID, and ICQ 
    number. 
    */ 
    ?> 
    <?php if($user->user_aim) : ?> 
    AIM screen name: <?php echo $user->user_aim; ?> 
    <br /> 
    <?php endif; ?> 

    <?php if($user->user_msn) : ?> 
    MSN ID: <?php echo $user->user_aim; ?> 
    <br /> 
    <?php endif; ?> 

    <?php if($user->user_yim) : ?> 
    Yahoo ID: <?php echo $user->user_aim; ?> 
    <br /> 
    <?php endif; ?> 

    <?php if($user->user_icq) : ?> 
    ICQ number: <?php echo $user->user_aim; ?> 
    <br /> 
    <?php endif; ?> 

    </p> 

    <?php 
    endif; // end of admin and user_level test 
    endforeach; // end of the users' profile 'loop' 
    ?> 

</div> 

<?php get_sidebar(); ?> 

<?php get_footer(); ?>