<?php
/**
 * The template for displaying posts in the Image post format
 *
 * @package WordPress
 * @subpackage Bookie
 * @since Bookie 1.0
 */

/* get global WordPress variable, no need to show image in page 2 for paginated content */
global $page;

if ( $page == 1 ) {
    $tp_output = toko_grab_image( esc_html__( 'Continue Reading &rarr;', 'bookie-wp' ) ); 
}
?>

<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php if ( is_sticky() && is_home() ) : ?>
            <span class="sticky-label"><?php esc_html_e( 'Sticky', 'bookie-wp' ); ?></span>
        <?php endif; ?>

        <?php if ( $page == 1 && $tp_output['image'] ) : ?>
            <figure class="entry-media entry-media-image">
                <?php printf( '%s', $tp_output['image'] ); ?>
            </figure>
        <?php endif; ?>

        <span class="entry-meta-item entry-meta-time">
            <?php toko_meta_date(); ?>
        </span>

        <?php if( !is_singular() ) : ?>
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
        <?php else : ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php endif; ?>

        <div class="entry-meta">
            <?php esc_html_e( 'Posted', 'bookie-wp' ); ?> 
            <?php toko_meta_author( esc_html__( 'by', 'bookie-wp' ).' ' ); ?> 
            <?php toko_meta_categories( esc_html__( 'under', 'bookie-wp' ).' ' ); ?>
        </div>
    </header>

    <?php if( is_singular() ) : ?>
        <div class="entry-content">
            <?php if ( $page == 1 ) : ?>
                <?php printf( '%s', $tp_output['content'] ); ?>
                <?php toko_link_pages(); ?>
            <?php else : ?>
                <?php the_content(); ?>
                <?php toko_link_pages(); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if( is_singular() ) : ?>
        <div class="entry-footer">
            <?php toko_meta_tags( '<span class="entry-meta-label"><i class="drip-icon-tag"></i> ' . esc_html__( 'Tags', 'bookie-wp' ) . '</span><span class="entry-meta-content">', '</span>', ', ' ); ?>
        </div>
    <?php endif; ?>

</article>
