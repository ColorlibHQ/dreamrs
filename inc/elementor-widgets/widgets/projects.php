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
 * elementor projects section widget.
 *
 * @since 1.0
 */
class Dreamrs_Projects extends Widget_Base {

	public function get_name() {
		return 'dreamrs-projects';
	}

	public function get_title() {
		return __( 'Recent Works', 'dreamrs' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'dreamrs-elements' ];
	}

	protected function _register_controls() {

        $this->start_controls_section(
			'section_heading',
			[
				'label' => __( 'Section Heading', 'dreamrs' ),
			]
        );
        
        $this->add_control(
            'sec_title_first_word',
            [
                'label'         => esc_html__( 'Section Title Colorful Word', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'our', 'dreamrs' )
            ]
        );
        $this->add_control(
            'sec_title_sec_word',
            [
                'label'         => esc_html__( 'Section Title Regular Word', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'project', 'dreamrs' )
            ]
        );
        $this->add_control(
            'sec_txt_sep',
            [
                'label'     => __( 'Big Title Area', 'dreamrs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'big_title',
            [
                'label'         => esc_html__( 'Big Title', 'dreamrs' ),
                'type'          => Controls_Manager::TEXTAREA,
                'label_block'   => true,
                'default'       => esc_html__( 'Hello City We are leader in', 'dreamrs' )
            ]
        );
        $this->add_control(
            'big_title_color',
            [
                'label'         => esc_html__( 'Big Title Colorful', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'Projects', 'dreamrs' )
            ]
        );
		$this->end_controls_section(); 


        // ----------------------------------------  Projects Content ------------------------------
        $this->start_controls_section(
            'menu_tab_sec',
            [
                'label' => __( 'Project Settings', 'dreamrs' ),
            ]
        );
		$this->add_control(
			'proj_filter',
			[
				'label'         => esc_html__( 'Show/Hide Project Filter', 'dreamrs' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block'   => false,
				'label_on'      => 'SHOW',
				'label_off'     => 'HIDE',
                'default'       => 'yes',
                'options'       => [
                    'no'        => 'HIDE',
                    'yes'       => 'SHOW'
                ]
			]
		);
        $this->add_control(
			'proj_order',
			[
				'label'         => esc_html__( 'Project Order', 'dreamrs' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block'   => false,
				'label_on'      => 'ASC',
				'label_off'     => 'DESC',
                'default'       => 'yes',
                'options'       => [
                    'no'        => 'ASC',
                    'yes'       => 'DESC'
                ]
			]
		);

        $this->end_controls_section(); // End projects content

        //------------------------------ Color Settings ------------------------------
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
                    '{{WRAPPER}} .portfolio_area .section_tittle h2 span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'big_title_colorful_title_color', [
                'label'     => __( 'Big Title Colorful Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio_area .portfolio-filter h2 span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'portfolio_item_style_sep',
            [
                'label'     => __( 'Single Portfolio Item Color Settings', 'dreamrs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'portfolio_item_short_info_color', [
                'label'     => __( 'Portfolio Item Short Info Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio_area .portfolio_box .short_info p' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'portfolio_title_color', [
                'label'     => __( 'Portfolio Title Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio_area .portfolio_box .short_info h4 a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'portfolio_title_hover_color', [
                'label'     => __( 'Portfolio Title Hover Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio_area .portfolio_box .short_info h4 a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();


        // Filtering Button Style ==============================
        $this->start_controls_section(
            'btn_styles', [
                'label' => __( 'Filtering Button Styles', 'dreamrs' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'btn_hover_txt_color', [
                'label'     => __( 'Button Hover Text Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio_area .portfolio-filter a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'active_btn_bg_color', [
                'label'     => __( 'Active Button BG Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio_area .portfolio-filter .active' => 'border-color: {{VALUE}}; background-color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .portfolio_area',
            ]
        );

        $this->end_controls_section();

	}

	protected function render() {

    $settings           = $this->get_settings();
    $sec_title_color    = !empty( $settings['sec_title_first_word'] ) ? $settings['sec_title_first_word'] : '';
    $sec_title_reg      = !empty( $settings['sec_title_sec_word'] ) ? $settings['sec_title_sec_word'] : '';
    $big_title          = !empty( $settings['big_title'] ) ? $settings['big_title'] : '';
    $big_title_color    = !empty( $settings['big_title_color'] ) ? $settings['big_title_color'] : '';
    $proj_title         = $big_title . ' <span>' . $big_title_color . '</span>';
    $proj_filter        = !empty( $settings['proj_filter'] ) ? $settings['proj_filter'] : '';
    $proj_order         = !empty( $settings['proj_order'] ) ? $settings['proj_order'] : '';
    $proj_order         = $proj_order == 'yes' ? 'DESC' : 'ASC';
    $dynamic_class      = is_front_page() ? 'pt_30 padding_bottom' : 'section-padding';
    ?>

    <!--::project part start::-->
    <section class="portfolio_area <?php echo $dynamic_class?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_tittle">
                        <h2><span><?php echo esc_html( $sec_title_color )?></span> <?php echo esc_html( $sec_title_reg )?></h2>
                    </div>

                    <?php
                        //Load Portfolios ==============
                        dreamrs_portfolio_section( $proj_title, $proj_filter, $proj_order );
                    ?>
                </div>
            </div>
        </div>
    </section>
    <!--::project part end::-->
    <?php

    }
}
