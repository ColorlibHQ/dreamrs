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



?>

	<div id="page_<?php the_ID(); ?>" <?php post_class('row'); ?>>
		<?php 

		/**
		 * page content 
		 * Comments Template
		 * @Hook  dreamrs_page_content
		 *
		 * @Hooked dreamrs_page_content_cb
		 * 
		 *
		 */
		do_action( 'dreamrs_page_content' );

		?>
	</div>
