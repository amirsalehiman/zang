<div class="col-md-4">
    <aside id="sidebar" class="sidebar">
        <?php if( !dynamic_sidebar( 'sidebar-1' ) ) : ?>
            <section class="widget">
                <div class="widget-wrap widget-inside">
                    <h3 class="widget-title">
                        <?php esc_html_e( 'Primary Sidebar', 'bookie-wp' ); ?>
                    </h3>
                    <p>
                        <?php printf( __( 'This is %1$s widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'bookie-wp' ), esc_html__( 'Primary Sidebar', 'bookie-wp' ), admin_url( 'widgets.php' ) ); ?>
                    </p>
                </div>
            </section>
        <?php endif; ?>
    </aside>
</div>