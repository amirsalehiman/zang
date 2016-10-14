<?php
/**
 * The template for displaying posts in the Audio post format
 *
 * @package WordPress
 * @subpackage Bookie
 * @since Bookie 1.0
 */

$tp_audio = toko_media_grabber( array( 'type' => 'audio', 'split_media' => true ) );
?>

<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php if ( is_sticky() && is_home() ) : ?>
            <span class="sticky-label"><?php esc_html_e( 'Sticky', 'bookie-wp' ); ?></span>
        <?php endif; ?>

        <?php if ( $tp_audio ) : ?>
            <figure class="entry-media entry-media-audio">
                <?php printf( '%s', $tp_audio ); ?>
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
            <?php the_content(); ?>
            <?php toko_link_pages(); ?>
        </div>
    <?php endif; ?>

    <?php if( is_singular() ) : ?>
        <div class="entry-footer">
            <?php toko_meta_tags( '<span class="entry-meta-label"><i class="drip-icon-tag"></i> ' . esc_html__( 'Tags', 'bookie-wp' ) . '</span><span class="entry-meta-content">', '</span>', ', ' ); ?>
        </div>
    <?php endif; ?>

</article>