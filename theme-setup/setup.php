<?php
/**
 * Webdesignby_Theme - A Wordpress theme development framework
 * 
 * @package Webdesignby_Theme
 * @version 1.0
 * @author Ross Sabes <ross@webdesignby.com>
 * @copyright (c) 2014, Ross Sabes
 * 
 */

if( !class_exists('Webdesignby_Theme')):

class Webdesignby_Theme
{
    
    protected $config = array(
                            'thumbnails',
                    );
    
    protected $unlimited_slugs = array('equipment', 'projects');
    
    function __construct( $config = array() )
    {
        if( ! empty( $config ) && is_array($config) )
        {
            $this->config = $config;
        }
        /*
         * function hooks...
         * http://codex.wordpress.org/Plugin_API/Action_Reference#Actions_Run_During_a_Typical_Request
         */
     
        add_action( 'after_setup_theme', array($this, 'setup') );
        add_action( 'init', array( $this, 'init') );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts'));
        add_action( 'wp_head', array($this, 'head') );
        add_action( 'pre_get_posts', array($this, 'pre_get_posts'));
        
    }
    
    function configure( $var )
    {
        if( isset($this->config[$var]) )
            return true;
        
        return false;
    }
    
    function setup()
    {
        if( $this->configure('thumbnails'));
        $this->setup_thumbnails();
        
        $this->setup_menus();
    }
    
    function setup_menus()
    {
        /*
         * Main Navigation Setup
         */
        // This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'webdesignby1' ) );
        register_nav_menu( 'footer', __( 'Footer Menu', 'webdesignby1' ) );
    }
    
    function setup_thumbnails()
    {
        /*
         * Define sizes needed
         */
        $thumb_sizes = array(
            array(
                'name'      => 'thumb',
                'width'     => 150,
                'height'    => 85,
                'crop'      => true,
            ),
            array(
                'name'      => 'thumb_270_360',
                'width'     => 270,
                'height'    => 360,
                'crop'      => false,
            ),
             array(
                'name'      => 'thumb_300_400',
                'width'     => 300,
                'height'    => 400,
                'crop'      => false,
            ),
            array(
                'name'      => 'webdesignby-full-width',
                'height'    => 372,
                'width'     => 672,
                'crop'      => true,
            )
        );
        
        
        if( is_array($thumb_sizes) && count($thumb_sizes) )
        {
            $init = array_shift($thumb_sizes);
            set_post_thumbnail_size( $init['width'], $init['height'], $init['crop'] );
            foreach($thumb_sizes as $thumb)
            {
                add_image_size( $thumb['name'], $thumb['width'], $thumb['height'], $thumb['crop'] );
            }
        }
        
        add_theme_support( 'post-thumbnails' );
    }
    
    function init()
    {
        $this->post_types();
    }
    
    /**
    * Post Type Definitions
    */
    
    function post_types()
    {
        $this->register_post_type('equipment');
        $this->register_post_type('projects', 'Projects', array('title', 'thumbnail', 'editor', 'excerpt'));
    }
    
    /*
     *  Custom Post Types
     *  http://codex.wordpress.org/Function_Reference/register_post_type
     */
    function register_post_type( $type, $label = null, $supports = array( 'title', 'thumbnail', 'editor' ) )
    {
        
        if( empty($label))
            $label = ucfirst($type);
        
        // Equipment
        
        $args = array(
            'public'    => true,
            'label'     => __( $label ),
            'supports'  => $supports,
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => $type)
        );
    
        register_post_type($type, $args);
    }
    
    /**
     * Enque Scripts
     */
    function enqueue_scripts()
    {
        $this->enqueue_styles();
        // wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
    }
    
    function enqueue_styles()
    {
        wp_enqueue_style( 'main', get_template_directory_uri() . '/styles/main.css' );
        
        // fonts
        wp_enqueue_style( 'font_oswald', 'http://fonts.googleapis.com/css?family=Oswald:700');
        wp_enqueue_style( 'font_economica', 'http://fonts.googleapis.com/css?family=Economica');
    }
    
    /**
    * WP Head Actions
    * 
    * 
    */
   function head()
   {
       // <link rel="icon" type="image/ico" href="http://www.mysite.com/favicon.ico"/>
       echo "<link rel=\"shortcut icon\" href=\"" . get_bloginfo('stylesheet_directory') . "/favicon.ico\" />";

   }
   
   /**
    * Query Modifications
    */
   
    // add_action( 'pre_get_posts', 'hwl_home_pagesize', 1 );
   
   function unlimited_posts_per_page( $query )
   {
       $query->set( 'posts_per_page', -1 );
   }
   
   function unlimited_ppp()
   {
       add_action( 'pre_get_posts', array($this, 'unlimited_posts'));
   }
   
   function pre_get_posts( $query )
   {
       
       // only perform on main query on the front end
       if( $query->is_main_query() && ! is_admin() ){
          
           if( $this->is_unlimted() )
           {
               $query->set( 'posts_per_page', -1 );
           }
       }
   }
   
   function is_unlimted()
   {
       $slug = get_query_var( 'post_type' );

       if( in_array($slug, $this->unlimited_slugs ) )
       {
           return true;
       }
       
       return false;
   }
   
    
}

endif;