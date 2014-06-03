<div class="vhrp-box <?php echo $type; ?>">
    <h2><?php _e( $h2_titles[$type], 'vh-related-posts'); ?></h2>
    <div class="featured-image">
    <?php
        if ( has_post_thumbnail($box->ID)) {
            $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($box->ID), 'large');
            #echo '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" class="lightbox">';
            echo '<a href="' . get_permalink($box->ID) . '">';
            echo get_the_post_thumbnail($box->ID, 'vh-rp-image');
            echo '</a>';
        }
    ?>
    </div>
    <h3><?php echo esc_html($box->post_title); ?></h3>
    <p><?php echo $box->post_excerpt; ?> <a href="<?php echo get_permalink($box->ID); ?>" class="more">MORE</a></p>
</div>
