<?php

/**
 * webdesignby1 functions and definitions
 *
*/

if ( ! function_exists( 'webdesignby1_setup' ) ) :
    
    function webdesignby1_setup()
    {
         // Add RSS feed links to <head> for posts and comments.
        // add_theme_support( 'automatic-feed-links' );
        // Enable support for Post Thumbnails, and declare two sizes.

        /*
         * Define sizes needed
         */
        $thumb_sizes = array(
            array(
                'name'      => 'thumb',
                'height'    => 372,
                'width'     => 672,
                'crop'      => true,
            ),
            array(
                'name'      => 'webdesignby1-full-width',
                'height'    => 372,
                'width'     => 672,
                'crop'      => true,
            )
        );
        
        add_theme_support( 'post-thumbnails' );
        
        if( is_array($thumb_sizes) && count($thumb_sizes) )
        {
            $init = array_shift($thumb_sizes);
            set_post_thumbnail_size( $init['width'], $init['height'], $init['crop'] );
            foreach($thumb_sizes as $thumb)
            {
                add_image_size( $thumb['name'], $thumb['width'], $thumb['height'], $thumb['crop'] );
            }
        }
        
        /*
         * Main Navigation Setup
         */
        // This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'webdesignby1' ) );
        register_nav_menu( 'footer', __( 'Footer Menu', 'webdesignby1' ) );
       
    }
    
endif;
add_action( 'after_setup_theme', 'webdesignby1_setup' );

/*
 * Enque scripts and styles
 * 
 * http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 */

function webdesignby1_styles()
{
    wp_enqueue_style( 'main', get_template_directory_uri() . '/styles/main.css' );
    wp_enqueue_style( 'menu', get_template_directory_uri() . '/styles/menu.css' );
}

add_action( 'wp_enqueue_scripts', 'webdesignby1_styles' );

function webdesignby1_scripts()
{
    // wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'webdesignby1_scripts');