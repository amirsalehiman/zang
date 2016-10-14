<!DOCTYPE html>
<html <?php //language_attributes(); ?>>

<head>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <div class="site-wrap">
        
        <header class="section-site-header">
            <div class="site-header">
                <div class="container">
                    <?php if ( is_page_template( 'page_visual_composer.php' ) || is_page_template( 'page_fullwidth_notitle.php' ) ) : ?>
                        <h1 class="site-brand pull-left">
                    <?php else : ?>
                        <div class="site-brand pull-left">
                    <?php endif; ?>
                        <a class="navbar-brand" href="<?php echo esc_url( home_url('/') ); ?>">
                        <?php $tp_logo_text = toko_get_option( 'header_logo_text', get_bloginfo( 'name' ) ); ?>
                        <?php if ( $tp_logo_image = toko_get_option( 'header_logo_image' ) ) : ?>
                            <?php echo '<div class="site-logo-image">'; ?>
                            <?php echo '<img src="'.esc_url( $tp_logo_image ).'" alt="'.$tp_logo_text.'" />'; ?>
                            <?php echo '</div>'; ?>
                        <?php else : ?>
                            <?php echo '<div class="site-logo-text">' ?>
                            <?php echo ( "" == $tp_logo_text ) ? get_bloginfo( 'name' ) : $tp_logo_text; ?>
                            <?php echo '</div>'; ?>
                        <?php endif; ?>
                        <?php if( !toko_get_option( 'toko_header_description_disable' ) ) : ?>
                            <?php echo '<p class="site-description">' . get_bloginfo( 'description' ) . '</p>'; ?>
                        <?php endif ?>
                        </a>
                    <?php if ( is_page_template( 'page_visual_composer.php' ) || is_page_template( 'page_fullwidth_notitle.php' ) ) : ?>
                        </h1>
                    <?php else : ?>
                        </div>
                    <?php endif; ?>
                        
                    <div class="site-quicknav pull-right">
                        <ul class="nav navbar-right">

                            <?php if( !toko_get_option( 'toko_header_menu_disable' ) ) : ?>
                            <?php if( has_nav_menu( 'navbar-header' ) ) : ?>
                            <li class="dropdown visible-xs visible-sm">
                                <a href="#" class="dropdown-toggle"  data-toggle="collapse" data-target=".navbar-collapse-top">
                                    <i class="fa fa-navicon"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php endif; ?>

                            <?php if( class_exists( 'woocommerce' ) ) : ?>
                            <?php if( !toko_get_option( 'toko_header_minicart_disable' ) ) : ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle cart-subtotal" data-toggle="dropdown" rel="nofollow">
                                    <span class="topnav-label"><span class="amount"><?php echo WC()->cart->cart_contents_count; ?></span></span>
                                    <i class="simple-icon-bag"></i>
                                </a>
                                <?php if( !is_cart() && !is_checkout() ) : ?>
                                    <ul class="dropdown-menu topnav-minicart-dropdown sm-nowrap">
                                        <li>
                                            <?php the_widget('WC_Widget_Cart'); ?>
                                        </li>
                                    </ul>
                                <?php endif; ?>
                            </li>
                            <?php endif; ?>
                            <?php endif; ?>

                        </ul>
                    </div>

                    <?php if( !toko_get_option( 'toko_header_menu_disable' ) ) : ?>
                    <div class="site-menu navbar-collapse collapse navbar-collapse-top ">
                        <?php
                            wp_nav_menu( array(
                                'theme_location'    => 'navbar-header',
                                'container'         => '',
                                'container_class'   => '',
                                'menu_class'        => 'site-menu nav navbar-nav',
                                'fallback_cb'       => '',
                                'walker'            => new toko_navwalker(),
                                'echo'              => true
                                )
                            );
                        ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

        </header>