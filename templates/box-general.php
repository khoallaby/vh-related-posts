<div class="vhrp-box <?php echo $type; ?>">
    <h2><?php _e( $h2_titles[$type], 'vh-related-posts'); ?></h2>
    <div class="featured-image">
    <?php
        if ( has_post_thumbnail($box->ID))
            echo get_the_post_thumbnail($box->ID, 'vh-rp-image');
    ?>
    </div>
    <h3><?php echo esc_html($box->post_title); ?></h3>
    <p><?php echo $box->post_excerpt; ?> <a href="<?php echo get_permalink($box->ID); ?>" class="more">MORE</a></p>
</div>
