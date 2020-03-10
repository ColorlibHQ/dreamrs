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
 * elementor apartments section widget.
 *
 * @since 1.0
 */
class Dreamrs_Apartments extends Widget_Base {

	public function get_name() {
		return 'dreamrs-apartments';
	}

	public function get_title() {
		return __( 'Apartments', 'dreamrs' );
	}

	public function get_icon() {
		return 'eicon-navigator';
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
                'default'       => esc_html__( 'Luxuries', 'dreamrs' )
            ]
        );
        $this->add_control(
            'sec_title_sec_word',
            [
                'label'         => esc_html__( 'Section Title Regular Word', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'Apartment', 'dreamrs' )
            ]
        );
		$this->end_controls_section(); 


        // ----------------------------------------  Apartments Content ------------------------------
        $this->start_controls_section(
            'menu_tab_sec',
            [
                'label' => __( 'Apartment Settings', 'dreamrs' ),
            ]
        );
        $this->add_control(
			'apart_order',
			[
				'label'         => esc_html__( 'Apartment Order', 'dreamrs' ),
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

        $this->end_controls_section(); // End apartments content

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
                    '{{WRAPPER}} .project_gallery .project_gallery_tittle h2 span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'sing_item_col_sett_sep',
            [
                'label'     => __( 'Single Items Color Settings Separator', 'dreamrs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
 
        $this->add_control(
            'item_short_info_color', [
                'label'     => __( 'Item Short Info Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .project_gallery .project_gallery_hover_text p' => 'color: {{VALUE}};',
                ],
            ]
        ); 

        $this->add_control(
            'item_title_color', [
                'label'     => __( 'Item Title Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .project_gallery .project_gallery_hover_text h3 a' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .project_gallery',
            ]
        );

        $this->end_controls_section();

	}

	protected function render() {
        
    // call load widget script
    $this->load_widget_script();

    $settings           = $this->get_settings();
    $sec_title_color    = !empty( $settings['sec_title_first_word'] ) ? $settings['sec_title_first_word'] : '';
    $sec_title_reg      = !empty( $settings['sec_title_sec_word'] ) ? $settings['sec_title_sec_word'] : '';
    $apart_order        = !empty( $settings['apart_order'] ) ? $settings['apart_order'] : '';
    $apart_order        = $apart_order == 'yes' ? 'DESC' : 'ASC';
    ?>

   <!--::apartment part start::-->
   <section class="project_gallery section-padding">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <div class="project_gallery_tittle">
                  <h2><span><?php echo esc_html( $sec_title_color )?></span> <?php echo esc_html( $sec_title_reg )?></h2>
               </div>
               <div class="grid">
                  <div class="grid-sizer"></div>

                  <?php
                    //Load Apartments ==============
                    dreamrs_apartment_section( $apart_order );
                  ?>
               </div>
            </div>
         </div>
      </div>
   </section>
    <!--::apartment part end::-->
    <?php

    }

    public function load_widget_script(){
        if( \Elementor\Plugin::$instance->editor->is_edit_mode() === true  ) {
        ?>
        <script>
        ( function( $ ){
            $(document).ready(function() {
                //masonry js
                $('.grid').masonry({
                    itemSelector: '.grid-item',
                    columnWidth: '.grid-sizer',
                    percentPosition: true
                });
            });
        })(jQuery);
        </script>
        <?php 
        }
    }
}
