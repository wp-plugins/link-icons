<?php
/*
Plugin Name: Link Icons
Plugin URI: 
Description: Link Icons adds icons to your links to indicate if it's to an external site, an image etc.
Author: Erik Bergh
Version: 0.2
Author URI: http://www.bergh.me
*/

include_once ( dirname(__FILE__) . "/link_icons_functions.php");

wp_register_style( 'link-icons-style', plugins_url('/css/link_icons.css', __FILE__) );
wp_enqueue_style( 'link-icons-style' );

wp_enqueue_script('jquery');

add_filter('the_content','link_icons');

?>
