<?php 
/**
 * @Packge     : Dreamrs
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author URI : http://colorlib.com/wp/
 *
 */
 
    // Block direct access
    if( !defined( 'ABSPATH' ) ){
        exit( 'Direct script access denied.' );
    }

/*=========================================================
	Theme option callback
=========================================================*/
function dreamrs_opt( $id = null, $default = '' ) {
	
	$opt = get_theme_mod( $id, $default );
	
	$data = '';
	
	if( $opt ) {
		$data = $opt;
	}
	
	return $data;
}


/*=========================================================
	Site icon callback
=========================================================*/

function dreamrs_site_icon(){
	if ( ! has_site_icon() ) {
		$html = '';
		$icon_path = DREAMRS_DIR_ASSETS_URI . 'img/favicon.png';
		$html .= '<link rel="icon" href="' . esc_url ( $icon_path ) . '" sizes="32x32">';
		$html .= '<link rel="icon" href="' . esc_url ( $icon_path ) . '" sizes="192x192">';
		$html .= '<link rel="apple-touch-icon-precomposed" href="' . esc_url ( $icon_path ) . '">';
		$html .= '<meta name="msapplication-TileImage" content="' . esc_url ( $icon_path ) . '">';

		return $html;
	} else {
		return;
	}
}


/*=========================================================
	Custom meta id callback
=========================================================*/
function dreamrs_meta( $id = '' ){
    
    $value = get_post_meta( get_the_ID(), '_dreamrs_'.$id, true );
    
    return $value;
}


/*=========================================================
	Blog Date Permalink
=========================================================*/
function dreamrs_blog_date_permalink(){
	
	$year  = get_the_time('Y'); 
    $month_link = get_the_time('m');
    $day   = get_the_time('d');

    $link = get_day_link( $year, $month_link, $day);
    
    return $link; 
}



/*========================================================
	Including br tag with blog title 
========================================================*/
if ( ! function_exists( 'dreamrs_br_tag_with_title' ) ) {
	function dreamrs_br_tag_with_title( $limit = 2 ) {

		$excerpt = explode( ' ', get_the_title() );
		
		// $limit null check
		if( !null == $limit ){
			$limit = $limit;
		}else{
			$limit = 2;
		}
		if ( count( $excerpt ) >= $limit ) {
			array_pop( $excerpt );
			$exc_slice = array_slice( $excerpt, 0, $limit );
			$excerpt = implode( " ", $exc_slice ).'<br>';
		} else {
			$exc_slice = array_slice( $excerpt, 0, $limit );
			$excerpt = implode( " ", $exc_slice );
		}
		return $excerpt;

	}
}



/*========================================================
	Blog Excerpt Length
========================================================*/
if ( ! function_exists( 'dreamrs_excerpt_length' ) ) {
	function dreamrs_excerpt_length( $limit = 30 ) {

		$excerpt = explode( ' ', get_the_excerpt() );
		
		// $limit null check
		if( !null == $limit ){
			$limit = $limit;
		}else{
			$limit = 30;
		}
        
        
		if ( count( $excerpt ) >= $limit ) {
			array_pop( $excerpt );
			$exc_slice = array_slice( $excerpt, 0, $limit );
			$excerpt = implode( " ", $exc_slice ).' ...';
		} else {
			$exc_slice = array_slice( $excerpt, 0, $limit );
			$excerpt = implode( " ", $exc_slice );
		}
		
		$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
		return $excerpt;

	}
}


/*==========================================================
	Comment number and Link
==========================================================*/
if ( ! function_exists( 'dreamrs_posted_comments' ) ) {
    function dreamrs_posted_comments(){
        
        $comments_num = get_comments_number();
        if( comments_open() ){
            if( $comments_num == 0 ){
                $comments = esc_html__(' No Comments','dreamrs');
            } elseif ( $comments_num > 1 ){
                $comments= $comments_num . esc_html__(' Comments','dreamrs');
            } else {
                $comments = esc_html__( ' 1 Comment','dreamrs' );
            }
            $comments = '<i class="ti-comments"></i> '. $comments;
        } else {
            $comments = esc_html__( ' Comments are closed', 'dreamrs' );
        }
        
        return $comments;
    }
}


/*===================================================
	Post embedded media
===================================================*/
function dreamrs_embedded_media( $type = array() ){
    
    $content = do_shortcode( apply_filters( 'the_content', get_the_content() ) );
    $embed   = get_media_embedded_in_content( $content, $type );
        
    if( in_array( 'audio' , $type) ){
    
        if( count( $embed ) > 0 ){
            $output = str_replace( '?visual=true', '?visual=false', $embed[0] );
        }else{
           $output = '';
        }
        
    }else{
        
        if( count( $embed ) > 0 ){

            $output = $embed[0];
        }else{
           $output = ''; 
        }
        
    }
    
    return $output;
}


/*===================================================
	WP post link pages
====================================================*/
function dreamrs_link_pages(){
    wp_link_pages( array(
    'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dreamrs' ) . '</span>',
    'after'       => '</div>',
    'link_before' => '<span>',
    'link_after'  => '</span>',
    'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'dreamrs' ) . ' </span>%',
    'separator'   => '<span class="screen-reader-text">, </span>',
    ) );
}


/*====================================================
	Specific Social icons overwritten by flaticon
====================================================*/

function dreamrs_social_icon_overwrite_by_flaticon( $social_icon ){
	switch ( $social_icon ) {
		case ($social_icon == 'fa fa-facebook' || $social_icon == 'fa fa-facebook-f'):
			$social_icon = 'flaticon-facebook';
			break;
		case ($social_icon == 'fa fa-twitter'):
			$social_icon = 'flaticon-twitter';
			break;
		case ($social_icon == 'fa fa-skype'):
			$social_icon = 'flaticon-skype';
			break;
		case ($social_icon == 'fa fa-instagram'):
			$social_icon = 'flaticon-instagram';
			break;
		
		default:
			$social_icon = $social_icon;
			break;
	}

	return $social_icon;
}

/*====================================================
	Theme logo
====================================================*/
function dreamrs_theme_logo( $class = '' ) {

    $siteUrl = home_url('/');
    // site logo
		
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$imageUrl = wp_get_attachment_image_src( $custom_logo_id , 'dreamrs_logo_196x38' );
	
	if( !empty( $imageUrl[0] ) ){
		$siteLogo = '<a class="'.esc_attr( $class ).'" href="'.esc_url( $siteUrl ).'"><img src="'.esc_url( $imageUrl[0] ).'" alt="dreamrs logo"></a>';
	}else{
		$siteLogo = '<h2><a class="'.esc_attr( $class ).'" href="'.esc_url( $siteUrl ).'">'.esc_html( get_bloginfo('name') ).'</a><span>'. esc_html( get_bloginfo('description') ) .'</span></h2>';
	}
	
	return wp_kses_post( $siteLogo );
	
}


/*================================================
    Page Title Bar
================================================*/
function dreamrs_page_titlebar() {
	if ( ! is_page_template( 'template-builder.php' ) ) {
		?>
        <section class="hero-banner d-flex align-items-center">
            <div class="container">
				<h2>
				<?php
				if ( is_category() ) {
					single_cat_title( __('Category: ', 'dreamrs') );

				} elseif ( is_tag() ) {
					single_tag_title( __('Tag Archive for - ', 'dreamrs') );

				} elseif ( is_archive() ) {
					echo get_the_archive_title();

				} elseif ( is_page() ) {
					echo get_the_title();

				} elseif ( is_search() ) {
					echo esc_html__( 'Search for: ', 'dreamrs' ) . get_search_query();

				} elseif ( ! ( is_404() ) && ( is_single() ) || ( is_page() ) ) {
					echo  get_the_title();

				} elseif ( is_home() ) {
					echo esc_html__( 'Blog', 'dreamrs' );

				} elseif ( is_404() ) {
					echo esc_html__( '404 error', 'dreamrs' );

				}
				?>
				</h2>
            </div>
        </section>
		<?php
	}
}



/*================================================
	Blog pull right class callback
=================================================*/
function dreamrs_pull_right( $id = '', $condation ){
    
    if( $id == $condation ){
        return ' '.'order-last';
    }else{
        return;
    }
    
}



/*======================================================
	Inline Background
======================================================*/
function dreamrs_inline_bg_img( $bgUrl ){
    $bg = '';

    if( $bgUrl ){
        $bg = 'style="background-image:url('.esc_url( $bgUrl ).')"'; 
    }

    return $bg;
}


/*======================================================
	Blog Category
======================================================*/
function dreamrs_featured_post_cat(){

	$categories = get_the_category(); 
	
	if( is_array( $categories ) && count( $categories ) > 0 ){
		$getCat = [];
		foreach ( $categories as $value ) {

			if( $value->slug != 'featured' && ! is_front_page() ){
				$getCat[] = '<a href="'.esc_url( get_category_link( $value->term_id ) ).'"> <i class="ti-bookmark"></i> '.esc_html( $value->name ).'</a>';
			}else{
				$getCat[] = '<i class="ti-bookmark"></i>'.esc_html( $value->name );
			}
		}

		return implode( ', ', $getCat );
	}
         
}


/*=======================================================
	Customize Sidebar Option Value Return
========================================================*/
function dreamrs_sidebar_opt(){

    $sidebarOpt = dreamrs_opt( 'dreamrs_blog_layout' );
    $sidebar = '1';
    // Blog Sidebar layout  opt
    if( is_array( $sidebarOpt ) ){
        $sidebarOpt =  $sidebarOpt;
    }else{
        $sidebarOpt =  json_decode( $sidebarOpt, true );
    }
    
    
    if( !empty( $sidebarOpt['columnsCount'] ) ){
        $sidebar = $sidebarOpt['columnsCount'];
    }


    return $sidebar;
}


/**================================================
	Themify Icon
 =================================================*/
function dreamrs_themify_icon(){
    return[
        'flaticon-chip'     => __('Flaticon Chip', 'dreamrs'),
        'flaticon-cap'      => __('Flaticon Cap', 'dreamrs'),
        'flaticon-wallet'   => __('Flaticon Wallet', 'dreamrs'),
        'flaticon-audio'    => __('Flaticon Audio', 'dreamrs'),
    ];
}


/*===========================================================
	Set contact form 7 default form template
============================================================*/
function dreamrs_contact7_form_content( $template, $prop ) {
    
    if ( 'form' == $prop ) {

        $template =
            '<div class="row"><div class="col-12"><div class="form-group">[textarea* your-message id:message class:form-control class:w-100 rows:9 cols:30 placeholder "Message"]</div></div><div class="col-sm-6"><div class="form-group">[text* your-name id:name class:form-control placeholder "Enter your  name"]</div></div><div class="col-sm-6"><div class="form-group">[email* your-email id:email class:form-control placeholder "Enter your email"]</div></div><div class="col-12"><div class="form-group">[text your-subject id:subject class:form-control placeholder "Subject"]</div></div></div><div class="form-group mt-3">[submit class:button class:button-contactForm class:btn_2 "Send Message"]</div>';

        return $template;

    } else {
    return $template;
    } 
}
add_filter( 'wpcf7_default_template', 'dreamrs_contact7_form_content', 10, 2 );



/*============================================================
	Pagination
=============================================================*/
function dreamrs_blog_pagination(){
	echo '<nav class="blog-pagination justify-content-center d-flex">';
        the_posts_pagination(
            array(
                'mid_size'  => 2,
                'prev_text' => __( '<span class="ti-angle-left"></span>', 'dreamrs' ),
                'next_text' => __( '<span class="ti-angle-right"></span>', 'dreamrs' ),
                'screen_reader_text' => ' '
            )
        );
	echo '</nav>';
}


/*=============================================================
	Blog Single Post Navigation
=============================================================*/
if( ! function_exists('dreamrs_blog_single_post_navigation') ) {
	function dreamrs_blog_single_post_navigation() {

		// Start nav Area
		if( get_next_post_link() || get_previous_post_link()   ):
			?>
			<div class="navigation-area">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-12 nav-left flex-row d-flex justify-content-start align-items-center">
						<?php
						if( get_next_post_link() ){
							$nextPost = get_next_post();

							if( has_post_thumbnail() ){
								?>
								<div class="thumb">
									<a href="<?php the_permalink(  absint( $nextPost->ID )  ) ?>">
										<?php echo get_the_post_thumbnail( absint( $nextPost->ID ), 'dreamrs_np_thumb', array( 'class' => 'img-fluid' ) ) ?>
									</a>
								</div>
								<?php
							} ?>
							<div class="arrow">
								<a href="<?php the_permalink(  absint( $nextPost->ID )  ) ?>">
									<span class="ti-arrow-left text-white"></span>
								</a>
							</div>
							<div class="detials">
								<p><?php echo esc_html__( 'Prev Post', 'dreamrs' ); ?></p>
								<a href="<?php the_permalink(  absint( $nextPost->ID )  ) ?>">
									<h4><?php echo wp_trim_words( get_the_title( $nextPost->ID ), 4, ' ...' ); ?></h4>
								</a>
							</div>
							<?php
						} ?>
					</div>
					<div class="col-lg-6 col-md-6 col-12 nav-right flex-row d-flex justify-content-end align-items-center">
						<?php
						if( get_previous_post_link() ){
							$prevPost = get_previous_post();
							?>
							<div class="detials">
								<p><?php echo esc_html__( 'Next Post', 'dreamrs' ); ?></p>
								<a href="<?php the_permalink(  absint( $prevPost->ID )  ) ?>">
									<h4><?php echo wp_trim_words( get_the_title( $prevPost->ID ), 4, ' ...' ); ?></h4>
								</a>
							</div>
							<div class="arrow">
								<a href="<?php the_permalink(  absint( $prevPost->ID )  ) ?>">
									<span class="ti-arrow-right text-white"></span>
								</a>
							</div>
							<div class="thumb">
								<a href="<?php the_permalink(  absint( $prevPost->ID )  ) ?>">
									<?php echo get_the_post_thumbnail( absint( $prevPost->ID ), 'dreamrs_np_thumb', array( 'class' => 'img-fluid' ) ) ?>
								</a>
							</div>
							<?php
						} ?>
					</div>
				</div>
			</div>
		<?php
		endif;

	}
}


/*=======================================================
	Author Bio
=======================================================*/
function dreamrs_author_bio(){
	$avatar = get_avatar( absint( get_the_author_meta( 'ID' ) ), 90 );
	?>
	<div class="blog-author">
		<div class="media align-items-center">
			<?php
			if( $avatar  ) {
				echo wp_kses_post( $avatar );
			}
			?>
			<div class="media-body">
				<a href="<?php echo esc_url( get_author_posts_url( absint( get_the_author_meta( 'ID' ) ) ) ); ?>"><h4><?php echo esc_html( get_the_author() ); ?></h4></a>
				<p><?php echo esc_html( get_the_author_meta('description') ); ?></p>
			</div>
		</div>
	</div>
	<?php
}


/*===================================================
 Dreamrs Comment Template Callback
 ====================================================*/
function dreamrs_comment_callback( $comment, $args, $depth ) {

	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>
	<<?php echo esc_attr( $tag ); ?> <?php comment_class( ( empty( $args['has_children'] ) ? '' : 'parent').' comment-list' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-list">
	<?php endif; ?>
		<div class="single-comment">
			<div class="user d-flex">
				<div class="thumb">
					<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
				</div>
				<div class="desc">
					<div class="comment">
						<?php comment_text(); ?>
					</div>

					<div class="d-flex justify-content-between">
						<div class="d-flex align-items-center">
							<h5 class="comment_author"><?php printf( __( '<span class="comment-author-name">%s</span> ', 'dreamrs' ), get_comment_author_link() ); ?></h5>
							<p class="date"><?php printf( __('%1$s at %2$s', 'dreamrs'), get_comment_date(),  get_comment_time() ); ?><?php edit_comment_link( esc_html__( '(Edit)', 'dreamrs' ), '  ', '' ); ?> </p>
							<?php if ( $comment->comment_approved == '0' ) : ?>
								<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'dreamrs' ); ?></em>
								<br>
							<?php endif; ?>
						</div>

						<div class="reply-btn">
							<?php comment_reply_link(array_merge( $args, array( 'add_below' => $add_below, 'depth' => 1, 'max_depth' => 5, 'reply_text' => 'Reply' ) ) ); ?>
						</div>
					</div>

				</div>
			</div>
		</div>
	<?php if ( 'div' != $args['style'] ) : ?>
		</div>
	<?php endif; ?>
	<?php
}
// add class comment reply link
add_filter('comment_reply_link', 'dreamrs_replace_reply_link_class');
function dreamrs_replace_reply_link_class( $class ) {
	$class = str_replace("class='comment-reply-link", "class='btn-reply comment-reply-link text-uppercase", $class);
	return $class;
}



/*=========================================================
    Latest Blog Post For Elementor Section
===========================================================*/
function dreamrs_latest_blog( $post_order = 'DESC' ){
	
	function dreamrs_get_first_catname_link() {
		$dreamrs_cat = '';
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$dreamrs_cat .= '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
		}
		return $dreamrs_cat;
	}

	$count = 0;
	
	$lBlog = new WP_Query( array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'order'			 => $post_order
    ) );

    if( $lBlog->have_posts() ){
        while( $lBlog->have_posts() ){
			$lBlog->the_post();
			$count++;
			if ( $count == 1 ) {
	?>
			
		<div class="col-lg-7">
			<div class="single_blog">
				<div class="appartment_img">
					<?php
						if( has_post_thumbnail() ){
							the_post_thumbnail( 'dreamrs_latest_blog_606x269', ['alt' => get_the_title() ] );
						}
					?>
				</div>
				<div class="single_appartment_content">
					<p><?php echo dreamrs_get_first_catname_link()?> / <?php the_time('F j, Y') ?></p>
					<a href="<?php the_permalink(); ?>">
						<h4><?php the_title(); ?></h4>
					</a>
					<ul class="list-unstyled">
					<li><?php echo dreamrs_posted_comments();?></li>
					<li><?php echo get_simple_likes_button( get_the_ID() )?></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="right_single_blog">
				<?php 
			} else { 
				?>
				<div class="single_blog">
					<div class="media">
						<div class="media-body align-self-center">
							<p><?php echo dreamrs_get_first_catname_link()?> / <?php the_time('F j, Y') ?></p>
							<a href="<?php the_permalink(); ?>">
								<h5 class="mt-0"> <?php the_title(); ?></h5>
							</a>
							<ul class="list-unstyled">
								<li><?php echo dreamrs_posted_comments();?></li>
								<li><?php echo get_simple_likes_button( get_the_ID() )?></li>
							</ul>
						</div>
					</div>
				</div>
				<?php 
			} 
		}
			?>
			</div>
		</div>
		<?php

    }

}



/*=========================================================
    Share Button Code
===========================================================*/
function dreamrs_social_sharing_buttons( $ulClass = '', $tagLine = '' ) {

	// Get page URL
	$URL = get_permalink();
	$Sitetitle = get_bloginfo('name');

	// Get page title
	$Title = str_replace( ' ', '%20', get_the_title());

	// Construct sharing URL without using any script
	$twitterURL = 'https://twitter.com/intent/tweet?text='.esc_html( $Title ).'&amp;url='.esc_url( $URL ).'&amp;via='.esc_html( $Sitetitle );
	$facebookURL= 'https://www.facebook.com/sharer/sharer.php?u='.esc_url( $URL );
	$linkedin   = 'https://www.linkedin.com/shareArticle?mini=true&url='.esc_url( $URL ).'&title='.esc_html( $Title );
	$pinterest  = 'http://pinterest.com/pin/create/button/?url='.esc_url( $URL ).'&description='.esc_html( $Title );;

	// Add sharing button at the end of page/page content
	$content = '';
	$content  .= '<ul class="'.esc_attr( $ulClass ).'">';
	$content .= $tagLine;
	$content .= '<li><a href="' . esc_url( $facebookURL ) . '" target="_blank"><i class="ti-facebook"></i></a></li>';
	$content .= '<li><a href="' . esc_url( $twitterURL ) . '" target="_blank"><i class="ti-twitter-alt"></i></a></li>';
	$content .= '<li><a href="' . esc_url( $pinterest ) . '" target="_blank"><i class="ti-pinterest"></i></a></li>';
	$content .= '<li><a href="' . esc_url( $linkedin ) . '" target="_blank"><i class="ti-linkedin"></i></a></li>';
	$content .= '</ul>';

	return $content;

}




/*================================================
    Dreamrs Custom Posts
================================================*/
function dreamrs_custom_posts() {
	
	// Portfolio Custom Post
	
	$labels = array(
		'name'               => _x( 'Portfolios', 'post type general name', 'dreamrs' ),
		'singular_name'      => _x( 'Portfolio', 'post type singular name', 'dreamrs' ),
		'menu_name'          => _x( 'Portfolios', 'admin menu', 'dreamrs' ),
		'name_admin_bar'     => _x( 'Portfolio', 'add new on admin bar', 'dreamrs' ),
		'add_new'            => _x( 'Add New', 'portfolio', 'dreamrs' ),
		'add_new_item'       => __( 'Add New Portfolio', 'dreamrs' ),
		'new_item'           => __( 'New Portfolio', 'dreamrs' ),
		'edit_item'          => __( 'Edit Portfolio', 'dreamrs' ),
		'view_item'          => __( 'View Portfolio', 'dreamrs' ),
		'all_items'          => __( 'All Portfolios', 'dreamrs' ),
		'search_items'       => __( 'Search Portfolios', 'dreamrs' ),
		'parent_item_colon'  => __( 'Parent Portfolios:', 'dreamrs' ),
		'not_found'          => __( 'No portfolios found.', 'dreamrs' ),
		'not_found_in_trash' => __( 'No portfolios found in Trash.', 'dreamrs' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'dreamrs' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'portfolio' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	);

	register_post_type( 'portfolio', $args );
	

	// Register taxonomy

	$labels = array(
		'name'              => _x( 'Portfolio Category', 'taxonomy general name', 'dreamrs' ),
		'singular_name'     => _x( 'Portfolio Categories', 'taxonomy singular name', 'dreamrs' ),
		'search_items'      => __( 'Search Portfolio Categories', 'dreamrs' ),
		'all_items'         => __( 'All Portfolio Categories', 'dreamrs' ),
		'parent_item'       => __( 'Parent Portfolio Category', 'dreamrs' ),
		'parent_item_colon' => __( 'Parent Portfolio Category:', 'dreamrs' ),
		'edit_item'         => __( 'Edit Portfolio Category', 'dreamrs' ),
		'update_item'       => __( 'Update Portfolio Category', 'dreamrs' ),
		'add_new_item'      => __( 'Add New Portfolio Category', 'dreamrs' ),
		'new_item_name'     => __( 'New Portfolio Category Name', 'dreamrs' ),
		'menu_name'         => __( 'Portfolio Category', 'dreamrs' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'portfolio-cat' ),
	);

	register_taxonomy( 'portfolio-cat', array( 'portfolio' ), $args );


	// Apartment Custom Post
	
	$apartment_labels = array(
		'name'               => _x( 'Apartments', 'post type general name', 'dreamrs' ),
		'singular_name'      => _x( 'Apartment', 'post type singular name', 'dreamrs' ),
		'menu_name'          => _x( 'Apartments', 'admin menu', 'dreamrs' ),
		'name_admin_bar'     => _x( 'Apartment', 'add new on admin bar', 'dreamrs' ),
		'add_new'            => _x( 'Add New', 'apartment', 'dreamrs' ),
		'add_new_item'       => __( 'Add New Apartment', 'dreamrs' ),
		'new_item'           => __( 'New Apartment', 'dreamrs' ),
		'edit_item'          => __( 'Edit Apartment', 'dreamrs' ),
		'view_item'          => __( 'View Apartment', 'dreamrs' ),
		'all_items'          => __( 'All Apartments', 'dreamrs' ),
		'search_items'       => __( 'Search Apartments', 'dreamrs' ),
		'parent_item_colon'  => __( 'Parent Apartments:', 'dreamrs' ),
		'not_found'          => __( 'No apartments found.', 'dreamrs' ),
		'not_found_in_trash' => __( 'No apartments found in Trash.', 'dreamrs' )
	);

	$apartment_args = array(
		'labels'             => $apartment_labels,
		'description'        => __( 'Description.', 'dreamrs' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'apartment' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	);

	register_post_type( 'apartment', $apartment_args );

}
add_action( 'init', 'dreamrs_custom_posts' );




/*=========================================================
    Portfolio Section
========================================================*/
function dreamrs_portfolio_section( $proj_title, $proj_filter, $proj_order ){ ?>

	<div class="portfolio-filter">
		<h2><?php echo $proj_title?></h2>
		<?php if ( $proj_filter == 'yes' ) { ?>
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<?php
				$categories = get_terms(
					array(
						'taxonomy' => 'portfolio-cat',
						'hide_empty' => false
					)
				);
				$i = 1;
				foreach ( $categories as $category ) {
					$active_class  = ( $i == 1 ) ? 'active' : '';
					$area_selected = ( $i == 1 ) ? 'true' : 'false';
					echo '<li><a class="'. $active_class .'" id="'. esc_attr( $category->slug ) .'-tab" data-toggle="tab" href="#'. esc_attr( $category->slug ) .'" role="tab" aria-controls="'. esc_attr( $category->slug ) .'" aria-selected="'. $area_selected .'">'. esc_html( $category->name ) .'</a></li>';
					$i++;
				}
			?>
		</ul>
		<?php } ?>
	</div>

	<div class="portfolio_item tab-content" id="myTabContent">
		<?php
		$categories = get_terms(
			array(
				'taxonomy' => 'portfolio-cat',
				'hide_empty' => false
			)
		);
		$i = 1;
		foreach ( $categories as $category ) {
			$active_class = ( $i == 1 ) ? ' show active' : '';
			?>
			<div class="row align-items-center justify-content-between tab-pane fade<?php echo $active_class?>" id="<?php echo esc_attr( $category->slug ) ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr( $category->slug ) ?>-tab">
				<?php
				$args = array(
					'post_type' 		=> 'portfolio',
					'order'				=> $proj_order,
					'posts_per_page'	=> 3,
					'tax_query' 		=> array(
						array(
							'taxonomy' => 'portfolio-cat',
							'field'    => 'slug',
							'terms'    => $category->slug,
						),
					),
				);
				
				$query = new WP_Query( $args );
				$j = 0;
				if( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post(); 
						$img_size = dreamrs_meta( 'portfolio_img_size' );
						$image_size = 'dreamrs_portfolio_image_555x589';
						if ( $img_size == 2 ) {
							$image_size = 'dreamrs_portfolio_image_458x491';
						}
						$img_id = get_post_thumbnail_id( get_the_id() );
						$img_src = wp_get_attachment_image_src( $img_id, 'full');
						$short_info = dreamrs_meta( 'project_short_info' );
						$j++;
						if( $j == 1 ){
						?>
						<div class="col-lg-6 col-sm-12 col-md-6">
							<div class="portfolio_box">
								<a href="<?php echo $img_src[0]?>" class="img-gal">
									<div class="single_portfolio">
										<?php 
											the_post_thumbnail( $image_size, ['class' => 'img-fluid w-100', 'alt' => get_the_title() ] );
										?>
									</div>
								</a>
								<div class="short_info">
									<p><?php echo $short_info?></p>
									<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								</div>
							</div>
						</div>

						<div class="col-lg-5 col-md-6">
							<div class="row">
							<?php 
						}elseif( $j == 2 || $j == 3 ) { ?>
								<div class="col-lg-12 col-sm-6 col-md-12 single_portfolio_project">
									<div class="portfolio_box">
										<a href="<?php echo $img_src[0]?>" class="img-gal">
											<div class="single_portfolio">
												<?php 
													the_post_thumbnail( $image_size, ['class' => 'img-fluid w-100', 'alt' => get_the_title() ] );
												?>
											</div>
										</a>
										<div class="short_info">
											<p><?php echo $short_info?></p>
											<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
										</div>
									</div>
								</div>
							<?php 
						}
					}
							?>
							</div>
						</div>
					<?php
				}
				?>
				</div>
				<?php
			$i++;
		} ?>
	</div>
	<?php
}



/*=========================================================
    Image sizes selection serially
========================================================*/

function dreamrs_img_sizes_selection( $item_number ) {
	
	$img_size = '';
	switch ( $item_number ) {
		case '1':
			$img_size = 'dreamrs_apartment_image_960x350';
			break;
		
		case '2':
			$img_size = 'dreamrs_portfolio_single_image_960x700';
			break;
		
		default:
			$img_size = 'dreamrs_apartment_image_479x350';
			break;
	}

	return $img_size;
}

/*=========================================================
    Apartment Section
========================================================*/
function dreamrs_apartment_section( $apart_order ) {
	
	$args = array(
		'post_type' 		=> 'apartment',
		'order'				=> $apart_order,
		'posts_per_page'	=> 4,
	);

	$query = new WP_Query( $args );
	$i = 0;
	if( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$i++;
			$query->the_post(); 
			$img_size 			 = dreamrs_img_sizes_selection( $i );
			$short_info 		 = dreamrs_meta( 'apartment_short_info' );
			$apartment_baths 	 = dreamrs_meta( 'apartment_baths' );
			$apartment_beds 	 = dreamrs_meta( 'apartment_beds' );
			$apartment_size 	 = dreamrs_meta( 'apartment_size' );
			$img_alignment_class = ($i == 1) ? ' big_weight' : (($i == 2) ? ' big_height' : '');
			?>
			<div class="grid-item<?php echo $img_alignment_class?>">
				<div class="project_img">
					<?php 
						the_post_thumbnail( $img_size, [ 'alt' => get_the_title() ] );
					?>
					<div class="project_gallery_hover_text">
						<p><?php echo esc_html( $short_info )?></p>
						<h3><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
						<ul>
							<li><span class="flaticon-bath"></span> <?php echo esc_html( $apartment_baths )?></li>
							<li><span class="flaticon-bed"></span> <?php echo esc_html( $apartment_beds )?></li>
							<li><span class="flaticon-frame"></span> <?php echo esc_html( $apartment_size ) . ' '. __( 'sqrt', 'dreamrs' )?></li>
							<li><?php echo get_simple_likes_button( get_the_ID() )?></li>
						</ul>
					</div>
				</div>
			</div>
			<?php 
		}
	}
	?>
	<?php
}
