<?php 
/*
Plugin Name: DLINQ Catalog Additions
Plugin URI:  https://github.com/
Description: Makes Canvas Catalog Descriptions HTML & FAQs
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


/* Add iframe just to course listings */
function dlinq_cc_add_iframe ( $content ) {
   global $post;
   $post_id = $post->ID;
    if ( is_singular('course-listing') ) {
        return $content . '<iframe width="100%" height="2000px" src="https://experiments.middcreate.net/extras/catalog/?id='.$post_id.'"></iframe>';
    }

    return $content;
}
add_filter( 'the_content', 'dlinq_cc_add_iframe');


//add the common FAQs to pages content

function dlinq_cc_global_faq_repeater($content){
   global $post;
   $post_id = $post->ID;
       if ( is_page() && get_field('activate_global_faqs', 'option') === 'true') {

         //page specific FAQs
         $page_html = '';
          if( have_rows('faq', $post_id) ):

             // Loop through rows.
             while( have_rows('faq', $post_id) ) : the_row();

                 // Load sub field value.
                 $faq_title = get_sub_field('faq_title');
                 $faq_content = get_sub_field('faq_text');
                 // Do something...
                  $page_html .= dlinq_cc_faq_html($faq_title,$faq_content);  
             // End loop.
             endwhile;
             
            // No value.
            else :
                // Do something...
         endif;

         //Global FAQs
         if( have_rows('faq', 'option') ):
            $global_html = '';
             // Loop through rows and display if activate global faqs === true
             while( have_rows('faq', 'option')) : the_row();

                 // Load sub field value.
                 $faq_title = get_sub_field('faq_title');
                 $faq_content = get_sub_field('faq_text');
                 // Do something...
                 $global_html .= dlinq_cc_faq_html($faq_title,$faq_content);               
             // End loop.
             endwhile;
            // No value.
            else :
                // Do something...
            endif;
         } else {
            return $content;
         }
         return $page_html . $global_html;
   }

add_filter( 'the_content', 'dlinq_cc_global_faq_repeater');

function dlinq_cc_faq_html($title, $content){
   return "<h2>Q: {$title}</h2>{$content}";
}


//MAKE IT WORK IN REST BRUTE FORCE
add_action( 'rest_api_init', 'add_faqs_to_json' );
function add_faqs_to_json() {
 
    register_rest_field(
        'page', //the post type of your choice
        'faqs_bundle', //the name for your json element
        array(
            'get_callback'    => 'dlinq_cc_json_global_faq_repeater', //the function that creates the content 
        )
    );
}


function dlinq_cc_json_global_faq_repeater(){
   global $post;
   $post_id = $post->ID;
       if ( get_field('activate_global_faqs', 'option') === 'true') {

         //page specific FAQs
         $page_html = '';
          if( have_rows('faq', $post_id) ):

             // Loop through rows.
             while( have_rows('faq', $post_id) ) : the_row();

                 // Load sub field value.
                 $faq_title = get_sub_field('faq_title');
                 $faq_content = get_sub_field('faq_text');
                 // Do something...
                  $page_html .= dlinq_cc_faq_html($faq_title,$faq_content);  
             // End loop.
             endwhile;
             
            // No value.
            else :
                // Do something...
         endif;

         //Global FAQs
         if( have_rows('faq', 'option') ):
            $global_html = '';
             // Loop through rows and display if activate global faqs === true
             while( have_rows('faq', 'option')) : the_row();

                 // Load sub field value.
                 $faq_title = get_sub_field('faq_title');
                 $faq_content = get_sub_field('faq_text');
                 // Do something...
                 $global_html .= dlinq_cc_faq_html($faq_title,$faq_content);               
             // End loop.
             endwhile;
            // No value.
            else :
                // Do something...
            endif;
         } else {
         }
         return $page_html . $global_html;
   }



//save acf json
      add_filter('acf/settings/save_json', 'dlinq_cc_json_save_point');
       
      function dlinq_cc_json_save_point( $path ) {
          
          // update path
          $path = plugin_dir_path(__FILE__) . '/acf-json'; //replace w get_stylesheet_directory() for theme
          
          
          // return
          return $path;
          
      }


      // load acf json
      add_filter('acf/settings/load_json', 'dlinq_cc_json_load_point');

      function dlinq_cc_json_load_point( $paths ) {
          
          // remove original path (optional)
          unset($paths[0]);
          
          
          // append path
          $paths[] = plugin_dir_path(__FILE__)  . '/acf-json';//replace w get_stylesheet_directory() for theme
          
          
          // return
          return $paths;
          
      }

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
