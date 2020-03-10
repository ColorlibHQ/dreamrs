<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package dreamrs
 */

get_header();
$project_short_info   = dreamrs_meta( 'project_short_info' );

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
                                if( !empty( $project_short_info ) ){
                                    echo '<p>'. esc_html( $project_short_info ) . '</p>';
                                }

                                echo '<h3>'. get_the_title() . '</h3>';

                                $terms = get_the_terms( get_the_id() , 'portfolio-cat' );

                                echo '<ul>';
                                    echo '<li><span class="flaticon-frame"></span> '. $terms[0]->name .'</li>';
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