<?php
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit( 'Direct script access denied.' );
}

/**
 * @Packge     : Dreamrs
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */
 
 
function dreamrs_widgets_init() {
    // sidebar widgets 
    
    register_sidebar(array(
        'name'          => esc_html__('Sidebar widgets', 'dreamrs'),
        'description'   => esc_html__('Place widgets in sidebar widgets area.', 'dreamrs'),
        'id'            => 'sidebar_widgets',
        'before_widget' => '<div id="%1$s" class="widget single_sidebar_widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget_title">',
        'after_title'   => '</h4>'
    ));

	// footer widgets register
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer One', 'dreamrs' ),
			'id'            => 'footer-1',
			'before_widget' => '<div id="%1$s" class="single_footer_part footer_1 %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Two', 'dreamrs' ),
			'id'            => 'footer-2',
			'before_widget' => '<div class="col-sm-6 col-lg-3"><div id="%1$s" class="single_footer_part footer_2 %2$s">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Three', 'dreamrs' ),
			'id'            => 'footer-3',
			'before_widget' => '<div class="col-sm-6 col-lg-3"><div id="%1$s" class="single_footer_part footer_3 %2$s">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Four', 'dreamrs' ),
			'id'            => 'footer-4',
			'before_widget' => '<div class="col-sm-6 col-lg-3"><div id="%1$s" class="single_footer_part footer_4 %2$s">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		)
	);
	

}
add_action( 'widgets_init', 'dreamrs_widgets_init' );
