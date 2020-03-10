<?php 
/**
 * @Packge     : Dreamrs
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 * Customizer section fields
 *
 */

/***********************************
 * General Section Fields
 ***********************************/

 // Theme color field
Epsilon_Customizer::add_field(
    'dreamrs_theme_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Theme Color', 'dreamrs' ),
        'description' => esc_html__( 'Select the theme color.', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_general_section',
        'default'     => '#ff3334',
    )
);
 
// Header background color field
Epsilon_Customizer::add_field(
    'dreamrs_header_bg_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Sticky Header BG Color', 'dreamrs' ),
        'description' => esc_html__( 'Select the header background color.', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_header_section',
        'default'     => '#fff',
    )
);

// Header nav menu color field
Epsilon_Customizer::add_field(
    'dreamrs_header_menu_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Header menu color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_header_section',
        'default'     => '#000',
    )
);

// Header nav menu hover color field
Epsilon_Customizer::add_field(
    'dreamrs_header_menu_hover_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Header menu hover color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_header_section',
        'default'     => '#fe5c24',
    )
);

// Header dropdown menu bg color field
Epsilon_Customizer::add_field(
    'dreamrs_header_drop_menu_bg_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Dropdown menu BG color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_header_section',
        'default'     => '#fafafa',
    )
);

// Header dropdown menu color field
Epsilon_Customizer::add_field(
    'dreamrs_header_drop_menu_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Dropdown menu color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_header_section',
        'default'     => '#000',
    )
);

// Header dropdown menu hover color field
Epsilon_Customizer::add_field(
    'dreamrs_header_drop_menu_hover_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Dropdown menu hover color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_header_section',
        'default'     => '#000',
    )
);

/***********************************
 * Blog Section Fields
 ***********************************/
 
// Post excerpt length field
Epsilon_Customizer::add_field(
    'dreamrs_excerpt_length',
    array(
        'type'        => 'text',
        'label'       => esc_html__( 'Set post excerpt length', 'dreamrs' ),
        'description' => esc_html__( 'Set post excerpt length.', 'dreamrs' ),
        'section'     => 'dreamrs_blog_section',
        'sanitize_callback' => 'sanitize_text_field',
        'default'     => '30',
    )
);

// Blog single page social share icon
Epsilon_Customizer::add_field(
    'dreamrs_blog_meta',
    array(
        'type'        => 'epsilon-toggle',
        'label'       => esc_html__( 'Blog page post meta show/hide', 'dreamrs' ),
        'section'     => 'dreamrs_blog_section',
        'default'     => true
    )
);
Epsilon_Customizer::add_field(
    'dreamrs_like_btn',
    array(
        'type'        => 'epsilon-toggle',
        'label'       => esc_html__( 'Blog Single Page Like Button show/hide', 'dreamrs' ),
        'section'     => 'dreamrs_blog_section',
        'default'     => true
    )
);
Epsilon_Customizer::add_field(
    'dreamrs_blog_share',
    array(
        'type'        => 'epsilon-toggle',
        'label'       => esc_html__( 'Blog Single Page Share show/hide', 'dreamrs' ),
        'section'     => 'dreamrs_blog_section',
        'default'     => true
    )
);

/***********************************
 * 404 Page Section Fields
 ***********************************/

// 404 text #1 field
Epsilon_Customizer::add_field(
    'dreamrs_fof_titleone',
    array(
        'type'              => 'text',
        'label'             => esc_html__( '404 Text #1', 'dreamrs' ),
        'section'           => 'dreamrs_fof_section',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'Say Hello.'
    )
);
// 404 text #2 field
Epsilon_Customizer::add_field(
    'dreamrs_fof_titletwo',
    array(
        'type'              => 'text',
        'label'             => esc_html__( '404 Text #2', 'dreamrs' ),
        'section'           => 'dreamrs_fof_section',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'Say Hello.'
    )
);
// 404 text #1 color field
Epsilon_Customizer::add_field(
    'dreamrs_fof_textone_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( '404 Text #1 Color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_fof_section',
        'default'     => '#000000',
    )
);
// 404 text #2 color field
Epsilon_Customizer::add_field(
    'dreamrs_fof_texttwo_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( '404 Text #2 Color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_fof_section',
        'default'     => '#656565',
    )
);
// 404 background color field
Epsilon_Customizer::add_field(
    'dreamrs_fof_bg_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( '404 Page Background Color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_fof_section',
        'default'     => '#fff',
    )
);

/***********************************
 * Footer Section Fields
 ***********************************/

// Footer Widget section
Epsilon_Customizer::add_field(
    'footer_widget_separator',
    array(
        'type'        => 'epsilon-separator',
        'label'       => esc_html__( 'Footer Widget Section', 'dreamrs' ),
        'section'     => 'dreamrs_footer_section',

    )
);

// Footer widget toggle field
Epsilon_Customizer::add_field(
    'dreamrs_footer_widget_toggle',
    array(
        'type'        => 'epsilon-toggle',
        'label'       => esc_html__( 'Footer widget show/hide', 'dreamrs' ),
        'description' => esc_html__( 'Toggle to display footer widgets.', 'dreamrs' ),
        'section'     => 'dreamrs_footer_section',
        'default'     => true,
    )
);

// Footer Copyright section
Epsilon_Customizer::add_field(
    'dreamrs_footer_copyright_separator',
    array(
        'type'        => 'epsilon-separator',
        'label'       => esc_html__( 'Footer Copyright Section', 'dreamrs' ),
        'section'     => 'dreamrs_footer_section',
        'default'     => true,

    )
);

// Footer copyright text field
// Copy right text
$url = 'https://colorlib.com/';
$copyText = sprintf( __( 'Theme by %s colorlib %s Copyright &copy; %s  |  All rights reserved.', 'dreamrs' ), '<a target="_blank" href="' . esc_url( $url ) . '">', '</a>', date( 'Y' ) );
Epsilon_Customizer::add_field(
    'dreamrs_footer_copyright_text',
    array(
        'type'        => 'epsilon-text-editor',
        'label'       => esc_html__( 'Footer copyright text', 'dreamrs' ),
        'section'     => 'dreamrs_footer_section',
        'default'     => wp_kses_post( $copyText ),
    )
);

// Footer widget background color field
Epsilon_Customizer::add_field(
    'dreamrs_footer_bg_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Background Color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_footer_section',
        'default'     => '#030305',
    )
);

// Footer widget text color field
Epsilon_Customizer::add_field(
    'dreamrs_footer_widget_text_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Text Color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_footer_section',
        'default'     => '#888888',
    )
);

// Footer widget title color field
Epsilon_Customizer::add_field(
    'dreamrs_footer_widget_title_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Widget Title Color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_footer_section',
        'default'     => '#ffffff',
    )
);

// Footer widget anchor color field
Epsilon_Customizer::add_field(
    'dreamrs_footer_widget_anchor_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Anchor Color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_footer_section',
        'default'     => '#7f7f7f',
    )
);

// Footer widget anchor hover color field
Epsilon_Customizer::add_field(
    'dreamrs_footer_widget_anchor_hover_color',
    array(
        'type'        => 'epsilon-color-picker',
        'label'       => esc_html__( 'Footer Anchor Hover Color', 'dreamrs' ),
        'sanitize_callback' => 'sanitize_text_field',
        'section'     => 'dreamrs_footer_section',
        'default'     => '#ff3334',
    )
);

