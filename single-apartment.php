<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package dreamrs
 */

get_header();
$apartment_short_info   = dreamrs_meta( 'apartment_short_info' );
$apartment_baths        = dreamrs_meta( 'apartment_baths' );
$apartment_beds         = dreamrs_meta( 'apartment_beds' );
$apartment_size         = dreamrs_meta( 'apartment_size' );

?>

    <!--::apartment details start::-->
    <section class="gallery_details">
         <div class="container">
             <div class="row justify-content-center">
                 <div class="col-lg-10">
                    <?php
                        if( have_posts() ) {
                            while( have_posts() ) : the_post();
                    ?>
                    <div class="project_img">
                        <?php
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'dreamrs_portfolio_single_image_960x700', array( 'alt' => get_the_title() ) );
                            }
                        ?>
                        <div class="project_gallery_hover_text">
                           <?php                                 
                                if( !empty( $apartment_short_info ) ){
                                    echo '<p>'. esc_html( $apartment_short_info ) . '</p>';
                                }

                                echo '<h3>'. get_the_title() . '</h3>';

                                echo '<ul>';
                                    echo '<li><span class="flaticon-bath"></span> '. $apartment_baths .'</li>';
                                    echo '<li><span class="flaticon-bed"></span> '. $apartment_beds .'</li>';
                                    echo '<li><span class="flaticon-frame"></span> '. $apartment_size .' '.  esc_html__( 'sqft', 'dreamrs' ) . '</li>';
                                    echo '<li> '. get_simple_likes_button( get_the_ID() ) . '</li>';
                                echo '</ul>';
                            ?>
                        </div>

                        <?php the_content()?>
                    </div>
                    <?php
                        endwhile;
                        }
                    ?>
                 </div>
             </div>
         </div>
     </section>
     <!--::apartment details end::-->

    <?php

// Footer============
get_footer();