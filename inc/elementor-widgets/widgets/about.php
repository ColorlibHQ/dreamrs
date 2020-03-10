<?php
namespace Dreamrselementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
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
 * Dreamrs elementor section widget.
 *
 * @since 1.0
 */
class Dreamrs_About extends Widget_Base {

	public function get_name() {
		return 'dreamrs-about';
	}

	public function get_title() {
		return __( 'About', 'dreamrs' );
	}

	public function get_icon() {
		return 'eicon-thumbnails-half';
	}

	public function get_categories() {
		return [ 'dreamrs-elements' ];
	}

	protected function _register_controls() {

        
		// ----------------------------------------  About content ------------------------------
		$this->start_controls_section(
			'about_left_content',
			[
				'label' => __( 'About Left Contents', 'dreamrs' ),
			]
		);
        
        $this->add_control(
            'about_title_first_word',
            [
                'label'         => esc_html__( 'Section Title Colorful Word', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'about', 'dreamrs' )
            ]
        );
        $this->add_control(
            'about_title_sec_word',
            [
                'label'         => esc_html__( 'Section Title Regular Word', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'us', 'dreamrs' )
            ]
        );
        $this->add_control(
			'img_left',
			[
				'label'         => esc_html__( 'Image Left', 'dreamrs' ),
                'type'          => Controls_Manager::MEDIA,
                'default'       => [
                    'url'       => Utils::get_placeholder_image_src(),
                ]
			]
        );
        $this->end_controls_section(); // End about left content


        // About right contents
        $this->start_controls_section(
			'about_right_content',
			[
				'label' => __( 'About Right Content', 'dreamrs' ),
			]
		);
        $this->add_control(
            'about_right_title',
            [
                'label'         => esc_html__( 'Right Title', 'dreamrs' ),
                'type'          => Controls_Manager::TEXTAREA,
                'label_block'   => true,
                'default'       => esc_html__( 'Hello City We are leader in', 'dreamrs' )
            ]
        );
        $this->add_control(
            'about_right_title_color',
            [
                'label'         => esc_html__( 'Right Title Colorful', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'properties', 'dreamrs' )
            ]
        );
        $this->add_control(
            'about_right_txt',
            [
                'label'         => esc_html__( 'Right Texts', 'dreamrs' ),
                'type'          => Controls_Manager::TEXTAREA,
                'label_block'   => true,
                'default'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed ips eiusmod tempor incididunt ut labore et dolore magna aliqua isuspendisse ultrices gravida. Risus.', 'dreamrs' )
            ]
        );
        $this->add_control(
            'about_btnlabel',
            [
                'label'         => esc_html__( 'Button Label', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'learn More', 'dreamrs' )
            ]
        );
        $this->add_control(
            'about_btnurl',
            [
                'label'         => esc_html__( 'Button Url', 'dreamrs' ),
                'type'          => Controls_Manager::URL,
                'show_external' => false
            ]
        );
        
        $this->end_controls_section(); // End about content


        // About counter section
        $this->start_controls_section(
			'about_counter_section',
			[
				'label' => __( 'About Counter Section', 'dreamrs' ),
			]
		);
        
		$this->add_control(
            'counters_content', [
                'label' => __( 'Create Counter', 'dreamrs' ),
                'type'  => Controls_Manager::REPEATER,
                'title_field' => '{{{ counter_title }}}',
                'fields' => [
                    [
                        'name'  => 'counter_val',
                        'label' => __( 'Counter Value', 'dreamrs' ),
                        'type'  => Controls_Manager::TEXT,
                        'label_block' => true,
                        'default' => __( '100', 'dreamrs' )
                    ],
                    [
                        'name'      => 'counter_title',
                        'label'     => __( 'Counter Title', 'dreamrs' ),
                        'type'      => Controls_Manager::TEXT,
                        'default'   => __( 'projects', 'dreamrs' )
                    ],
                ],
            ]
        );

		$this->end_controls_section(); // End counter content
        

        /**
         * Style Tab
         * ------------------------------ About Style Settings ------------------------------
         *
         */

        // Color Settings ==============================
        $this->start_controls_section(
            'sec_title_colors', [
                'label' => __( 'Text Color', 'dreamrs' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'colorful_title_color', [
                'label'     => __( 'Section Title Colorful Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about_part .section_tittle h2 span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .about_part .section_tittle h2:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'right_cont_colorful_title_color', [
                'label'     => __( 'Right Content Colorful Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about_part .about_text h2 span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'right_txt_color', [
                'label'     => __( 'Right Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about_part .about_text p' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'count_txt_sep',
            [
                'label'     => __( 'Counter Color Setting Separator', 'dreamrs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'count_val_col', [
                'label'     => __( 'Counter Value Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about_part .about_text .about_part_counter .single_counter span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'count_txt_col', [
                'label'     => __( 'Counter Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about_part .about_text .about_part_counter .single_counter p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Pattern Settings ==============================
        $this->start_controls_section(
            'pattern_bg', [
                'label' => __( 'Style Pattern Background', 'dreamrs' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pattern_img_sep',
            [
                'label'     => __( 'Pattern Image', 'dreamrs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pattern_img',
                'label' => __( 'Background', 'dreamrs' ),
                'types' => [ 'classic' ],
                'selector' => '{{WRAPPER}} .about_part .about_img:after',
            ]
        );
        $this->end_controls_section();

        // Button Style ==============================
        $this->start_controls_section(
            'btn_styles', [
                'label' => __( 'Button Styles', 'dreamrs' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'btn_bg_color', [
                'label'     => __( 'Button BG Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about_part .about_text .btn_1' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_txt_color', [
                'label'     => __( 'Button Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about_part .about_text .btn_1' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_hover_txt_color', [
                'label'     => __( 'Button Hover Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about_part .about_text .btn_1:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_hover_bg_color', [
                'label'     => __( 'Button Hover BG Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about_part .about_text .btn_1:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        

        /**
         * Style Tab
         * ------------------------------ Background Style ------------------------------
         *
         */
        $this->start_controls_section(
            'section_bg', [
                'label' => __( 'Style Background', 'dreamrs' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'section_bgheading',
            [
                'label'     => __( 'Background Settings', 'dreamrs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'sectionbg',
                'label' => __( 'Background', 'dreamrs' ),
                'types' => [ 'classic' ],
                'selector' => '{{WRAPPER}} .about_part',
            ]
        );

        $this->end_controls_section();


	}

	protected function render() {
        $settings             = $this->get_settings();

        // call load widget script
        $this->load_widget_script();

        $sec_title_color      = !empty( $settings['about_title_first_word'] ) ? $settings['about_title_first_word'] : '';		
        $sec_title            = !empty( $settings['about_title_sec_word'] ) ? $settings['about_title_sec_word'] : '';		
        $left_img             = !empty( $settings['img_left']['id'] ) ? wp_get_attachment_image( $settings['img_left']['id'], 'dreamrs_about_section_457x500', false, array( 'alt' => 'about image left' ) ) : '';
        $right_title          = !empty( $settings['about_right_title'] ) ? $settings['about_right_title'] : '';
        $right_title_color    = !empty( $settings['about_right_title_color'] ) ? $settings['about_right_title_color'] : '';
        $right_txt            = !empty( $settings['about_right_txt'] ) ? $settings['about_right_txt'] : '';
        $button_label         = !empty( $settings['about_btnlabel'] ) ? $settings['about_btnlabel'] : '';
        $button_url           = !empty( $settings['about_btnurl']['url'] ) ? $settings['about_btnurl']['url'] : '';
        $counters             = !empty( $settings['counters_content'] ) ? $settings['counters_content'] : '';
        ?>

    <!--::about part start::-->
   <section class="about_part section-padding">
      <div class="container">
         <div class="row">
            <div class="section_tittle">
               <h2><?php echo '<span>' . esc_html( $sec_title_color ) . '</span> ' . esc_html( $sec_title ) ?></h2>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-6 col-md-6">
               <div class="about_img">
                    <?php
                        if( $left_img ){
                            echo wp_kses_post( $left_img );
                        }
                    ?>
               </div>
            </div>
            <div class="offset-lg-1 col-lg-5 col-sm-8 col-md-6">
               <div class="about_text">
                  <h2><?php echo esc_html( $right_title ) . '<span> ' . esc_html( $right_title_color ) . '</span>'; ?></h2>
                  <p><?php echo esc_html( $right_txt )?></p>
                  <a href="<?php echo esc_url( $button_url )?>" class="btn_1"><?php echo esc_html( $button_label )?></a>
                  
                  <div class="about_part_counter">
                    <?php
                    if( is_array( $counters ) && count( $counters ) > 0 ){
                        foreach ( $counters as $counter ) {
                            $counter_val    = !empty( $counter['counter_val'] ) ? $counter['counter_val'] : '';
                            $counter_title  = !empty( $counter['counter_title'] ) ? $counter['counter_title'] : '';
                    ?>
                    <div class="single_counter">
                        <span class="counter"><?php echo esc_html( $counter_val )?></span>
                        <p><?php echo esc_html( $counter_title )?></p>
                    </div>
                    <?php
                        }
                    }
                    ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!--::about part end::-->
    <?php

    }


    public function load_widget_script(){
        if( \Elementor\Plugin::$instance->editor->is_edit_mode() === true  ) {
        ?>
        <script>
        ( function( $ ){
            $(document).ready(function() {
                //counter up
                $('.counter').counterUp({
                    delay: 10,
                    time: 2000
                });
            });
        })(jQuery);
        </script>
        <?php 
        }
    }

}
