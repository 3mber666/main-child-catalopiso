<?php
/*
 * 
 * Create Custom Rest API End Points.
 * <https://url/wp-json/wprk/v1/settings>
 * 
 * 
 */

ob_start();
class WP_React_Settings_Rest_Route {
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'create_rest_routes' ] );
    }

    public function create_rest_routes() {
        
        // Get all the store data [name, code, url]
        register_rest_route( 'wprk/v1', '/settings', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [ $this, 'get_store' ],
            'permission_callback' => [ $this, 'get_settings_permission' ]
        ]);

        // Post, Edit all the store data [name, code, url]
        register_rest_route( 'wprk/v1', '/settings', [
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => [ $this, 'save_store' ],
            'permission_callback' => [ $this, 'save_settings_permission' ]
        ]);

        register_rest_route( 'wprk/v1', '/delete', [
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => [ $this, 'delete_store' ],
            'permission_callback' => [ $this, 'save_settings_permission' ]
        ]);

        // Get all the information [Separated: Store code Used]
        register_rest_route( 'store/v1', '/users/(?P<store_code>.+)', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [ $this, 'get_users_per_store' ],
            'permission_callback' => [ $this, 'get_settings_permission' ]
        ]);

    }

    public function delete_store(WP_REST_Request $request) {
        
        $id = sanitize_text_field( $request['store_id'] );
        global $wpdb;
        $table = 'wp_store_codes';
        $wpdb->delete( $table, array( 'id' => $id ) );
        
        return rest_ensure_response( $data );
    }

    public function get_users_per_store(WP_REST_Request $request) {
        $getStore_code = urldecode($request->get_param('store_code'));
        $data = getUsersPerStore($getStore_code);
        return rest_ensure_response( $data );
    }


    public function get_store() {
        $data = getAllStore();
        return rest_ensure_response( $data );
    }

    public function save_store(WP_REST_Request $request) {
        
        $store_name = sanitize_text_field( $request['test1'] );
	    $store_code = sanitize_text_field( $request['test2'] );
	    $store_url = sanitize_text_field( $request['test3'] );
        $store_logo = sanitize_text_field( $request['test4'] );

        $parseCode =  preg_split("/[\s,]+/", $store_code);

        $input = $parseCode;
        $data = array();
        
        foreach($input as $value){
            $assign = md5($value);
            $data[$value] = $assign;
        }
        
        update_option('password_protected_password', $data);

        setStore($store_name, $store_code, $store_url, $store_logo);
        return rest_ensure_response( 'success' );
    }

    // SET PERMISSIONS
    public function get_settings_permission() {
        return true;
    }

    public function save_settings_permission() {
        return current_user_can( 'publish_posts' );
    }

    public function save_settings_permission1() {
        return current_user_can( 'delete_posts' );
    }
}

new WP_React_Settings_Rest_Route();
ob_end_flush();