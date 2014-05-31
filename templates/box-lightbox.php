<div class="vhrp-box <?php echo $type; ?>">
    <h2><?php _e( $h2_titles[$type], 'vh-related-posts'); ?></h2>
    <?php
    if ( has_post_thumbnail()) {
        $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
        echo '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" class="lightbox">';
        echo get_the_post_thumbnail($post->ID, 'vh-rp-image');
        echo '</a>';
    }
    ?>
    <h3><?php the_title(); ?></h3>
    <p><?php echo $post->post_excerpt; ?></p>
</div>