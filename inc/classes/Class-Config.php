<?php 
/**
 * @Packge 	   : Dreamrs
 * @Version    : 1.0
 * @Author 	   : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */
 
	// Block direct access
	if( !defined( 'ABSPATH' ) ){
		exit( 'Direct script access denied.' );
	}

	// Final Class
	final class Dreamrs{

		
		// Theme Version
		private $dreamrs_version = '1.0';

		// Minimum WordPress Version required
		private $min_wp = '4.0';

		// Minimum PHP version required 
		private $min_php = '5.6.25';

		function __construct(){
			// Theme Support
			add_action( 'after_setup_theme', array( $this, 'support' ) );
			// 
			$this->init();
		}

		// Theme init
		public function init(){
			//
			$this->setup();

			// customizer init Instantiate
			$this->customizer_init();
			
		}

		// Theme setup
		private function setup(){
			
			// Create enqueue class instance
			$enqueu = new dreamrs_Enqueue();
			$enqueu->scripts = $this->enqueue() ;
			$enqueu->dreamrs_scripts_enqueue_init() ;

		}
		// Theme Support
		public function support(){
			// content width
	        $GLOBALS['content_width'] = apply_filters( 'dreamrs_content_width', 751 );

	        
	        // text domain for translation.
	        load_theme_textdomain( 'dreamrs', DREAMRS_DIR_PATH . '/languages' );
	        
	        // support title tage
	        add_theme_support( 'title-tag' );
	        
	        // support logo
			add_theme_support( 'custom-logo', array(
				'height'      => 38,
				'width'       => 196,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text' => array( 'site-title', 'site-description' ),
			) );

			//Custom Hreader
			add_theme_support( 'custom-header', array(
				'flex-width'    => true,
				'width'         => 1920,
				'flex-height'   => true,
				'height'        => 575,
				'default-image' => get_template_directory_uri() . '/assets/img/banner.jpg'
			) );

			//Custom background
			add_theme_support( 'custom-background', array(
				'default-color' => 'ffffff'
			) );

	        //  support post format
	        add_theme_support( 'post-formats', array( 'video','audio' ) );
	        
	        // support post-thumbnails
	        add_theme_support( 'post-thumbnails', array( 'post', 'portfolio', 'apartment' ) );
			
			// Site logo size
			add_image_size( 'dreamrs_logo_196x38', 196, 38, true );
			add_image_size( 'dreamrs_footer_logo_134x25', 134, 25, true );
					
			// About section image size
			add_image_size( 'dreamrs_about_section_457x500', 457, 500, true );
						
			// Portfolio image size
			add_image_size( 'dreamrs_portfolio_image_555x589', 555, 589, true );
			add_image_size( 'dreamrs_portfolio_image_458x491', 458, 491, true );
			add_image_size( 'dreamrs_portfolio_single_image_960x700', 960, 700, true );
						
			// Apartment image size
			add_image_size( 'dreamrs_apartment_image_960x350', 960, 350, true );
			add_image_size( 'dreamrs_apartment_image_479x350', 479, 350, true );

			// Home blog post image size
			add_image_size( 'dreamrs_latest_blog_606x269', 606, 269, true );

			// Client image size
			add_image_size( 'dreamrs_client_img_203x203', 203, 203, true );

			// Latest post thumbnail Widget thumbnail size
			add_image_size( 'dreamrs_widget_post_thumb', 80, 80, true );

			// Single blog post image size
			add_image_size( 'dreamrs_single_blog_750x375', 750, 375, true );
			add_image_size( 'dreamrs_np_thumb', 60, 60, true );
	        	        
	        // support automatic feed links
	        add_theme_support( 'automatic-feed-links' );
	        
	        // support html5
	        add_theme_support( 'html5' );
			
			// Add theme support for selective refresh for widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );
						    
	        // register nav menu
	        register_nav_menus( array(
	            'primary-menu'   => esc_html__( 'Primary Menu', 'dreamrs' ),
				'important-link' => esc_html__( 'Important Link', 'dreamrs' )
	        ) );

	        // editor style
	        add_editor_style('assets/css/editor-style.css');

		} // end support method

		// enqueue theme style and script
		private function enqueue(){

			$cssPath = DREAMRS_DIR_CSS_URI;
			$jsPath  = DREAMRS_DIR_JS_URI;

			$scripts = array(
				'style' => array(
					array(
						'handler'		=> 'dreamrs-google-font',
						'file' 			=> $this->google_font(),
					),
					array(
						'handler'		=> 'dreamrs-bootstrap',
						'file' 			=> $cssPath.'bootstrap.min.css',
						'dependency' 	=> array(),
						'version' 		=> '5.3.8-5',
					),
					array(
						'handler'		=> 'dreamrs-animate',
						'file' 			=> $cssPath.'animate.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'dreamrs-owl-carousel',
						'file' 			=> $cssPath.'owl.carousel.min.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'dreamrs-font-awesome',
						'file' 			=> $cssPath.'font-awesome.min.css',
						'dependency' 	=> array(),
						'version' 		=> '7.3.1-1',
					),
					array(
						'handler'		=> 'dreamrs-themify',
						'file' 			=> $cssPath.'themify-icons.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'dreamrs-flaticon',
						'file' 			=> $cssPath.'flaticon.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'dreamrs-magnific-popup-css',
						'file' 			=> $cssPath.'magnific-popup.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'dreamrs-default-css',
						'file' 			=> $cssPath.'default.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'dreamrs-style-css',
						'file' 			=> $cssPath.'style.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					
					array(
						'handler'		=> 'dreamrs-style',
						'file' 			=> get_stylesheet_uri(),
					),
				),
				
				'scripts' => array(
					array(
						'handler'		=> 'dreamrs-bootstrap',
						'file' 			=> $jsPath.'bootstrap.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '5.3.8-4',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'dreamrs-magnific-popup-js',
						'file' 			=> $jsPath.'jquery.magnific-popup.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'dreamrs-owl-carousel-js',
						'file' 			=> $jsPath.'owl.carousel.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'dreamrs-jquery-easing-js',
						'file' 			=> $jsPath.'jquery.easing.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'dreamrs-instagram-feed-js',
						'file' 			=> $jsPath.'jquery.instagramFeed.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'dreamrs-jquery-ajaxchimp-js',
						'file' 			=> $jsPath.'jquery.ajaxchimp.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'dreamrs-jquery-nice-select-js',
						'file' 			=> $jsPath.'jquery.nice-select.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'dreamrs-slick-js',
						'file' 			=> $jsPath.'slick.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'dreamrs-jquery-counterup-js',
						'file' 			=> $jsPath.'jquery.counterup.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'dreamrs-waypoints-js',
						'file' 			=> $jsPath.'waypoints.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),

					array(
						'handler'		=> 'dreamrs-custom',
						'file' 			=> $jsPath.'custom.js',
						'dependency' 	=> array( 'jquery', 'masonry' ),
						'version' 		=> $this->dreamrs_version,
						'in_footer' 	=> true
					),

				)
			);

			return $scripts;

		} // end enqueu method 

		// Google Font  
		private function google_font(){
			$font_url = '';

			/*
			 * The families this theme uses are bundled under
			 * assets/fonts/google, so nothing is fetched from Google and
			 * no request leaves the visitor's browser for a third party.
			 *
			 * Translators can still turn the fonts off for scripts these
			 * families do not cover.
			 */
			if ( 'off' !== _x( 'on', 'Google font: on or off', 'dreamrs' ) ) {
				$font_url = get_template_directory_uri() . '/assets/css/google-fonts.css';
			}

			return esc_url_raw( $font_url );
		} //End google_font method

		private function customizer_init(){

		
			

			
			// Instantiate dreamrs theme customizer
			$dreamrs_theme_customizer = new dreamrs_theme_customizer();
		}
	} // End Dreamrs Class

?>