<?php 
/**
 * @Packge 	   : Colorlib
 * @Version    : 1.0
 * @Author 	   : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */
 
	// Block direct access
	if( !defined( 'ABSPATH' ) ){
		exit( 'Direct script access denied.' );
	}

	/**
	 *
	 * Define constant
	 *
	 */
	
	 
	// Base URI
	if( !defined( 'DREAMRS_DIR_URI' ) )
		define( 'DREAMRS_DIR_URI', get_template_directory_uri().'/' );
	
	// assets URI
	if( !defined( 'DREAMRS_DIR_ASSETS_URI' ) )
		define( 'DREAMRS_DIR_ASSETS_URI', DREAMRS_DIR_URI.'assets/' );
	
	// Css File URI
	if( !defined( 'DREAMRS_DIR_CSS_URI' ) )
		define( 'DREAMRS_DIR_CSS_URI', DREAMRS_DIR_ASSETS_URI .'css/' );
	
	// Js File URI
	if( !defined( 'DREAMRS_DIR_JS_URI' ) )
		define( 'DREAMRS_DIR_JS_URI', DREAMRS_DIR_ASSETS_URI .'js/' );
	
	// Icon Images
	if( !defined('DREAMRS_DIR_ICON_IMG_URI') )
		define( 'DREAMRS_DIR_ICON_IMG_URI', DREAMRS_DIR_ASSETS_URI.'img/icon/' );
	
	//DIR inc
	if( !defined( 'DREAMRS_DIR_INC' ) )
		define( 'DREAMRS_DIR_INC', DREAMRS_DIR_URI.'inc/' );

	//Elementor Widgets Folder Directory
	if( !defined( 'DREAMRS_DIR_ELEMENTOR' ) )
		define( 'DREAMRS_DIR_ELEMENTOR', DREAMRS_DIR_INC.'elementor-widgets/' );

	// Base Directory
	if( !defined( 'DREAMRS_DIR_PATH' ) )
		define( 'DREAMRS_DIR_PATH', get_parent_theme_file_path().'/' );
	
	//Inc Folder Directory
	if( !defined( 'DREAMRS_DIR_PATH_INC' ) )
		define( 'DREAMRS_DIR_PATH_INC', DREAMRS_DIR_PATH.'inc/' );
	
	//Colorlib framework Folder Directory
	if( !defined( 'DREAMRS_DIR_PATH_LIB' ) )
		define( 'DREAMRS_DIR_PATH_LIB', DREAMRS_DIR_PATH_INC.'libraries/' );
	
	//Classes Folder Directory
	if( !defined( 'DREAMRS_DIR_PATH_CLASSES' ) )
		define( 'DREAMRS_DIR_PATH_CLASSES', DREAMRS_DIR_PATH_INC.'classes/' );

	
	//Widgets Folder Directory
	if( !defined( 'DREAMRS_DIR_PATH_WIDGET' ) )
		define( 'DREAMRS_DIR_PATH_WIDGET', DREAMRS_DIR_PATH_INC.'widgets/' );
		
	//Elementor Widgets Folder Directory
	if( !defined( 'DREAMRS_DIR_PATH_ELEMENTOR_WIDGETS' ) )
		define( 'DREAMRS_DIR_PATH_ELEMENTOR_WIDGETS', DREAMRS_DIR_PATH_INC.'elementor-widgets/' );
	

		
	/**
	 * Include File
	 *
	 */
	
	// Breadcrumbs file include
	require_once( DREAMRS_DIR_PATH_INC . 'dreamrs-breadcrumbs.php' );
	// Sidebar register file include
	require_once( DREAMRS_DIR_PATH_INC . 'widgets/dreamrs-widgets-reg.php' );
	// Post widget file include
	require_once( DREAMRS_DIR_PATH_INC . 'widgets/dreamrs-recent-post-thumb.php' );
	// News letter widget file include
	require_once( DREAMRS_DIR_PATH_INC . 'widgets/dreamrs-newsletter-widget.php' );
	//Social Links
	require_once( DREAMRS_DIR_PATH_INC . 'widgets/dreamrs-social-links.php' );
	// Instagram Widget
	require_once( DREAMRS_DIR_PATH_INC . 'widgets/dreamrs-instagram.php' );
	// Nav walker file include
	require_once( DREAMRS_DIR_PATH_INC . 'wp_bootstrap_navwalker.php' );
	// Theme function file include
	require_once( DREAMRS_DIR_PATH_INC . 'dreamrs-functions.php' );

	// Theme Demo file include
	require_once( DREAMRS_DIR_PATH_INC . 'demo/demo-import.php' );

	// Post Like
	require_once( DREAMRS_DIR_PATH_INC . 'post-like.php' );
	// Theme support function file include
	require_once( DREAMRS_DIR_PATH_INC . 'support-functions.php' );
	// Html helper file include
	require_once( DREAMRS_DIR_PATH_INC . 'wp-html-helper.php' );
	// Pagination file include
	require_once( DREAMRS_DIR_PATH_INC . 'wp_bootstrap_pagination.php' );
	// Elementor Widgets
	require_once( DREAMRS_DIR_PATH_ELEMENTOR_WIDGETS . 'elementor-widget.php' );
	//
	require_once( DREAMRS_DIR_PATH_CLASSES . 'Class-Enqueue.php' );
	
	require_once( DREAMRS_DIR_PATH_CLASSES . 'Class-Config.php' );
	// Customizer
	require_once( DREAMRS_DIR_PATH_INC . 'customizer/customizer.php' );
	// Class autoloader
	require_once( DREAMRS_DIR_PATH_INC . 'class-epsilon-dashboard-autoloader.php' );
	// Class dreamrs dashboard
	require_once( DREAMRS_DIR_PATH_INC . 'class-epsilon-init-dashboard.php' );
	// Common css
	require_once( DREAMRS_DIR_PATH_INC . 'dreamrs-commoncss.php' );
	// SVG Icon File
	require_once( DREAMRS_DIR_PATH_INC . 'load-svg-icons.php' );


	if( class_exists( 'RW_Meta_Box' ) ){
		// Metabox Function
		require_once( DREAMRS_DIR_PATH_INC . 'dreamrs-metabox.php' );
	}


	// Admin Enqueue Script
	function dreamrs_admin_script(){
		wp_enqueue_style( 'dreamrs-admin', get_template_directory_uri().'/assets/css/dreamrs_admin.css', false, '1.0.0' );
		wp_enqueue_script( 'dreamrs_admin', get_template_directory_uri().'/assets/js/dreamrs_admin.js', false, '1.0.0' );
	}
	add_action( 'admin_enqueue_scripts', 'dreamrs_admin_script' );

	 
	// WooCommerce style desable
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );


	/**
	 * Instantiate Dreamrs object
	 *
	 * Inside this object:
	 * Enqueue scripts, Google font, Theme support features, Dreamrs Dashboard .
	 *
	 */
	
	$Dreamrs = new Dreamrs();
	
