<?php
/*
Plugin Name: VH - Related Posts
Plugin URI: http://www.visionhouse.pro
Description: Show Related Posts
Author: Andy Nguyen
Version: 1.0
Author URI: http://www.andynguyen.net
*/

#error_reporting(E_ALL);
require_once( dirname( __FILE__ ) . '/vh-related-posts.php' );
$vh_related_posts = new vh_related_posts();


/**
 * Calls the class on the post edit screen.
 */

if ( is_admin() ) {
    add_action( 'load-post.php', array($vh_related_posts, 'load_admin') );
    add_action( 'load-post-new.php', array($vh_related_posts, 'load_admin') );
}


