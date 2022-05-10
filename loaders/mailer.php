
<?php


// add_filter('cron_schedules', 'add_automailer');

// function add_automailer($schedules) {
//     $schedules['send_emails_interval'] = array(
//         'interval' => 300, // Settings up interval [Current: 24 hours]
//         'display' => __('Every 24hrs'),
//     );
//         return $schedules;
//     }
    

//     if(!wp_get_schedule('send_email_event')) {
//         wp_schedule_event( time(), 'send_emails_interval', 'send_email_event');
//     }
    

//     add_action( 'send_email_event', 'send_email_per_customer' );
 

//     function send_email_per_customer() {

//         global $wpdb;
//         $user_data = $wpdb->prefix . "users_store_data";
//         $store_data = $wpdb->prefix . "store_codes";
        
//         $get_user_x = $wpdb->get_results( "SELECT $user_data.timestamp,
//             $user_data.email,
//             $user_data.name,
//             $user_data.phone,
//             $user_data.key,
//             $user_data.store_code,
//             $store_data.store_url
//             FROM $user_data
//             INNER JOIN $store_data
//             ON $user_data.store_code=$store_data.store_code WHERE timestamp BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND");
        
//         foreach ($get_user_x as $geturldata ) {
//             $headers = "MIME-Version: 1.0\r\n";
//             $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
//             $body = "Your Project Board $geturldata->store_url/?password_protected_pwd=$geturldata->store_code&redirect_to=/project-boards/?key=$geturldata->key";
//             wp_mail($geturldata->email, 'Here\'s the link of your project board for this day', $body, $headers);
//         }
//     }


function my_cron_schedules($schedules){
    if(!isset($schedules["5min"])){
        $schedules["5min"] = array(
            'interval' => 5*60,
            'display' => __('Once every 5 minutes'));
    }
    if(!isset($schedules["30min"])){
        $schedules["30min"] = array(
            'interval' => 30*60,
            'display' => __('Once every 30 minutes'));
    }
    return $schedules;
}

add_filter('cron_schedules','my_cron_schedules');

function schedule_my_cron(){
    // Schedules the event if it's NOT already scheduled.
    if ( ! wp_next_scheduled ( 'my_5min_event' ) ) {
        wp_schedule_event( time(), '5min', 'my_5min_event' );
    }
}

// Registers and schedules the my_5min_event cron event.
add_action( 'init', 'schedule_my_cron' );

// Runs fivemin_schedule_hook() function every 5 minutes.
add_action( 'my_5min_event', 'fivemin_schedule_hook' );


function fivemin_schedule_hook() {
    global $wpdb;
        $user_data = $wpdb->prefix . "users_store_data";
        $store_data = $wpdb->prefix . "store_codes";
        
        $get_user_x = $wpdb->get_results( "SELECT $user_data.timestamp,
            $user_data.email,
            $user_data.name,
            $user_data.phone,
            $user_data.key,
            $user_data.store_code,
            $store_data.store_url
            FROM $user_data
            INNER JOIN $store_data
            ON $user_data.store_code=$store_data.store_code WHERE DATE(timestamp) = CURRENT_DATE()-1");
        
        foreach ($get_user_x as $geturldata ) {
            $body = "Your Project Board $geturldata->store_url/?password_protected_pwd=$geturldata->store_code&redirect_to=/project-boards/?key=$geturldata->key";
            wp_mail($geturldata->email, 'Here\'s the link of your project board for this day', $body);
        }
}

?>