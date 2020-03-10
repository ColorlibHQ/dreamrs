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
			if( class_exists('Epsilon_Framework') ){
				$this->customizer_init();
			}
			
			// Instantiate  Dashboard
			$Epsilon_init_Dashboard = Epsilon_init_Dashboard::get_instance();
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
						'handler'		=> 'google-font',
						'file' 			=> $this->google_font(),
					),
					array(
						'handler'		=> 'bootstrap',
						'file' 			=> $cssPath.'bootstrap.min.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'animate',
						'file' 			=> $cssPath.'animate.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'owl-carousel',
						'file' 			=> $cssPath.'owl.carousel.min.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'font-awesome',
						'file' 			=> $cssPath.'font-awesome.min.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'themify',
						'file' 			=> $cssPath.'themify-icons.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'flaticon',
						'file' 			=> $cssPath.'flaticon.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'magnific-popup-css',
						'file' 			=> $cssPath.'magnific-popup.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'default-css',
						'file' 			=> $cssPath.'default.css',
						'dependency' 	=> array(),
						'version' 		=> '1.0',
					),
					array(
						'handler'		=> 'style-css',
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
						'handler'		=> 'popper',
						'file' 			=> $jsPath.'popper.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '4.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'bootstrap',
						'file' 			=> $jsPath.'bootstrap.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '4.3.1',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'magnific-popup-js',
						'file' 			=> $jsPath.'jquery.magnific-popup.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'owl-carousel-js',
						'file' 			=> $jsPath.'owl.carousel.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'jquery-easing-js',
						'file' 			=> $jsPath.'jquery.easing.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'instagram-feed-js',
						'file' 			=> $jsPath.'jquery.instagramFeed.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'masonry-pkgd-js',
						'file' 			=> $jsPath.'masonry.pkgd.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'jquery-ajaxchimp-js',
						'file' 			=> $jsPath.'jquery.ajaxchimp.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'jquery-nice-select-js',
						'file' 			=> $jsPath.'jquery.nice-select.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'slick-js',
						'file' 			=> $jsPath.'slick.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'jquery-counterup-js',
						'file' 			=> $jsPath.'jquery.counterup.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),
					array(
						'handler'		=> 'waypoints-js',
						'file' 			=> $jsPath.'waypoints.min.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> '1.0',
						'in_footer' 	=> true
					),

					array(
						'handler'		=> 'dreamrs-custom',
						'file' 			=> $jsPath.'custom.js',
						'dependency' 	=> array( 'jquery' ),
						'version' 		=> $this->dreamrs_version,
						'in_footer' 	=> true
					),

				)
			);

			return $scripts;

		} // end enqueu method 

		// Google Font  
		private function google_font(){

			$fontUrl = '';
			
			if ( 'off' !== _x( 'on', 'Google font: on or off', 'dreamrs' ) ) {

				$font_families = array(
					'Open+Sans:300i,300,400i,400,700',
					'Poppins:400,500,600,700'
				);

				$familyArgs = array(
					'family' => htmlentities( implode( '|', $font_families ) ),
					'subset' => urlencode( 'latin, latin-text' ),
				);

				$fontUrl = add_query_arg( $familyArgs, '//fonts.googleapis.com/css' );
			}
			
			return esc_url_raw( $fontUrl );

		} //End google_font method

		// epsilon customizer init
		private function customizer_init(){

			// epsilon customizer quickie settings
		
			add_filter( 'epsilon_quickie_bar_shortcuts', array( $this, 'epsilon_quickie' ) );
			
			// Instantiate Epsilon Framework object
			$Epsilon_Framework = new Epsilon_Framework();

			
			// Instantiate dreamrs theme customizer
			$dreamrs_theme_customizer = new dreamrs_theme_customizer();
		}

		public function epsilon_quickie(){

				return	array(

				'links' => array(
					array(
						'link_to'   => 'dreamrs_theme_options_panel',
						'icon'      => 'dashicons dashicons-admin-home',
						'link_type' => 'panel',
					),
					array(
						'link_to'   => 'nav_menus',
						'icon'      => 'dashicons dashicons-menu',
						'link_type' => 'panel',
					),
					array(
						'link_to'   => 'widgets',
						'icon'      => 'dashicons dashicons-archive',
						'link_type' => 'panel',
					),
					array(
						'link_to'   => 'custom_css',
						'icon'      => 'dashicons dashicons-editor-code',
						'link_type' => 'section',
					),

				),
				'logo'  => array(
					'url' => EPSILON_URI . '/assets/img/epsilon-logo.png',
					'alt' => 'Epsilon Builder Logo',
				),
			);

		}

	} // End Dreamrs Class

?>