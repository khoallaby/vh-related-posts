<?php

class vh_related_posts {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'add_css_js' ), 10 );
        add_action( 'admin_enqueue_scripts', array( $this, 'add_css_js' ), 10 );
    }

    public function load_admin() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );
    }


    /**
     * Adds css/js
     */
    public function add_css_js( $page ) {
        #if( !in_array($page, array('post.php', 'post-new.php', 'page.php', 'page-new.php' )) )
        #    return;
        wp_enqueue_style( 'vh-rp', plugins_url('style.css', __FILE__), 10 );
        if( is_admin() )
            wp_enqueue_script( 'vh-rp', plugins_url('script.js', __FILE__), 10 );
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
     * $param string $type The type of box
     */
    public function render_box( $type ) {
        $h2_titles = $this->return_metakeys();
        if( in_array($type, array_keys($this->return_metakeys())) )
            include( dirname( __FILE__ ) . "/templates/box-{$type}.php" );
    }

    /**
     * Renders boxes
     *
     * $param string $type The type of box
     */
    public function render_boxes( $post ) {
        $posts = get_post_meta( $post->ID, '_vh_related_posts', true );
        foreach( $posts as $post_id ) {
            $box = get_post( $post_id );
            #if( )
        }
    }


    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'vhrp_custom_box', 'vhrp_custom_box_nonce' );

        $saved_values = get_post_meta( $post->ID, '_vh_related_posts', true );


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
            'video' => 'Video',
            'app-note' => 'App Note',
            'case-study' => 'Case Study',
            'testing-services' => 'Testing Services',
            'software-product' => 'Software Product',
            'hardware-product' => 'Hardware Product'
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
        update_post_meta( $post_id, '_vh_related_posts', $_POST['vh_rp_posts'] );
    }


}