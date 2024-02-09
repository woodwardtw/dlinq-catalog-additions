<?php 
/*
Plugin Name: DLINQ Catalog Additions
Plugin URI:  https://github.com/
Description: For stuff to deal with Canvas Catalog
Version:     1.0
Author:      DLINQ
Author URI:  https://dlinq.middcreate.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: my-toolset

*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


//add_action('wp_enqueue_scripts', 'prefix_load_scripts');

function prefix_load_scripts() {                           
    $deps = array('jquery');
    $version= '1.0'; 
    $in_footer = true;    
    wp_enqueue_script('dlinq-cc-main-js', plugin_dir_url( __FILE__) . 'js/dlinq-cc-main.js', $deps, $version, $in_footer); 
    wp_enqueue_style( 'dlinq-cc-main-css', plugin_dir_url( __FILE__) . 'css/dlinq-cc-main.css');
}


/* Add a paragraph only to Pages. */
function dlinq_cc_add_iframe ( $content ) {
   global $post;
   $post_id = $post->ID;
    if ( is_singular('course-listing') ) {
        return $content . '<iframe src="https://experiments.middcreate.net/extras/catalog/?id='.$post_id.'"></iframe>';
    }

    return $content;
}
add_filter( 'the_content', 'dlinq_cc_add_iframe');


//LOGGER -- like frogger but more useful

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}

  //print("<pre>".print_r($a,true)."</pre>");
