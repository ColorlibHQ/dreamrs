<?php
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit( 'Direct script access denied.' );
} 
/**
 * @Packge     : DREAMRS
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */
 
 
// enqueue css
function dreamrs_common_custom_css(){
    
    wp_enqueue_style( 'dreamrs-common', get_template_directory_uri().'/assets/css/dynamic.css' );
		$header_bg         		  = esc_url( get_header_image() );
		$header_bg_img 			  = !empty( $header_bg ) ? 'background-image: url('.esc_url( $header_bg ).')' : '';

		$themeColor     		  = dreamrs_opt( 'dreamrs_theme_color' );

		$buttonBorderColor     	  = dreamrs_opt( 'dreamrs_button_border_color' );
		$hoverColor     	  	  = dreamrs_opt( 'dreamrs_hover_color');

		$headerTop_bg     		  = dreamrs_opt( 'dreamrs_top_header_bg_color' );
		$headerTop_col     		  = dreamrs_opt( 'dreamrs_top_header_color' );

		$headerTopBg      		  = dreamrs_opt( 'dreamrs_top_header_bg_color');
		$headerBg          		  = dreamrs_opt( 'dreamrs_header_bg_color');
		$menuColor          	  = dreamrs_opt( 'dreamrs_header_menu_color' );
		$menuHoverColor           = dreamrs_opt( 'dreamrs_header_menu_hover_color' ) != '#fe5c24' ? dreamrs_opt('dreamrs_header_menu_hover_color') : $themeColor;
		$dropMenuBgColor          = dreamrs_opt( 'dreamrs_header_drop_menu_bg_color' ) != '#ab7636' ? dreamrs_opt('dreamrs_header_drop_menu_bg_color') : $themeColor;
		$dropMenuColor            = dreamrs_opt( 'dreamrs_header_drop_menu_color' );
		$dropMenuHovColor         = dreamrs_opt( 'dreamrs_header_drop_menu_hover_color' );

		$footerwbgColor     	  = dreamrs_opt('dreamrs_footer_bg_color') != '#030305' ? dreamrs_opt('dreamrs_footer_bg_color') : '';
		$footerwTextColor   	  = dreamrs_opt('dreamrs_footer_widget_text_color') != '#888888' ? dreamrs_opt('dreamrs_footer_widget_text_color') : '';
		$widgettitlecolor  		  = dreamrs_opt('dreamrs_footer_widget_title_color');
		$footerwanchorcolor 	  = dreamrs_opt('dreamrs_footer_widget_anchor_color') != '#7f7f7f' ? dreamrs_opt('dreamrs_footer_widget_anchor_color') : '';
		$footerwanchorhovcolor    = dreamrs_opt('dreamrs_footer_widget_anchor_hover_color');
		
		$fofbg					  = dreamrs_opt('dreamrs_fof_bg_color');
		$foftonecolor			  = dreamrs_opt('dreamrs_fof_textone_color');
		$fofttwocolor			  = dreamrs_opt('dreamrs_fof_texttwo_color');

		$gradientBgColor 		  = $themeColor != '#ff3334' ? $themeColor : '';
		$footerAncDefColor 		  = $footerwanchorcolor != '#7f7f7f' ? $footerwanchorcolor : $themeColor;
		$footerAncDefHovColor 	  = $footerwanchorhovcolor != '#ff3334' ? $footerwanchorhovcolor : $themeColor;

		$customcss ="
			.hero-banner{
				{$header_bg_img}
			}
			
			.sub_menu
			{
				background-color: {$headerTopBg};
			}
			
			.dropdown .dropdown-menu
			{
				background-color: {$dropMenuBgColor};
			}

			.banner_part .banner_text .btn_1{
				background: {$gradientBgColor};
			}

			.cta_part .cta_part_iner .cta_part_text p, .about_part .about_text h5, .our_latest_work .single_work_demo h5, .blog_part .single-home-blog .card h5:hover, .blog_part .single-home-blog .card ul li i, .review_part .slick_right:hover, .review_part .slick_left:hover, .blog_part .single-home-blog a, .blog_part .single-home-blog .time, .blog_part .single-home-blog li span, .single_single_service span.fa, section.cta_area a.cta_btn:hover, .sub_menu .sub_menu_right_content i, .sub_menu .sub_menu_social_icon a:hover, .banner-breadcrumb .breadcrumb-item a, .banner_part .banner_text h5 span, .banner_part .banner_text .btn_1:hover, .service_part .single_service_part i, .about_part .about_text h4, .our_industries .single_industries h3 a:hover, .portfolio_part .card-columns .portfolio_btn, .see_more_project .btn_1:hover, .post_2 .post_text_1 h3:hover, .post_2 .category_post_img .category_btn, .footer_part .copyright_part_text a, .subscribe_area .subscribe_iner .btn_2:hover, .sl-button span:hover, .blog_right_sidebar .widget_dreamrs_recent_widget .post_item .media-body h3:hover, .project_details .project_details_widget .single_project_details_widget span, .blog_right_sidebar .widget_dreamrs_newsletter .btn_1, .about_part .section_tittle h2 span, .about_part .about_text h2 span, .portfolio_area .section_tittle h2 span, .portfolio_area .portfolio-filter h2 span, a:hover, .portfolio_area .portfolio_box .short_info p, .service_part .section_tittle h2 span, .service_part .service_text h2 span, .project_gallery .project_gallery_tittle h2 span, .project_gallery .project_gallery_hover_text p, .project_gallery .project_gallery_hover_text h3 a:hover, .blog_part .blog_part_tittle h2 span, .blog_part .single_blog .single_appartment_content p a, .blog_part .single_blog .single_appartment_content a:hover h4, .blog_part .single_blog .single_appartment_content .list-unstyled li:hover, .blog_part .single_blog .single_appartment_content .list-unstyled li:hover a, .blog_part .right_single_blog .single_blog .media-body p a, .blog_part .right_single_blog .single_blog .media-body a:hover h5, .blog_part .right_single_blog .single_blog .media-body .list-unstyled li:hover, .blog_part .right_single_blog .single_blog .media-body .list-unstyled li:hover a, .single-post-area .navigation-area a:hover h4, .blog_details a:hover h2
			{
				color: {$themeColor}
			}			
			.portfolio_part .card-columns .portfolio_btn path, .service_part .single_service_text svg path{
				fill: {$themeColor}
			}
			.our_latest_work .single_work_demo .btn_3:hover, .team_member_section .single_team_member .single_team_text h3 a:hover, .team_member_section .single_team_member .team_member_social_icon a:hover, .blog_part .single-home-blog .card .card-body a:hover, .pre_icon a:hover, .next_icon a:hover, .review_part .section_tittle p, .banner-breadcrumb > ol > li.breadcrumb-item > a.bread-link:hover, .review_part .section_tittle p, .banner-breadcrumb .breadcrumb-item a:hover, .blog_details a:hover, .blog_right_sidebar .widget_categories ul li:hover, .blog_right_sidebar .widget_categories ul li:hover a, .blog_right_sidebar .widget_categories ul li a:hover, .blog_area a h2:hover, .main_menu .main-menu-item ul li a:not(.dropdown-item):hover, .post_2 .category_post_img .category_btn:hover, .footer_part .social_icon a:hover, .footer_part .single-footer-widget ul li a:hover{
				color: {$themeColor}!important;
			}

			.review_part .intro_video_bg .video-play-button, .review_part .owl-prev span:after, .review_part .owl-next span:after, .review_part .intro_video_bg .video-play-button:after, .review_part .intro_video_bg .video-play-button:before, .review_part .intro_video_bg .video-play-button:hover:after, .blog_item_img .blog_item_date, .single_sidebar_widget .tagcloud a:hover, .blog_right_sidebar .single_sidebar_widget.widget_dreamrs_newsletter .btn, .pre_icon :after, .next_icon :after, section.cta_area, .service_part .single_service_part .line:after, .service_part .single_service_part:hover, .about_part .about_text .btn_2:hover, .section_tittle h2:after, .our_industries .single_industries h3:after, .faq_part .faq_content .active .accordion-header h2:before, .portfolio_part .card-columns .blockquote h2:after, .see_more_project .btn_1, .contact-section .btn_2:hover, .about_part .section_tittle h2:after, .btn_1:hover, .service_part .section_tittle h2:after, .button-contactForm
			{
				background: {$themeColor}
			}

			.btn_4, .our_Professional .single_industries_text:hover, .button:not(.wpcf7-submit), .portfolio_area .portfolio-filter .active{
				border-color: {$themeColor};
				background-color: {$themeColor};
			}
			.btn_4:hover{
				color: {$themeColor}!important;
			}

			.service_part .single_service_part:hover .single_service_part_iner span
			{
				background: {$themeColor}!important;
			}

			.btn_2:hover,
			.copyright_part .footer-social a:hover
			{
				background: {$hoverColor}!important;
			}

			.blog_part .single-home-blog .card h5:hover
			{
				color: {$hoverColor};
			}

			.about_part .about_img h2:after, .copyright_part .footer-social a
			{
				border-color: {$themeColor}
			}
			
			.sub_header{
				background: {$headerTop_bg}
			}
			.sub_header .sub_header_social_icon a,
			.sub_header .sub_header_social_icon .register_icon
			{
				color: {$headerTop_col}
			}

			.main_menu:after
			{
				background: {$headerBg};
			}
			.main_menu .main-menu-item ul li a
			{
			   color: {$menuColor}!important;
			}
			.main_menu .main-menu-item ul li a:not(.dropdown-item):hover
			{
			   color: {$menuHoverColor}!important;
			}
			
			.dropdown .dropdown-menu .dropdown-item
			{
			   color: {$dropMenuColor}!important;
			}
			.dropdown .dropdown-menu .dropdown-item:hover
			{
			   color: {$dropMenuHovColor}!important;
			}

			.footer_part {
				background: {$footerwbgColor};
			}

			.footer_part hr{
				background-color: {$footerwTextColor}
			}

			.footer_part .single_footer_part p, .footer_part .widget_dreamrs_newsletter .input-group input, .footer_part .copyright_part_text p, .footer_part .footer_2 .social_icon a, .footer_part .copyright_text p
			{
				color: {$footerwTextColor}
			}
			.footer_part .widget_dreamrs_newsletter .input-group, .footer_part .copyright_part_text, .footer_part .copyright_text p, .footer_part .footer_logo {
				border-color: {$footerwTextColor}
			}
			.footer_part .single-footer-widget h4, .footer_part .single_footer_part h4
			{
				color: {$widgettitlecolor}
			}

			.footer_part .copyright_part_text a, .footer_part .social_icon a, .footer_part .single-footer-widget ul li a, .footer_part .copyright_text p a
			{
			   color: {$footerwanchorcolor};
			}
			.footer_part .copyright_part_text .footer-text > a, .footer_part .single_footer_part ul li a
			{
			   color: {$footerAncDefColor};
			}

			.footer_part .copyright_text p a, .footer_part .copyright_text p a:hover
			{
				color: {$gradientBgColor};
			}


			.footer_part .btn{
				background: {$footerwanchorcolor};
			}
			.footer_part .social_icon a:hover, .footer_part .single-footer-widget ul li a:hover
			{
			   color: {$footerAncDefHovColor};
			}
			.footer_part .copyright_part_text a:hover, .footer_part .footer_2 .social_icon a:hover, .footer_part .single_footer_part ul li a:hover
			{
			   color: {$footerAncDefHovColor}!important;
			}

			#f0f {
				background-color: {$fofbg};
			}
			.f0f-content .h1 {
				color: {$foftonecolor};
			}
			.f0f-content p {
				color: {$fofttwocolor};
			}

			.blog_right_sidebar .single_sidebar_widget .btn_1:hover, .search-page .button.button-contactForm:hover, .f0f-content .button.button-contactForm:hover{
				background: #fff;
			}

        ";
       
    wp_add_inline_style( 'dreamrs-common', $customcss );
    
}
add_action( 'wp_enqueue_scripts', 'dreamrs_common_custom_css', 50 );