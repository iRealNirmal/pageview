<?php
   /*
   Plugin Name: Page View
   Plugin URI: http://www.demo.com
   Description: A plugin through which we can check the page viewed
   Version: 1.0
   Author: Nirmal Bhagwani
   Author URI: nirmalbhagwani.wordpress.com
   License: GPL2
   */
/**
 * Adds a view to the post being viewed
 *
 * Finds the current views of a post and adds one to it by updating
 * the postmeta. The meta key used is "awepop_views".
 *
 * @global object $post The post object
 * @return integer $new_views The number of views the post has
 *
 */
function awepop_add_view() {
   if(is_single()) {
      global $post;
      $current_views = get_post_meta($post->ID, "awepop_views", true);
      if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
         $current_views = 0;
      }
      $new_views = $current_views + 1;
      update_post_meta($post->ID, "awepop_views", $new_views);
      return $new_views;
   }
}
add_action("wp_head", "awepop_add_view");
/**
 * Retrieve the number of views for a post
 *
 * Finds the current views for a post, returning 0 if there are none
 *
 * @global object $post The post object
 * @return integer $current_views The number of views the post has
 *
 */
function awepop_get_view_count() {
   global $post;
   $current_views = get_post_meta($post->ID, "awepop_views", true);
   if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
      $current_views = 0;
   }

   return $current_views;
}
/**
 * Displays a list of posts ordered by popularity
 *
 * Shows a simple list of post titles ordered by their view count
 *
 * @param integer $post_count The number of posts to show
 *
 */
 function awepop_popularity_list($post_count = 10) {
 	$args = array(
 		"posts_per_page" => 10,
 		"post_type" => "post",
 		"post_status" => "publish",
 		"meta_key" => "awepop_views",
 		"orderby" => "meta_value_num",
 		"order" => "DESC"
 	);
 	
 	$awepop_list = new WP_Query($args);
 	
 	if($awepop_list->have_posts()) { echo ""; }
 	echo '<div id="Viewed" class="widget"><h3 class="widget-title">Most Viewed Page</h3><ul>';
 	while ( $awepop_list->have_posts() ) : $awepop_list->the_post();
 		echo '<li><a href="' . get_permalink().'">'.the_title('', '', false).'</a></li>';
 	endwhile;
	echo "</ul></div>";
 	if($awepop_list->have_posts()) { echo "";}
 }
 
if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook(__FILE__, 'example_deinstall');

 /**
 * Delete options in database
 */
function example_deinstall() {

	
 //if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

$option_name = 'awepop_views';

// For Single site
if ( !is_multisite() ) 
{
    delete_option( $option_name );
} 
// For Multisite
else 
{
	if(is_single()) {
    delete_post_meta($post->ID, "awepop_views", true);
	}
}
}