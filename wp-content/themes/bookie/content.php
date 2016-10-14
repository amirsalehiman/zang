<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Bookie
 * @since Bookie 1.0
 */
?>

<article id="post-<?php echo get_the_ID(); ?>" <?php post_class('entry'); ?>>

    <header class="entry-header">
        <?php if ( is_sticky() && is_home() ) : ?>
            <span class="sticky-label"><?php esc_html_e( 'Sticky', 'bookie-wp' ); ?></span>
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

        <?php if( has_post_thumbnail() ) : ?>
            <figure class="entry-media entry-media-standard">
                <?php the_post_thumbnail(); ?>    
            </figure>
        <?php endif; ?>
    </header>

    <div class="entry-content">
        <?php if( is_singular() ) : ?>
            <?php the_content(); ?>
            <?php toko_link_pages(); ?>
        <?php else : ?>
            <?php if( has_excerpt() ) : ?>
                <?php the_excerpt(); ?>
            <?php else : ?>
                <?php echo '<p>' . wp_trim_words( get_the_content(), 100, '...' ) . '</p>'; ?>
            <?php endif; ?>
            <p>
                <a href="<?php the_permalink(); ?>" rel="bookmark" class="read-more-link">
                    <?php esc_html_e( 'Continue Reading &rarr;', 'bookie-wp' ); ?> 
                </a>
            </p>
        <?php endif; ?>
    </div>

    <?php if( is_singular() ) : ?>
        <div class="entry-footer">
            <?php toko_meta_tags( '<span class="entry-meta-label"><i class="drip-icon-tag"></i> ' . esc_html__( 'Tags', 'bookie-wp' ) . '</span><span class="entry-meta-content">', '</span>', ', ' ); ?>
        </div>
    <?php endif; ?>

</article>
