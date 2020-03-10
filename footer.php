<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package
 */

    $url = 'https://colorlib.com/';
    $copyText = sprintf( __( 'Theme by %s colorlib %s Copyright &copy; %s  |  All rights reserved.', 'dreamrs' ), '<a target="_blank" href="' . esc_url( $url ) . '">', '</a>', date( 'Y' ) );
    $copyRight = !empty( dreamrs_opt( 'dreamrs_footer_copyright_text' ) ) ? dreamrs_opt( 'dreamrs_footer_copyright_text' ) : $copyText;
    $footer_logo = get_theme_mod( 'footer_logo' );
    $footer_logo_src = wp_get_attachment_image_src( $footer_logo, 'dreamrs_footer_logo_134x25' );
    $footer_class = ( dreamrs_opt( 'dreamrs_footer_widget_toggle' ) || $footer_logo ) != 1 ? ' no_widget' : '';
    ?>

    <!-- footer part start-->
    
    <footer class="footer_part<?php echo $footer_class?>">
        <div class="container">
            <div class="row">
                <?php
                    if( !empty( $footer_logo ) ) {
                ?>
                <div class="col-lg-12">
                    <div class="footer_logo">
                        <a href="<?php echo esc_url( home_url('/') )?>" class="footer_logo_iner"> <img src="<?php echo $footer_logo_src[0]?>" alt="footer logo"> </a>
                    </div>
                </div>
                <?php
                    }

                    if( dreamrs_opt( 'dreamrs_footer_widget_toggle' ) == 1 ) {
                        // Footer Widget 1
                        if ( is_active_sidebar( 'footer-1' ) ) {
                            echo '<div class="col-sm-6 col-lg-3">';
                                dynamic_sidebar( 'footer-1' );
                            echo '</div>';
                        }

                        // Footer Widget 2
                        if ( is_active_sidebar( 'footer-2' ) ) {
                            dynamic_sidebar( 'footer-2' );
                        }

                        // Footer Widget 3
                        if ( is_active_sidebar( 'footer-3' ) ) {
                            dynamic_sidebar( 'footer-3' );
                        }

                        // Footer Widget 4
                        if ( is_active_sidebar( 'footer-4' ) ) {
                            dynamic_sidebar( 'footer-4' );
                        }
                    }
                ?>
            </div>
            <hr/>

            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright_text text-center">
                        <p class="footer-text m-0"><?php echo wp_kses_post( $copyRight ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
    </body>
</html>