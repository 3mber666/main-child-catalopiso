<?php

/**
 * 
 *  Custom Helpers [Biradams]
 *  Tugas Virtual Solution
 *  
 */

 // validate email as always
function validateEmail($email) {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    } else {
        return false;
    }
}


// Cleaning special chars, numbers and etc
function cleanPhoneNumber($string) {
    $string = str_replace("-", "", $string);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
 }

// Sanitizing inputs at it's finest
function sanitizedText($data) {
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
}

// Check User Exist!
function isEmailExist($email) {
    global $wpdb;
    $date = date('Y-m-d H:i:s');

    $user_data_table = $wpdb->prefix . "users";
    $store_user_data = $wpdb->prefix . "users_store_data";
    $user_count = $wpdb->get_var( "SELECT id FROM $user_data_table WHERE `user_email` = '$email'");

    if($user_count) {
    wp_set_current_user( $user_count );
    wp_set_auth_cookie( $user_count );

    $wpdb->update($store_user_data, array(
        'timestamp' => $date
        ), 
        array(
           "id" => $user_count
    ));

    }

    return $user_count;
}

// Check Store Code State
function storeCodeState($code){
    global $wpdb;
    $store_data_table = $wpdb->prefix . "store_codes";
    $geturldb = $wpdb->get_results( "SELECT * FROM $store_data_table WHERE `store_code` = '$code'");
    foreach ($geturldb as $geturldata ) {
        $get_url_from_db = $geturldata->store_url;
    }
    return $get_url_from_db;
}

// function getStoreData(): void {
//     global $wpdb;
//     $store_data_table = $wpdb->prefix . "store_codes";
//     $geturldb = $wpdb->get_results( "SELECT * FROM $store_data_table WHERE `store_code` = '$code'");
//     foreach ($geturldb as $geturldata ) {
//         $get_url_from_db = $geturldata->store_url;
//         $get_url_from_db = $geturldata->store_url;
//         $get_url_from_db = $geturldata->store_url;
//     }
// }


// Set Cookies
function setCookies($name, $value, $expiration){
    $cookie_name = "verified";
    $cookie_value = $sanitizedStoreCode;
    setcookie($name, $value, time() + ($expiration * 24), "/");
}


// Set user data in [user_store_data db table]
function setUserData($name, $email, $phone, $store_code) {
    global $wpdb;
    $user_data_table = $wpdb->prefix . "users_store_data";
    $premmerce_wishlist_table = "wp_premmerce_wishlist";

    $date = date('Y-m-d H:i:s');
    $key = uniqid();

    $wpdb->insert($user_data_table, array(
        'id' => NULL,
        'timestamp' => $date,
        'name' => $name, 
        'email' => $email, 
        'phone' => $phone, 
        'store_code' => $store_code,
        'key' => $key
    ));

    // // wp_premmerce_wishlist(user_id, name, wishlist_key, products, date_modified, date_created)

    // $wpdb->insert('wp_premmerce_wishlist', array(
    //     'id' => NULL,
    //     'name' => $name, 
    //     'key' => $key, 
    //     'products' => '', 
    //     'date_modified' => $date,
    //     'date_created' => $date
    // ));
    
}



function createAll($get_user = '', $name = '', $pass = '', $email = '', $phone, $store_code, $product = '') {
    $username = $get_user;
    $password = $pass;
    $email = $email;

    if (username_exists($username) == null && email_exists($email) == false) {
        // $user_id = wp_create_user($username, $password, $email);
        // $user = get_user_by('id', $user_id);
        // $user->add_role('customer');
        

        $userData = array(
            'user_login' => $username,
            'first_name' => $name,
            'user_pass' => $password,
            'user_email' => $email,
            'role' => 'customer'
        );

        $user_id =  wp_insert_user( $userData );

        global $wpdb;
        $user_data_table = $wpdb->prefix . "users_store_data";
        $premmerce_wishlist_table = "wp_premmerce_wishlist";

        $date = date('Y-m-d H:i:s');
        $date_1 = date('Y-m-d');
        $key = uniqid();

        $wpdb->insert($user_data_table, array(
            'id' => $user_id,
            'timestamp' => $date,
            'name' => $username, 
            'email' => $email, 
            'phone' => $phone, 
            'store_code' => $store_code,
            'key' => $key
        ));

        // wp_premmerce_wishlist(user_id, name, wishlist_key, products, date_modified, date_created)


        setCookies('count', $key, 3600);

        $wpdb->insert($premmerce_wishlist_table, array(
            'id' => NULL,
            'user_id' => $user_id,
            'name' => 'Sample Search '.$date_1,
            'wishlist_key' => $key,
            'products' => $product,
            'date_created' => $date,
            'date_modified' => $date,
            'default' => 0
        ));

        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );
        
        $website_url = get_site_url();

        $body = "Your Project Board: <a href='$website_url/project-boards/?key=$key&code=letmein'>Click Here</a>";
        wp_mail($email, 'Here\'s the link of your project board for this day', $body);
    }

}

function createProjectBoard($user_id, $email, $product) {

    global $wpdb;
    $premmerce_wishlist_table = "wp_premmerce_wishlist";
    $user_data_table = $wpdb->prefix . "users_store_data";
    
    $date = date('Y-m-d');
    $date_1 = date('Y-m-d H:i:s');
    $key = uniqid();

    $wpdb->insert($premmerce_wishlist_table, array(
        'id' => NULL,
        'user_id' => $user_id,
        'name' => 'Sample Search '.$date,
        'wishlist_key' => $key,
        'products' => $product,
        'date_created' => $date_1,
        'date_modified' => $date_1,
        'default' => 0
    ));
    
    $wpdb->update($user_data_table, array(
        'key' => $key,
        'timestamp' => $date_1
        ), 
        array(
           "id" => $user_id
    ));

    setCookies('count', $key, 3600);

    $website_url = get_site_url();
    $body = "Your Project Board: <a href='$website_url/project-boards/?key=$key&code=letmein'>Click Here</a>";
    wp_mail($email, 'Here\'s the link of your project board for this day', $body);

}



function getUsersYesterday() {

    global $wpdb;
    $user_data_table = $wpdb->prefix . "users_store_data";
    $store_data_table = $wpdb->prefix . "store_codes";
    $query = "SELECT  $user_data_table.id, 
    $user_data_table.timestamp, 
    $user_data_table.name, 
    $user_data_table.email, 
    $user_data_table.name, 
    $user_data_table.phone, 
    $user_data_table.key, 
    $user_data_table.store_code,
    $store_data_table.store_url
    FROM $user_data_table
    INNER JOIN $store_data_table
    ON $user_data_table.store_code=$store_data_table.store_code WHERE DATE(timestamp) = CURRENT_DATE()-1;";
    

    // $query = "SELECT * FROM $user_data_table WHERE `store_code` = '$getStore_code' LIMIT 50";
    $prepared_query = $wpdb->prepare($query);
    $data = $wpdb->get_results($prepared_query);
    
    return $data;
}


function updateProjectBoard($id, $product) {
    global $wpdb;
    $date_1 = date('Y-m-d H:i:s');

    // Check if Product Exist
    $getExistProduct = $wpdb->get_var( "SELECT products FROM `wp_premmerce_wishlist` WHERE `wishlist_key` = '$id' AND `products` LIKE '%$product%'");
    
    $getProducts = $wpdb->get_var( "SELECT products FROM wp_premmerce_wishlist WHERE `wishlist_key` = '$id'");
    $addProducts = $getProducts.',';

    if(!$getExistProduct) {
    $wpdb->update('wp_premmerce_wishlist', array( 
        'products' => $addProducts.$product,
        'date_modified' => $date_1
      ), 
      array(
         'wishlist_key' => $id
      ));
    }
}


function getProjectBoard($email) {

    global $wpdb;
    $premmerce_wishlist_table = "wp_premmerce_wishlist";
    $user_data_table = "wp_users_store_data";
    
    $getProjects = $wpdb->get_var("SELECT wishlist_key 
        FROM $premmerce_wishlist_table
        INNER JOIN $user_data_table
        ON $user_data_table.id=$premmerce_wishlist_table.user_id
        WHERE `email` = '$email' 
        AND `date_created` >= NOW() - INTERVAL 1 DAY");

    return $getProjects;
}

function setStore($name, $code, $url, $logo) {
    global $wpdb;
    $wpdb->insert('wp_store_codes', array(
        'store_name' => $name,
        'store_code' => $code,
        'store_url' => $url,
        'store_logo' => $logo,
    ));
}

function getAllStore() {
    global $wpdb;
    
    $store_data_table = $wpdb->prefix . "store_codes";
    $query = "SELECT * FROM $store_data_table LIMIT 50";
    $prepared_query = $wpdb->prepare($query);
    $data = $wpdb->get_results($prepared_query);
    
    return $data;
}

function getUsersPerStore($getStore_code) {

    global $wpdb;
    $user_data_table = $wpdb->prefix . "users_store_data";
    $store_data_table = $wpdb->prefix . "store_codes";

    $query = "SELECT $user_data_table.id, 
        $user_data_table.timestamp, 
        $user_data_table.name, 
        $user_data_table.email, 
        $user_data_table.name, 
        $user_data_table.phone, 
        $user_data_table.key, 
        $store_data_table.store_code,
        $store_data_table.store_url
        FROM $user_data_table
        INNER JOIN $store_data_table
        ON $user_data_table.store_code=$store_data_table.store_code WHERE $user_data_table.store_code = '$getStore_code' AND timestamp >= now() - INTERVAL 1 DAY";
    
        // $query = "SELECT * FROM $user_data_table WHERE `store_code` = '$getStore_code' LIMIT 50";
        $prepared_query = $wpdb->prepare($query, $getStore_code);
        $data = $wpdb->get_results($prepared_query);
        
    return $data;
}

function get_product_by_slug($page_slug, $output = OBJECT) {
    global $wpdb;
        $product = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $page_slug, 'product'));
        if ( $product )
            return get_post($product, $output);
    return null;
}

function custom_cron_schedule( $schedules ) {
    if(!isset($schedules['5min'])){
      $schedules['5min'] = array(
        'interval' => 5 * MINUTE_IN_SECONDS,
        'display' => __('Once every 5 minutes'));
    }
    if(!isset($schedules['20min'])){
      $schedules['20min'] = array(
        'interval' => 20 * MINUTE_IN_SECONDS,
        'display' => __('Once every 20 minutes'));
    }
    return $schedules;
 }

if (!wp_next_scheduled('name_your_cron')) {
   wp_schedule_event( time(), '5min', 'name_your_cron' );
}

function my_schedule_hook() {
	
    global $wpdb;
	
    $user_data_table = $wpdb->prefix . "users_store_data";
    $store_data_table = $wpdb->prefix . "store_codes";
    $project_board_table = "wp_premmerce_wishlist";

    $get_user_x = $wpdb->get_results ("SELECT $user_data_table.id, 
    $user_data_table.timestamp, 
    $user_data_table.name, 
    $user_data_table.email, 
    $user_data_table.name, 
    $user_data_table.phone, 
    $user_data_table.key,
    $project_board_table.date_modified, 
    $project_board_table.default, 
    $user_data_table.store_code,
    $store_data_table.store_url
    FROM $user_data_table
    INNER JOIN $store_data_table
    INNER JOIN $project_board_table
    ON $user_data_table.store_code=$store_data_table.store_code WHERE $project_board_table.date_modified < DATE_SUB(NOW(), INTERVAL 1 HOUR) AND $project_board_table.default = '0'");

    foreach ($get_user_x as $geturldata ) {

    $body = "Your Project Board $geturldata->store_url/project-boards/?key=$geturldata->key&code=letmein";
    $mail = wp_mail($geturldata->email, 'Here\'s the link of your project board for this day', $body);
    
    if($mail) {
        $wpdb->update('wp_premmerce_wishlist', array( 
            'default' => 1
        ), 
        array(
            'wishlist_key' => $geturldata->key
        ));
        }
    }
}
?>