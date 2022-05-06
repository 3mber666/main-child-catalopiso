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

// Check User Email State
function isEmailInUsed($email) {
    global $wpdb;
    $user_data_table = $wpdb->prefix . "users_store_data";
    $user_count = $wpdb->get_var( "SELECT COUNT(*) FROM $user_data_table WHERE `email` = '$email'");
    $UserEmailState = $user_count > 1 ? true : false;
    return $UserEmailState;
}

// Check User Exist!
function isEmailExist($email) {
    global $wpdb;
    $user_data_table = $wpdb->prefix . "users";
    $user_count = $wpdb->get_var( "SELECT id FROM $user_data_table WHERE `user_email` = '$email'");
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



function createAll($get_user = '', $pass = '', $email = '', $phone, $store_code, $product = '') {
    $username = $get_user;
    $password = $pass;
    $email = $email;

    if (username_exists($username) == null && email_exists($email) == false) {
        $user_id = wp_create_user($username, $password, $email);
        $user = get_user_by('id', $user_id);
        $user->add_role('customer');
        

        global $wpdb;
        $user_data_table = $wpdb->prefix . "users_store_data";
        $premmerce_wishlist_table = "wp_premmerce_wishlist";

        $date = date('Y-m-d H:i:s');
        $key = uniqid();

        $wpdb->insert($user_data_table, array(
            'id' => $user->id,
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
            'user_id' => $user->id,
            'name' => $username,
            'wishlist_key' => $key,
            'products' => $product,
            'date_created' => $date,
            'date_modified' => $date,
            'default' => 0
        ));

    }

}


function createProjectBoard($user_id, $product) {
    global $wpdb;
    $premmerce_wishlist_table = "wp_premmerce_wishlist";
    $user_data_table = $wpdb->prefix . "users_store_data";
    
    $date = date('Y-m-d');
    $date_1 = date('Y-m-d H:i:s');
    $key = uniqid();


    $wpdb->insert($premmerce_wishlist_table, array(
        'id' => NULL,
        'user_id' => $user_id,
        'name' => $date,
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
    
}


function updateProjectBoard($id, $product) {
    global $wpdb;
    $date = date('Y-m-d');

    $getExistProduct = $wpdb->get_var( "SELECT products FROM `wp_premmerce_wishlist` WHERE `wishlist_key` = '$id' AND `products` LIKE '%$product%'");
    
    // Check if product exist in specific project

    $getProducts = $wpdb->get_var( "SELECT products FROM wp_premmerce_wishlist WHERE `wishlist_key` = '$id'");
    $addProducts = $getProducts.',';

    if(!$getExistProduct) {
    $wpdb->update('wp_premmerce_wishlist', array( 
      'products' => $addProducts.$product,
      'date_modified' => $date
      ), 
      array(
         "wishlist_key" => $id
      ));
    }

}
    

function getProjectBoard() {
    global $wpdb;
    $premmerce_wishlist_table = "wp_premmerce_wishlist";
    $user_id = get_current_user_id();
    $getProjects = $wpdb->get_var( "SELECT wishlist_key FROM $premmerce_wishlist_table WHERE `user_id` = '$user_id' LIMIT 50");
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

function wc_create_new_customer_custom( $email, $username = '', $password = '', $name, $phone, $store_code ) { 
 
    // Check the email address. 
    if ( empty( $email ) || ! is_email( $email ) ) { 
        return new WP_Error( 'registration-error-invalid-email', __( 'Please provide a valid email address.', 'woocommerce' ) ); 
    } 
 
    if ( email_exists( $email ) ) { 
        return new WP_Error( 'registration-error-email-exists', __( 'An account is already registered with your email address. Please login.', 'woocommerce' ) ); 
    } 
 
    // Handle username creation. 
    if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) || ! empty( $username ) ) { 
        $username = sanitize_user( $username ); 
 
        if ( empty( $username ) || ! validate_username( $username ) ) { 
            return new WP_Error( 'registration-error-invalid-username', __( 'Please enter a valid account username.', 'woocommerce' ) ); 
        } 
 
        if ( username_exists( $username ) ) { 
            return new WP_Error( 'registration-error-username-exists', __( 'An account is already registered with that username. Please choose another.', 'woocommerce' ) ); 
        } 
    } else { 
        $username = sanitize_user( current( explode( '@', $email ) ), true ); 
 
        // Ensure username is unique. 
        $append = 1; 
        $o_username = $username; 
 
        while ( username_exists( $username ) ) { 
            $username = $o_username . $append; 
            $append++; 
        } 
    } 
 
    // Handle password creation. 
    if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && empty( $password ) ) { 
        $password = wp_generate_password(); 
        $password_generated = true; 
    } elseif ( empty( $password ) ) { 
        return new WP_Error( 'registration-error-missing-password', __( 'Please enter an account password.', 'woocommerce' ) ); 
    } else { 
        $password_generated = false; 
    } 
 
    // Use WP_Error to handle registration errors. 
    $errors = new WP_Error(); 
 
    do_action( 'woocommerce_register_post', $username, $email, $errors ); 
 
    $errors = apply_filters( 'woocommerce_registration_errors', $errors, $username, $email ); 
 
    if ( $errors->get_error_code() ) { 
        return $errors; 
    } 
 
    $new_customer_data = apply_filters( 'woocommerce_new_customer_data', array( 
        'user_login' => $username,  
        'user_pass' => $password,  
        'user_email' => $email,  
        'role' => 'customer',  
 ) ); 
 
    $customer_id = wp_insert_user( $new_customer_data ); 
 
    if ( is_wp_error( $customer_id ) ) { 
        return new WP_Error( 'registration-error', '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong> ' . __( 'Couldn’t register you… please contact us if you continue to have problems.', 'woocommerce' ) ); 
    } 
 
    do_action( 'woocommerce_created_customer', $customer_id, $new_customer_data, $password_generated ); 


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

    // wp_premmerce_wishlist(user_id, name, wishlist_key, products, date_modified, date_created)

    $wpdb->insert($premmerce_wishlist_table, array(
        'id' => NULL,
        'user_id' => $customer_id,
        'name' => $name, 
        'wishlist_key' => $key, 
        'products' => '',
        'date_created' => $date,
        'date_modified' => $date,
        'default' => 0
    ));
    
    wp_set_current_user( $customer_id );
    wp_set_auth_cookie( $customer_id );

    return $customer_id; 
}


function get_product_by_slug($page_slug, $output = OBJECT) {
    global $wpdb;
        $product = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $page_slug, 'product'));
        if ( $product )
            return get_post($product, $output);
    return null;
}

function encodeString($str){
    for($i=0; $i<5;$i++)
    {
      $str=strrev(base64_encode($str)); //apply base64 first and then reverse the string
    }
    return $str;
  }
  
  
  function decodeString($str){
   for($i=0; $i<5;$i++)
   {
      $str=base64_decode(strrev($str)); //apply base64 first and then reverse the string}
   }
   return $str;
  }



?>