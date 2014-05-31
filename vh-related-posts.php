<?php

class vh_related_posts {
    /**
     * Metakey for storing related posts IDs
     *
     * @var string
     */
    public $metakey = '_vh_related_posts';
    /**
     * Metakey for determining type of box to show
     *
     * @var string
     */
    public $metakey_type = 'type';

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'add_css_js' ), 10 );
        add_filter( 'the_content', array($this, 'render_boxes') );
        if( is_singular() && is_main_query() ) {
            add_filter( 'the_content', array($this, 'render_boxes') );
        } elseif ( is_admin() ) {
            add_action( 'load-post.php', array($this, 'load_admin') );
            add_action( 'load-post-new.php', array($this, 'load_admin') );
        }

        add_action("after_switch_theme", array($this, 'theme_activate'), 10 ,  2);
    }

    /**
     * Load admin hooks
     */
    public function load_admin() {
        add_action( 'admin_enqueue_scripts', array( $this, 'add_css_js' ), 10 );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );
    }


    /**
     * Adds css/js to admin or post/page
     */
    public function add_css_js( $page ) {
        if( is_singular() ) {
            wp_enqueue_style( 'vh-rp', plugins_url('style.css', __FILE__), 10 );
        } elseif( is_admin() ) {
            if( in_array($page, array('post.php', 'post-new.php', 'page.php', 'page-new.php' )) ) {
                wp_enqueue_style( 'vh-rp', plugins_url('style.css', __FILE__), 10 );
                wp_enqueue_script( 'vh-rp', plugins_url('script.js', __FILE__), 10 );
            } else
                return;
        }
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        $post_types = array('post', 'page');     //limit meta box to certain post types
        if ( in_array( $post_type, $post_types )) {
            add_meta_box(
                'some_meta_box_name'
                ,__( 'Related Posts', 'vh-related-posts' )
                ,array( $this, 'render_meta_box_content' )
                ,$post_type
                ,'advanced'
                ,'high'
            );
        }
    }


    /**
     * Renders a box
     *
     * $param string $type The type of box (general/video)
     */
    public function render_box( $post ) {
        $h2_titles = $this->return_metakeys();
        $type = get_post_meta( $post->ID, $this->metakey_type, true );
        if( !$type )
            $type = 'general';
        if( in_array($type, array_keys($h2_titles)) ) {
            ob_start();
            include( dirname( __FILE__ ) . "/templates/box-{$type}.php" );
            $return = ob_get_contents();
            ob_end_clean();
            return $return;
        }
    }

    /**
     * Renders boxes after the_content()
     *
     * $param string $post The post
     */
    public function render_boxes( $content ) {
        global $post;
        $posts = get_post_meta( $post->ID, $this->metakey, true );
        $return = '<div class="vh-related-posts-container">';
        foreach( $posts as $post_id ) {
            $box = get_post( $post_id );
            if( $box )
                $return .= $this->render_box( $box );
        }
        $return .= '<div class="clear"></div></div>';
        return $content . $return;
    }


    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'vhrp_custom_box', 'vhrp_custom_box_nonce' );

        $saved_values = get_post_meta( $post->ID, $this->metakey, true );


        $args = array(
            'post_type' => array('post','page'),
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );
        $posts_query = new WP_Query( apply_filters( 'vhrp_admin_query', $args ) );

        require_once( dirname( __FILE__ ) . '/templates/admin.php' );
    }


    /**
     * Return metakey => Title array
     */
    public function return_metakeys() {
        return apply_filters( 'vhrp_box_h2_titles', array(
            'general' => 'General', #remove later
            'video' => 'Video',
            'app-note' => 'App Note',
            'case-study' => 'Case Study',
            'services' => 'Testing Services',
            'software' => 'Software Product',
            'hardware' => 'Hardware Product'
        ));
    }


    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
        // Check if our nonce is set.
        if ( ! isset( $_POST['vhrp_custom_box_nonce'] ) )
            return $post_id;

        $nonce = $_POST['vhrp_custom_box_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'vhrp_custom_box' ) )
            return $post_id;

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) )
                return $post_id;
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }

        // Update the meta field.
        update_post_meta( $post_id, $this->metakey, $_POST['vh_rp_posts'] );
    }

    public function theme_activate() {
        add_image_size( 'vh-rp-image', 220, 180 );
    }
}