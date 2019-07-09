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

add_action( 'admin_menu', 'wpdf_admin_menu' );
function wpdf_admin_menu() {
    add_menu_page(
        __( 'Dashboard', 'wpdf' ),
        'WPDF',
        'manage_options',
        'wpdf_dsh',
        'wpdf_dsh',
        'dashicons-welcome-widgets-menus'
    );
}

/**
 * Enqueue plugin styles and scripts that load in the frontend
 */
function wpdf_enqueue_public_styles() {
    if( wpdf_current_user_role() ) {
            wp_enqueue_style( 'wpdf-public-style', plugin_dir_url( __FILE__ ) . 'inc/css/wpdf-public.css', array(), '' );
            wp_enqueue_style( 'boostrap-css', plugin_dir_url( __FILE__ ) . 'lib/bootstrap/css/bootstrap.css', array(), '' );
            wp_enqueue_style( 'datatables-css', plugin_dir_url( __FILE__ ) . 'lib/mdb/css/datatables.min.css' );
            wp_enqueue_style( 'font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' );

            wp_enqueue_script( 'wpdf-main-js', plugin_dir_url( __FILE__ ) . 'inc/js/wpdf-script.js', array(), '' );
            wp_enqueue_script( 'datatables-js', plugin_dir_url( __FILE__ ) . 'lib/mdb/js/datatables.min.js' );
    }
}
add_action( 'wp_enqueue_scripts', 'wpdf_enqueue_public_styles' );

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

    return $users_list;
}

function wpdf_users_to_json() { ?>
    <script>
		var users = <?php echo json_encode( wpdf_users_list() ); ?>
	</script>
<?php }

/**
 * Users table shortcode
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
 * @package  wpdf
 * @since 1.0
 */
wpdf_users_to_json();
function wpdf_display_shortcode() {
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
        return esc_html_e( 'You\'re not authorized to see this content', 'wpdf' );
    }
}