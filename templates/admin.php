<?php

?>
<div class="vh-rp-admin-container">
    <?php
    if ( $posts_query->have_posts() ) {
        echo '<ul>';
        while ( $posts_query->have_posts() ) {
            $posts_query->the_post();
            $checked = in_array( get_the_ID(), $saved_values) ? ' checked="checked" ' : '';
    ?>
        <li class="vh_rp_post<?php the_ID(); ?>">
            <label for="vh_rp_post<?php the_ID(); ?>">
                <input type="checkbox" id="vh_rp_post<?php echo the_ID(); ?>" name="vh_rp_posts[]" value="<?php echo esc_attr( get_the_ID() ); ?>" <?php echo $checked; ?> />
                <?php echo esc_html( get_the_title() ); ?>
            </label>
        </li>
    <?php
        }
        echo '</ul>';
    } else {
        // no posts found
    }
    wp_reset_postdata();
    ?>
</div>