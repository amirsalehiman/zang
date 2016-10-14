        <footer class="section-site-footer">

			<?php if( !toko_get_option( 'toko_footer_banner_disable' ) && !is_page_template() ) : ?>
				<?php if( $tp_footer_banner_image = toko_get_option( 'toko_footer_banner_image' ) ) : ?>
					<div class="footer-banner"  style="background-image:url(<?php echo esc_url( $tp_footer_banner_image ); ?>)">
						<div class="container"> 
							<p><?php echo toko_get_option( 'toko_footer_banner_title' ) ?></p>
							<?php if( $tp_footer_banner_link = toko_get_option( 'toko_footer_banner_link' ) ) : ?>
								<a href="<?php echo esc_url( toko_get_option( 'toko_footer_banner_url' ) ); ?>" title=""><?php echo esc_attr( $tp_footer_banner_link ); ?> <i class="fa fa-long-arrow-right"></i></a>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
        	<?php endif; ?>

			<?php if( !toko_get_option( 'toko_footer_social_disable' ) || !toko_get_option( 'toko_footer_nav_disable' ) ) : ?>
            <div class="site-footer">
                <div class="container">

                	<?php if( !toko_get_option( 'toko_footer_social_disable' ) ) : ?>
                	<?php
                    	$tp_social_output = array();
                		$tp_socials = array( 'facebook', 'twitter', 'google-plus', 'instagram', 'rss', 'e-mail','youtube', 'flickr', 'linkedin', 'pinterest', 'dribbble', 'github', 'lastfm', 'vimeo', 'tumblr', 'soundcloud', 'behance', 'deviantart' );
                        for( $tp_i=0; $tp_i<18; $tp_i++ ) {
                            $tp_social_link = toko_get_option( 'toko_footer_social_' . $tp_i );
                            if( "e-mail" == $tp_socials[$tp_i] ) {
                                $tp_social_icon = 'envelope-o';
                            } 
                            else if( "vimeo" == $tp_socials[$tp_i] ) {
                                $tp_social_icon = 'vimeo-square';
                            } 
                            else {
                                $tp_social_icon = $tp_socials[$tp_i];
                            }
                            $tp_social_name = ucwords( str_replace( '-', ' ', $tp_socials[$tp_i] ) );
                            if( $tp_social_link ) {
                                $tp_social_output[] = '<li><a href="' . esc_url( $tp_social_link ) . '" rel="nofollow" title="' . esc_attr( $tp_social_name ) . '"><i class="fa fa-'.esc_attr( $tp_social_icon ).'"></i></a></li>';
                            }
                        }
                	?>
                	<?php if ( ! empty( $tp_social_output ) ) : ?>
                    <div class="footer-social">
                        <ul>
                        	<?php echo implode( '', $tp_social_output ); ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                	<?php endif; ?>

                	<?php
					wp_nav_menu( array(
						'theme_location'    => 'navbar-footer',
						'container'         => 'div',
						'container_class'   => 'footer-menu-wrap',
						'menu_class'        => 'footer-menu nav navbar-nav navbar-center',
						'fallback_cb'       => '',
						'walker'            => '',
						'echo'				=> true,
						'depth'				=> 1
						)
					);
			        ?>

	                <div class="footer-credit">
	                    <?php 
	                    $tp_footer_credit = toko_get_option( 'footer_credit' );
	                    if ( !$tp_footer_credit ) {
	                        $tp_footer_credit = esc_html__( 'Copyright &copy; Bookie WordPress Theme', 'bookie-wp' );
	                    }
	                    echo wpautop( $tp_footer_credit );
	                    ?>
	                </div>

                </div>
            </div>
        	<?php endif; ?>

        </footer>

    </div>

<?php wp_footer(); ?>

</body>

</html>