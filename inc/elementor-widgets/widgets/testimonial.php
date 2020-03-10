<?php
namespace Dreamrselementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Dreamrs elementor Testimonial section widget.
 *
 * @since 1.0
 */
class Dreamrs_Testimonial extends Widget_Base {

	public function get_name() {
		return 'dreamrs-testimonial';
	}

	public function get_title() {
		return __( 'Testimonial', 'dreamrs' );
	}

	public function get_icon() {
		return 'eicon-slides';
	}

	public function get_categories() {
		return [ 'dreamrs-elements' ];
	}

	protected function _register_controls() {
        
        // Section Heading
		$this->start_controls_section(
			'section_heading',
			[
				'label' => __( 'Section Heading', 'dreamrs' ),
			]
        );
        
        $this->add_control(
            'sec_heading',
            [
                'label'         => esc_html__( 'Section Heading Text', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => __( 'Testimonial', 'dreamrs' )
            ]
        );
		$this->end_controls_section(); 


		// ----------------------------------------   Testimonial contents ------------------------------
		$this->start_controls_section(
			'testimonial_block',
			[
				'label' => __( 'Testimonial', 'dreamrs' ),
			]
		);
		$this->add_control(
            'testimonials_content', [
                'label' => __( 'Create New', 'dreamrs' ),
                'type'  => Controls_Manager::REPEATER,
                'title_field' => '{{{ client_name }}}',
                'fields' => [
                    [
                        'name'  => 'client_img',
                        'label' => __( 'Client Image', 'dreamrs' ),
                        'type'  => Controls_Manager::MEDIA,
                        'label_block' => true,
                    ],
                    [
                        'name'      => 'client_name',
                        'label'     => __( 'Client Name', 'dreamrs' ),
                        'type'      => Controls_Manager::TEXT,
                        'default'   => __( 'Daniel E Gilcritst', 'dreamrs' )
                    ],
                    [
                        'name'      => 'client_des',
                        'label'     => __( 'Client Designation', 'dreamrs' ),
                        'type'      => Controls_Manager::TEXT,
                        'default'   => __( 'Manager, Vision', 'dreamrs' )
                    ],
                    [
                        'name'      => 'client_rev',
                        'label'     => __( 'Client Review', 'dreamrs' ),
                        'type'      => Controls_Manager::TEXTAREA,
                        'default'   => __( 'Enim a, scelerisque aliquet. Vivamus neque diam sed at, pede laoreet orci. Potenti vel In sagittis nulla augue vitae et tempus torquent. Lacinia neque mus taciti ante prsent at facilisis. Enim a, scelerisque aliquet. Vivamus neque diam sed at, pede laoreet orci Potti, vel. In sagittis nulla augue vitae et tempus torquent.', 'dreamrs' )
                    ],
                ],
            ]
        );

		$this->end_controls_section(); // End Testimonial content

        /**
         * Style Tab
         * ------------------------------ Style Tab Content ------------------------------
         *
         */

        // Heading Style ==============================
        $this->start_controls_section(
            'color_sect', [
                'label' => __( 'Style Heading', 'dreamrs' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'section_color', [
                'label'     => __( 'Section Title Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review_part .section_tittle h2' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .review_part .section_tittle h2:after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .review_part .section_tittle h2:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );    
        $this->add_control(
            'sectionbg_pattern_sep',
            [
                'label'     => __( 'Background Pattern Settings', 'dreamrs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'testimonial_bg_color', [
                'label'     => __( 'Testimonial BG Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review_part .slider .slick-list' => 'background-color: {{VALUE}};',
                ],
            ]
        );    
        $this->add_control(
            'client_name_color', [
                'label'     => __( 'Client Name Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review_part .client_review_text h3' => 'color: {{VALUE}};',
                ],
            ]
        );    
        $this->add_control(
            'client_desg_color', [
                'label'     => __( 'Client Designation Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review_part .client_review_text h5' => 'color: {{VALUE}};',
                ],
            ]
        );    
        $this->add_control(
            'client_rev_color', [
                'label'     => __( 'Client Review Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .review_part .client_review_text p' => 'color: {{VALUE}};',
                ],
            ]
        );    
        
        $this->end_controls_section();


	}

	protected function render() {
        
    // call load widget script
    $this->load_widget_script();

    $settings = $this->get_settings();
    $sec_heading  = !empty( $settings['sec_heading'] ) ? $settings['sec_heading'] : '';
    $testimonials = !empty( $settings['testimonials_content'] ) ? $settings['testimonials_content'] : '';
    $count = 1;
    ?>

    <!--::review_part start::-->
   <section class="review_part padding_bottom">
      <div class="container-fluid">
         <div class="row justify-content-center">
            <div class="col-xl-6 col-sm-6">
               <div class="section_tittle text-center">
                  <h2><?php echo esc_html( $sec_heading );?></h2>
               </div>
            </div>
         </div>
         <div class="row justify-content-center">
            <div class="col-lg-11 col-sm-8">
               <!-- THUMBNAILS -->
               <div class="slider-nav-thumbnails">
                    <?php
                    if( is_array( $testimonials ) && count( $testimonials ) > 0 ){
                        foreach ( $testimonials as $testimonial ) {
                            $client_img = !empty( $testimonial['client_img']['id'] ) ? wp_get_attachment_image( $testimonial['client_img']['id'], 'dreamrs_client_img_203x203', false, array( 'class' => 'image', 'alt' => 'client image' ) ) : '';
                        ?>
                        <div class="slider-thumbnails">
                            <?php
                                if( $client_img ){
                                    echo wp_kses_post( $client_img );
                                }
                            ?>
                        </div>
                        <?php
                        }
                    }
                    ?>
               </div>
            </div>
            <div class="col-lg-7 col-sm-10">
               <!-- MAIN SLIDES -->
               <div class="slider">
                    <?php
                    if( is_array( $testimonials ) && count( $testimonials ) > 0 ){
                        foreach ( $testimonials as $testimonial ) {
                            $client_name    = !empty( $testimonial['client_name'] ) ? $testimonial['client_name'] : '';
                            $client_desig   = !empty( $testimonial['client_des'] ) ? $testimonial['client_des'] : '';
                            $client_rev     = !empty( $testimonial['client_rev'] ) ? $testimonial['client_rev'] : '';
                        ?>
                        <div data-index="<?php echo $count?>">
                            <div class="client_review_text text-center">
                                <h3><?php echo esc_html( $client_name );?></h3>
                                <h5><?php echo esc_html( $client_desig );?></h5>
                                <p><?php echo esc_html( $client_rev );?></p>
                            </div>
                        </div>
                        <?php
                        $count++;
                        }
                    }
                    ?>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!--::review_part end::-->
    <?php
    }

    public function load_widget_script(){
        if( \Elementor\Plugin::$instance->editor->is_edit_mode() === true  ) {
        ?>
        <script>
        ( function( $ ){
            $(document).ready(function() {
                
                $('.slider').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    speed: 500,
                    infinite: true,
                    asNavFor: '.slider-nav-thumbnails',
                    autoplay: true,
                    autoplaySpeed: 3000,
                    touchThreshold: 1000,
                    pauseOnFocus: true,
                    dots: false,
                });

                $('.slider-nav-thumbnails').slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    asNavFor: '.slider',
                    focusOnSelect: true,
                    infinite: true,
                    prevArrow: false,
                    nextArrow: false,
                    centerMode: true,
                    autoplaySpeed: 3000,
                    touchThreshold: 1000,
                    speed: 500,
                });

            });
        })(jQuery);
        </script>
        <?php 
        }
    }
}
