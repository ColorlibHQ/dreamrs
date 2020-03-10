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
 * Dreamrs elementor Team Member section widget.
 *
 * @since 1.0
 */
class Dreamrs_Services extends Widget_Base {

	public function get_name() {
		return 'dreamrs-services';
	}

	public function get_title() {
		return __( 'Services', 'dreamrs' );
	}

	public function get_icon() {
		return 'eicon-info-box';
	}

	public function get_categories() {
		return [ 'dreamrs-elements' ];
	}

	protected function _register_controls() {
        
		// ----------------------------------------   Services content ------------------------------
		$this->start_controls_section(
			'services_block',
			[
				'label' => __( 'Services', 'dreamrs' ),
			]
        );

        $this->add_control(
            'ser_title_first_word',
            [
                'label'         => esc_html__( 'Section Title Regular Word', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'our', 'dreamrs' )
            ]
        );
        $this->add_control(
            'ser_title_sec_word',
            [
                'label'         => esc_html__( 'Section Title Colorful Word', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'services', 'dreamrs' )
            ]
        );
		$this->add_control(
            'services_content', [
                'label' => __( 'Create Service', 'dreamrs' ),
                'type'  => Controls_Manager::REPEATER,
                'title_field' => '{{{ label }}}',
                'fields' => [
                    [
                        'name'      => 'icon',
                        'label'     => __( 'Select Icon', 'dreamrs' ),
                        'type'      => Controls_Manager::SELECT,
                        'default'   => 'service_icon1',
                        'options' => [
                            'service_icon1' => __( 'Service Icon 1', 'dreamrs' ),
                            'service_icon2' => __( 'Service Icon 2', 'dreamrs' ),
                            'service_icon3' => __( 'Service Icon 3', 'dreamrs' ),
                            'service_icon4' => __( 'Service Icon 4', 'dreamrs' ),
                        ]
                    ],
                    [
                        'name'  => 'label',
                        'label' => __( 'Title', 'dreamrs' ),
                        'type'  => Controls_Manager::TEXT,
                        'label_block' => true,
                        'default' => __( 'house Planning', 'dreamrs' )
                    ],
                    [
                        'name'      => 'desc',
                        'label'     => __( 'Descriptions', 'dreamrs' ),
                        'type'      => Controls_Manager::TEXTAREA,
                        'default'   => __( 'Lorem ipsum dolor sit amet consectetur elit seiusmod tempor incididunt', 'dreamrs' )
                    ],
                ],
            ]
        );

        $this->end_controls_section(); // End Services content
        

        // ----------------------------------------   Right content ------------------------------
		$this->start_controls_section(
			'right_content_block',
			[
				'label' => __( 'Right Content', 'dreamrs' ),
			]
        );

        $this->add_control(
            'right_title',
            [
                'label'         => esc_html__( 'Right Title', 'dreamrs' ),
                'type'          => Controls_Manager::TEXTAREA,
                'label_block'   => true,
                'default'       => esc_html__( 'Hello City We are leader in', 'dreamrs' )
            ]
        );

        $this->add_control(
            'right_title_color',
            [
                'label'         => esc_html__( 'Right Title Colorful', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'Services', 'dreamrs' )
            ]
        );

        $this->add_control(
            'right_txt',
            [
                'label'         => esc_html__( 'Right Text', 'dreamrs' ),
                'type'          => Controls_Manager::TEXTAREA,
                'label_block'   => true,
                'default'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipi scing elit, sed do eiusmodtemporincididunt u labore et dolore magna aliqua. Quis ipsum pendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel', 'dreamrs' )
            ]
        );

        $this->add_control(
            'right_btn',
            [
                'label'         => esc_html__( 'Button Label', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'learn more', 'dreamrs' )
            ]
        );

        $this->add_control(
            'right_btn_url',
            [
                'label'         => esc_html__( 'Button URL', 'dreamrs' ),
                'type'          => Controls_Manager::URL,
                'label_block'   => true,
                'default'       => [
                    'url'       => '#'
                ]
            ]
        );

        $this->end_controls_section(); // End Right content

        /**
         * Style Tab
         * ------------------------------ Style Tab Content ------------------------------
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
                    '{{WRAPPER}} .service_part .section_tittle h2 span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .service_part .section_tittle h2:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'right_cont_colorful_title_color', [
                'label'     => __( 'Right Content Colorful Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service_part .service_text h2 span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'right_txt_color', [
                'label'     => __( 'Right Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service_part .service_text p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        // Single Service Color Settings ==============================
        $this->start_controls_section(
            'single_serv_color_sett', [
                'label' => __( 'Single Service Color Settings', 'dreamrs' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
 
        $this->add_control(
            'item_bg_color', [
                'label'     => __( 'Item BG Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service_part .single_service_text' => 'background-color: {{VALUE}};',
                ],
            ]
        ); 
 
        $this->add_control(
            'icon_color', [
                'label'     => __( 'Icon Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service_part .single_service_text svg path' => 'fill: {{VALUE}};',
                ],
            ]
        ); 

        $this->add_control(
            'item_title_color', [
                'label'     => __( 'Item Title Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service_part .single_service_text h4' => 'color: {{VALUE}};',
                ],
            ]
        ); 

        $this->add_control(
            'item_txt_color', [
                'label'     => __( 'Item Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service_part .single_service_text p' => 'color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .service_part .service_text .btn_1' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_txt_color', [
                'label'     => __( 'Button Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service_part .service_text .btn_1' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_hover_txt_color', [
                'label'     => __( 'Button Hover Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service_part .service_text .btn_1:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_hover_bg_color', [
                'label'     => __( 'Button Hover BG Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service_part .service_text .btn_1:hover' => 'background-color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .service_part',
            ]
        );
        $this->end_controls_section();

	}

	protected function render() {

    $settings           = $this->get_settings();
    $ser_title_regular  = !empty( $settings['ser_title_first_word'] ) ? $settings['ser_title_first_word'] : '';
    $ser_title_color    = !empty( $settings['ser_title_sec_word'] ) ? $settings['ser_title_sec_word'] : '';
    $services           = !empty( $settings['services_content'] ) ? $settings['services_content'] : '';
    $right_title        = !empty( $settings['right_title'] ) ? $settings['right_title'] : '';
    $right_title_color  = !empty( $settings['right_title_color'] ) ? $settings['right_title_color'] : '';
    $right_txt          = !empty( $settings['right_txt'] ) ? $settings['right_txt'] : '';
    $right_btn          = !empty( $settings['right_btn'] ) ? $settings['right_btn'] : '';
    $right_btn_url      = !empty( $settings['right_btn_url']['url'] ) ? $settings['right_btn_url']['url'] : '';
    $dynamic_class      = ! is_front_page() ? ' section-padding' : '';
    ?>

    <!--::service part end::-->
   <section class="service_part<?php echo $dynamic_class?>">
      <div class="container">
         <div class="row justify-content-between align-items-center">
            <div class="col-lg-7 col-xl-6">
               <div class="section_tittle">
                    <h2>
                        <?php 
                            if ( $ser_title_regular ) {
                                echo esc_html( $ser_title_regular );
                            }
                            if ( $ser_title_color ) {
                                echo ' <span>' . esc_html( $ser_title_color ) . '</span>';
                            }
                        ?>
                    </h2>
               </div>
               <div class="service_part_iner">
                  <div class="row">
                    <?php
                    if( is_array( $services ) && count( $services ) > 0 ){
                        foreach ( $services as $service ) {
                            $servIcon      = !empty( $service['icon'] ) ? $service['icon'] : '';
                            $service_title = !empty( $service['label'] ) ? $service['label'] : '';
                            $service_desc  = !empty( $service['desc'] ) ? $service['desc'] : '';
                    ?>
                    <div class="col-lg-6 col-sm-6">
                        <div class="single_service_text ">
                           <?php echo get_dreamrs_svg_icon( $servIcon )?>
                           <h4><?php echo esc_html( $service_title )?></h4>
                           <p><?php echo esc_html( $service_desc )?> </p>
                        </div>
                    </div>
                    <?php
                        }
                    }
                    ?>
                  </div>
               </div>
            </div>
            <div class="col-lg-4 col-sm-10">
               <div class="service_text">
                  <h2>
                    <?php 
                        if ( $right_title ) {
                            echo esc_html( $right_title );
                        }
                        if ( $right_title_color ) {
                            echo ' <span> ' . esc_html( $right_title_color ) . '</span>';
                        }
                    ?>
                  </h2>
                  <p><?php echo esc_html( $right_txt )?> </p>
                  <a href="<?php echo esc_url( $right_btn_url )?>" class="btn_1"><?php echo esc_html( $right_btn )?></a>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!--::service part end::-->
    <?php
    }
}
