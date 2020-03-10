<?php 
/**
 * @Packge     : Dreamrs
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */

if( !defined( 'WPINC' ) ){
    die;
}

// demo import file
function dreamrs_import_files() {
	
	$demoImg = '<img src="'. DREAMRS_DIR_INC . 'demo/screen-image.png' .' " alt="'.esc_attr__( 'Demo Preview Imgae', 'dreamrs' ).'" />';
	
  return array(
    array(
      'import_file_name'             => 'Dreamrs Demo',
      'local_import_file'            => DREAMRS_DIR_PATH_INC .'demo/dreamrs-demo.xml',
      'local_import_widget_file'     => DREAMRS_DIR_PATH_INC .'demo/dreamrs-widgets.wie',
      'import_customizer_file_url'   => DREAMRS_DIR_INC . 'demo/dreamrs-customizer.dat',
      'import_notice' => $demoImg,
    ),
  );
}
add_filter( 'pt-ocdi/import_files', 'dreamrs_import_files' );
	
// demo import setup
function dreamrs_after_import_setup() {
	// Assign menus to their locations.
    $main_menu    	  = get_term_by( 'name', 'Main Menu', 'nav_menu' );
	$services_menu    = get_term_by( 'name', 'Our Service', 'nav_menu' );
	$footer_menu      = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
            'primary-menu' => $main_menu->term_id,
            'our-services' => $services_menu->term_id,
            'footer-menu'  => $footer_menu->term_id
		)
	);

	// Assign front page and posts page (blog page).
	$front_page_id = get_page_by_title( 'Homepage' );
	$blog_page_id  = get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
	update_option( 'dreamrs_demodata_import', 'yes' );

}
add_action( 'pt-ocdi/after_import', 'dreamrs_after_import_setup' );

//disable the branding notice after successful demo import
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

//change the location, title and other parameters of the plugin page
function dreamrs_import_plugin_page_setup( $default_settings ) {
	$default_settings['parent_slug'] = 'themes.php';
	$default_settings['page_title']  = esc_html__( 'One Click Demo Import' , 'dreamrs' );
	$default_settings['menu_title']  = esc_html__( 'Import Demo Data' , 'dreamrs' );
	$default_settings['capability']  = 'import';
	$default_settings['menu_slug']   = 'dreamrs-demo-import';

	return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'dreamrs_import_plugin_page_setup' );

// Enqueue scripts
function dreamrs_demo_import_custom_scripts(){
	
	
	if( isset( $_GET['page'] ) && $_GET['page'] == 'dreamrs-demo-import' ){
		// style
		wp_enqueue_style( 'dreamrs-demo-import', DREAMRS_DIR_INC . 'demo/css/demo-import.css', array(), '1.0', false );
	}
	
	
}
add_action( 'admin_enqueue_scripts', 'dreamrs_demo_import_custom_scripts' );



?>