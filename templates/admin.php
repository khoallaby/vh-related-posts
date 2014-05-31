<div class="vh-rp-type-container">
    <label>
        Related Post:
        <select name="<?php echo $this->metakey_type; ?>">
            <option value="">---</option>
            <?php
            foreach( $this->return_types(true) as $type => $type_title ) {
                $selected = get_post_meta(get_the_ID(), $this->metakey_type, true) == $type ? ' selected="selected" ' : '';
            ?>
                <option value="<?php echo esc_attr($type); ?>" <?php echo $selected; ?> ><?php echo esc_html($type_title); ?></option>
            <?php } ?>
        </select>
    </label>
</div>
<div class="vh-rp-select-container">

    <?php
    if ( $posts_query->have_posts() ) {
        echo '<ul>';
        while ( $posts_query->have_posts() ) {
            $posts_query->the_post();
            $checked = in_array( get_the_ID(), $saved_values) ? ' checked="checked" ' : '';
    ?>
        <li class="vh_rp_post<?php the_ID(); ?>">
            <label for="vh_rp_post<?php the_ID(); ?>">
                <input type="checkbox" id="vh_rp_post<?php echo the_ID(); ?>" name="<?php echo $this->metakey; ?>[]" value="<?php echo esc_attr( get_the_ID() ); ?>" <?php echo $checked; ?> />
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
