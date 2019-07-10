<?php
/**
 * The functions of the plugin.
 *
 * @link       https://segbedji.com
 * @author     justinahinon <justiny.ahinon@gmail.com>
 * @since      1.0
 *
 * @package    wpdf
 * @subpackage wpdf/admin
 */

/**
 * Check if the current user is admin
 * 
 * The plugin displays the shortcode only if the current
 * user has the role administrator. Else, there is no need
 * to run some functions or enqueue scripts and styles
 * 
 * @package  wpdf
 * @since 1.0
 * 
 * @return boolean If the current users is admin or not
 */
function wpdf_current_user_role() {
    if( is_user_logged_in() ) {
        if( wp_get_current_user()->roles[0] == 'administrator') {
            return true;
        }
        else {
            return false;
        }
    } 
    else {
        return false;
    }
}

/**
 * Retrieve the list of users ID of the site
 * 
 * @package  wpdf
 * @since 1.0
 * 
 * @return array List of users ID
 */
function wpdf_users_list() {
    // Fetch the list of users
    $users_details = get_users();

    // Retrieve data from the users list object and add them to an array
    for( $col_number = 0; $col_number < 3; $col_number++ ) {
        for( $row_number = 0; $row_number < sizeof( $users_details ); $row_number++ ) {
            $users_list[$row_number][0] = $users_details[$row_number]->user_login;
            $users_list[$row_number][1] = $users_details[$row_number]->display_name;
            $users_list[$row_number][2] = $users_details[$row_number]->roles[0];
        }
    }
    
    // Return the json encoded array
    return json_encode( $users_list );
}

/**
 * Assign the wpdf_users_list() return value to a global function
 * that will be used in wp_localize_script()
 */
$users = wpdf_users_list();

/**
 * Enqueue plugin styles and scripts that load in the frontend
 * 
 * @package  wpdf
 * @since 1.0
 */
function wpdf_enqueue_public_scripts() {
    if( wpdf_current_user_role() ) {
        // Enqueue plugin main style
        wp_enqueue_style( 'wpdf-public-style', plugin_dir_url( __FILE__ ) . 'inc/css/wpdf-public.css', array(), '' );
        
        // Enqueue bootstrap css
        wp_enqueue_style( 'boostrap-css', plugin_dir_url( __FILE__ ) . 'lib/bootstrap/css/bootstrap.css', array(), '' );
        
        // Enqueue fontawesome css
        wp_enqueue_style( 'font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' );
        
        // Enqueue datatables style file
        wp_enqueue_style( 'datatables-css', plugin_dir_url( __FILE__ ) . 'lib/mdb/css/datatables.min.css' );

        /**
         * Use the global users variable that contains the users array
         * encoded in JSon
         * 
         * The variable is use as parameter for the wp_localize_script()
         * function to be available in JavaScript main file
         * which is enqueued just before
         */
        global $users;
        wp_enqueue_script( 'wpdf-main-js', plugin_dir_url( __FILE__ ) . 'inc/js/wpdf-script.min.js', array(), '' );
        wp_localize_script( 'wpdf-main-js', 'params', $users );
        
        // Enqueue datatables JS file
        wp_enqueue_script( 'datatables-js', plugin_dir_url( __FILE__ ) . 'lib/mdb/js/datatables.min.js' );
    }
}
add_action( 'wp_enqueue_scripts', 'wpdf_enqueue_public_scripts' );
 
/**
 * Initialize the shortcode
 * 
 * The wpdf_display_shortcode() function as callback is used 
 * when the shortcode is called in a page or post
 * 
 * @package  wpdf
 * @since 1.0
 */
function wpdf_init_shortcode() {
    add_shortcode( 'wpdf-users', 'wpdf_display_shortcode' );
}
add_action('init', 'wpdf_init_shortcode');

/**
 * Implement users table shortcode
 * 
 * This function create and return a <table> HTML element
 * just with the head <thead> and an empty body <tbody>
 * 
 * It also add an id to the table which is used in JavaScript
 * by the Datatables() function to render in frontend the full 
 * table using the list of users
 * 
 * @package  wpdf
 * @since 1.0
 */
function wpdf_display_shortcode() {
    /**
     * Display the shortcode content only if the current user is admin
     * 
     * Else, a custom message is displayed
     */
    if( wpdf_current_user_role() ) {
        $output = '';

        $table_content = '<div class="table-responsive users-table-wrapper">';
        $table_content .='<table id="users-table">';
        $table_content .='<thead>
                            <tr>
                                <th class="table-head">User name</th>
                                <th class="table-head">Display name</th>
                                <th class="table-head">Role</th>
                            </tr>
                        </thead>';

        $table_content .= '<tbody></tbody>';

        $table_content .= '</table>';
        $table_content .= '<div>';

        $output .= $table_content;
        return $output;
    }
    else {
        return 'You\'re not authorized to see this content';
    }
}