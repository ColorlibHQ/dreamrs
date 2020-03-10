<?php
namespace Dreamrselementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Utils;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;  
}


/**
 *
 * Dreamrs elementor about us section widget.
 *
 * @since 1.0
 */
class Dreamrs_Banner extends Widget_Base {

	public function get_name() {
		return 'dreamrs-banner';
	}

	public function get_title() {
		return __( 'Banner', 'dreamrs' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	public function get_categories() {
		return [ 'dreamrs-elements' ];
	}

	protected function _register_controls() {

        // ----------------------------------------  content ------------------------------
        $this->start_controls_section(
            'banner_section',
            [
                'label' => __( 'Banner Section Content', 'dreamrs' ),
            ]
        );
        $this->add_control(
            'banner_content',
            [
                'label'         => esc_html__( 'Banner Content', 'dreamrs' ),
                'type'          => Controls_Manager::WYSIWYG,
                'default'       => __( '<h5>Dream</h5><h2>Proparties <br>Now In City</h2><p>Lorem ipsum dolor sit consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna. </p>', 'dreamrs' )
            ]
        );

        $this->end_controls_section(); // End content


        /**
         * Style Tab
         * ------------------------------ Banner Style ------------------------------
         *
         */

        // Heading Style ==============================
        $this->start_controls_section(
            'color_sect', [
                'label' => __( 'Banner Text Style', 'dreamrs' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'sec_title_first_word_color', [
                'label'     => __( 'Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .banner_part .banner_text h5' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .banner_part .banner_text h2' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .banner_part .banner_text p' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();

        // Background Style ==============================
        $this->start_controls_section(
            'section_bg', [
                'label' => __( 'Style Background', 'dreamrs' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'sectionbg',
                'label' => __( 'Background', 'dreamrs' ),
                'types' => [ 'classic' ],
                'selector' => '{{WRAPPER}} .banner_part',
            ]
        );
        $this->end_controls_section();

        // Background After Image ==============================
        $this->start_controls_section(
            'section_overlay_color', [
                'label' => __( 'Section Overlay Color', 'dreamrs' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'sectionbg_overlay',
                'label' => __( 'Background', 'dreamrs' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .banner_part:after',
            ]
        );
        $this->end_controls_section();

	}

	protected function render() {
        $settings = $this->get_settings();
        $ban_content = !empty( $settings['banner_content'] ) ? $settings['banner_content'] : '';
    ?>

    <!--::banner part start::-->
   <section class="banner_part">
      <div class="container">
         <div class="row">
            <div class="col-lg-5 offset-lg-1 col-sm-8 offset-sm-2">
               <div class="banner_text aling-items-center">
                    <div class="banner_text_iner">
                        <?php
                            //Content ==============
                            if( $ban_content ){
                                echo wp_kses_post( wpautop( $ban_content ) );
                            }
                        ?>
                    </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!--::banner part end::--> 
    <?php
    }
	
}
