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
 * Dreamrs elementor few words section widget.
 *
 * @since 1.0
 */

class Dreamrs_Blog extends Widget_Base {

	public function get_name() {
		return 'dreamrs-blog';
	}

	public function get_title() {
		return __( 'Blog', 'dreamrs' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'dreamrs-elements' ];
    }
    
    public function dreamrs_featured_post_cat(){
        $post_cat_array = [];
        $cat_args = [
            'orderby' => 'name',
            'order'   => 'ASC'
        ];
        $categories = get_categories($cat_args);
        foreach($categories as $category) {
            $args = array(
                'showposts' => 2,
                'category_name' => $category->slug,
                'ignore_sticky_posts'=> 1
            );
            $posts = get_posts($args);
            if ($posts) {
                $post_cat_array[ $category->slug ] = $category->name;
            } else {
                return __( 'Select a different category, because this category have not enough posts.', 'dreamrs' );
            }
        }
    
        return $post_cat_array;

             
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
            'sec_title_first_word',
            [
                'label'         => esc_html__( 'Section Title Regular Word', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'our', 'dreamrs' )
            ]
        );
        $this->add_control(
            'sec_title_sec_word',
            [
                'label'         => esc_html__( 'Section Title Colorful Word', 'dreamrs' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__( 'blog', 'dreamrs' )
            ]
        );
        $this->end_controls_section(); 


        // Blog post settings
        $this->start_controls_section(
            'blog_post_sec',
            [
                'label' => __( 'Blog Post Settings', 'dreamrs' ),
            ]
        );
        $this->add_control(
            'post_cat',
            [
                'label'         => esc_html__( 'Select Category', 'dreamrs' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'engineering',
                'description'   => esc_html__( 'Please use the featured images size 1159px width & 811px height or more for better look.', 'dreamrs' ),
                'options'       => $this->dreamrs_featured_post_cat()
            ]
        );
		$this->add_control(
			'post_order',
			[
				'label'         => esc_html__( 'Post Order', 'dreamrs' ),
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

        $this->end_controls_section(); // End few words content


        /**
         * Style Tab
         * ------------------------------ Background Style ------------------------------
         *
         */

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
                    '{{WRAPPER}} .blog_part .blog_part_tittle h2 span' => 'color: {{VALUE}};',
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
            'item_bg_color', [
                'label'     => __( 'Item BG Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blog_part .single_blog .single_appartment_content' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .blog_part .right_single_blog .single_blog .media-body' => 'background-color: {{VALUE}};',
                ],
            ]
        ); 

        $this->add_control(
            'blog_cat_color', [
                'label'     => __( 'Blog Category Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blog_part .single_blog .single_appartment_content p a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .blog_part .right_single_blog .single_blog .media-body p a' => 'color: {{VALUE}};',
                ],
            ]
        ); 

        $this->add_control(
            'blog_title_color', [
                'label'     => __( 'Blog Title Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blog_part .right_single_blog .single_blog .media-body a h5' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .blog_part .single_blog .single_appartment_content a h4' => 'color: {{VALUE}};',
                ],
            ]
        ); 

        $this->add_control(
            'blog_title_hover_color', [
                'label'     => __( 'Blog Title Hover Color', 'dreamrs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blog_part .right_single_blog .single_blog .media-body a:hover h5' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .blog_part .single_blog .single_appartment_content a:hover h4' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .blog_part',
            ]
        );

        $this->end_controls_section();
	}

	protected function render() {

    $settings  = $this->get_settings();
    $sec_title_color    = !empty( $settings['sec_title_first_word'] ) ? $settings['sec_title_first_word'] : '';
    $sec_title_reg      = !empty( $settings['sec_title_sec_word'] ) ? $settings['sec_title_sec_word'] : '';
    $post_order = !empty( $settings['post_order'] ) ? $settings['post_order'] : '';
    $post_order = $post_order == 'yes' ? 'DESC' : 'ASC';
    ?>

    <!--::blog_part start::-->
   <div class="blog_part padding_bottom">
      <div class="container">
         <div class="row">
            <div class="col-md-7 col-lg-5">
               <div class="blog_part_tittle">
                  <h2><?php echo esc_html( $sec_title_color )?> <span><?php echo esc_html( $sec_title_reg )?></span></h2>
               </div>
            </div>
         </div>
         <div class="row">
            <?php
                if( function_exists( 'dreamrs_latest_blog' ) ) {
                    dreamrs_latest_blog( $post_order );
                }
            ?>
         </div>
      </div>
   </div>
   <!--::blog_part end::-->
    <?php
	}
}
