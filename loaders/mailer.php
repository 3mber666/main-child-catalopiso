
<?php
/**
 * 
 *  Mailer<Custom WP Event/CRONS>
 *  Send_emails_interval 24 hours
 * 
 */
ob_start();

add_filter('cron_schedules', 'add_automailer');
function add_automailer($schedules) {
    $schedules['send_emails_interval'] = array(
        'interval' => 60*60*24, // Settings up interval [Current: 24 hours]
        'display' => __('Every 24hrs'),
    );
        return $schedules;
    }
    

    if(!wp_get_schedule('send_email_event')) {
        wp_schedule_event( time(), 'send_emails_interval', 'send_email_event');
    }
    

    add_action( 'send_email_event', 'send_email_per_customer' );
 

    function send_email_per_customer() {

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
            ON $user_data.store_code=$store_data.store_code WHERE timestamp BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND");
        
        foreach ($get_user_x as $geturldata ) {
            $body = "Your Project Board $geturldata->store_url/?password_protected_pwd=$geturldata->store_code&redirect_to=/project-boards/?key=$geturldata->key";
            wp_mail($geturldata->email, 'Today Project Board', $body);
        }
    }
    
    ob_end_flush();
?>