<?php

add_action( 'rest_api_init', 'wptravel_demo_import_api' );

function wptravel_demo_import_api() {
    register_rest_route(
        'wptraveldemo/v1',
        '/import/(?P<fileUrl>\w+)',
        array(
            'methods'             => 'POST',
            'permission_callback' => '__return_true',
            'callback'            => 'wptravel_import_demo',
        )
    );

    register_rest_route(
        'wptraveldemo/v1',
        '/get_demo_list',
        array(
            'methods'             => 'GET',
            'permission_callback' => '__return_true',
            'callback'            => 'wptravel_get_demo_list',
        )
    );
}

function wptravel_import_demo( $request ) {
    $fileUrl = str_replace( '_', '-', $request->get_param( 'fileUrl' ) );

    $hello = new WP_Import();
    $hello->import( 'https://wpdemo.wensolutions.com/demo-data/data/' . $fileUrl . '.xml' );

    return $fileUrl;
}

function wptravel_get_demo_list( $request ) {
    
    $get_data_lists = file_get_contents( 'https://wpdemo.wensolutions.com/demo-data/demo.json' ); 

    $data_lists = array();
    foreach( json_decode($get_data_lists) as $data ){
        $data_list['demo_name'] = $data->demo_name;
        $data_list['text_domain'] = $data->text_domain;
        $data_list['screenshot'] = $data->screenshot;

        array_push( $data_lists, $data_list );
    }

    return $data_lists;
}


add_action( 'admin_menu', 'wptravel_add_demo_page' );

/**
 * Adds a submenu page under a custom post type parent.
 */
function wptravel_add_demo_page() {
    add_submenu_page(
        'edit.php?post_type=itinerary-booking',
        __( 'All Demo Collection', 'wp-travel-blocks' ),
        __( 'Demos', 'wp-travel-blocks' ),
        'manage_options',
        'wptravel-demo',
        'wptravel_add_demo_page_callback'
    );
}

/**
 * Display callback for the submenu page.
 */
function wptravel_add_demo_page_callback() { 
    ?>
    <div class="wrap">
        <h1><?php _e( 'Demo Collection', 'wp-travel-blocks' ); ?></h1>
        <div id="wptravel-demo-page"></div>
    </div>
    <?php
}
