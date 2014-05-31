<div class="vhrp-box <?php echo $type; ?>">
    <h2><?php _e( $h2_titles[$type], 'vh-related-posts'); ?></h2>
    <?php
     if ( has_post_thumbnail())
         the_post_thumbnail('vh-rp-image');
    ?>
    <h3><?php the_title(); ?></h3>
    <p><?php echo $post->post_excerpt; ?></p>
</div>