<?php


add_action('parse_request', 'my_custom_url_handler1');
function my_custom_url_handler1() {
    
    $request_uri_string = $_SERVER['REQUEST_URI'];
    if(strpos($request_uri_string, 'install') !== false) {

if(isset($_POST['users_information'])) {
global $wpdb;
$plugin_name_db_version = '1.0';
$table_name = $wpdb->prefix . "users_store_data"; 
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE $table_name (
    `id` mediumint(9) NOT NULL AUTO_INCREMENT,
    `timestamp` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `store_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    UNIQUE KEY `id` (`id`)
  ) $charset_collate;";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
add_option( 'plugin_name_db_version', $plugin_name_db_version );

$message = true;
}

if(isset($_POST['store_codes'])) {
    global $wpdb;
    $plugin_name_db_version = '1.0';
    $table_name = $wpdb->prefix . "store_codes"; 
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        `id` tinyint(10) NOT NULL AUTO_INCREMENT,
        `store_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `store_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `store_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `store_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        PRIMARY KEY (`id`)
      ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    add_option( 'plugin_name_db_version', $plugin_name_db_version );

    $message = true;
}

global $wpdb;
$table_name1 = $wpdb->prefix . "store_codes";
$table_name2 = $wpdb->prefix . "users_store_data"; 
$table1 = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name1 ) ) === $table_name1;
$table2 = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name2 ) ) === $table_name2;

// if ($table1 && $table2) {
//     header("Location: /index.php");
//     exit();
// }
    ?>

    
<html>
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>One Click Install</title>
        <style>
            body {
                background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            }
        </style>
      </head>
      <body>
      <div id="walletModal" tabindex="-1" aria-hidden="true" class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center flex">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex justify-between items-center py-4 px-6 rounded-t border-b dark:border-gray-600">
                <h3 class="text-base font-semibold text-gray-900 lg:text-xl dark:text-white">
                    Create Tables
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="walletModal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                </button>
            </div>
            <div class="p-6">
                <p class="text-sm font-normal text-gray-500 dark:text-gray-400">One-click installs for the database table to make the plugins work.</p>
                <ul class="my-4 space-y-3">
                    <form action="" method="POST">
                    <li>
                        <button name="store_codes" type="submit" class="flex items-center p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                            <svg class="h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M10 9v-1.098l1.047-4.902h1.905l1.048 4.9v1.098c0 1.067-.933 2.002-2 2.002s-2-.933-2-2zm5 0c0 1.067.934 2 2.001 2s1.999-.833 1.999-1.9v-1.098l-2.996-5.002h-1.943l.939 4.902v1.098zm-10 .068c0 1.067.933 1.932 2 1.932s2-.865 2-1.932v-1.097l.939-4.971h-1.943l-2.996 4.971v1.097zm-4 2.932h22v12h-22v-12zm2 8h18v-6h-18v6zm1-10.932v-1.097l2.887-4.971h-2.014l-4.873 4.971v1.098c0 1.066.933 1.931 2 1.931s2-.865 2-1.932zm15.127-6.068h-2.014l2.887 4.902v1.098c0 1.067.933 2 2 2s2-.865 2-1.932v-1.097l-4.873-4.971zm-.127-3h-14v2h14v-2z"/></svg>
                            <span class="flex-1 ml-3 whitespace-nowrap">Create Store Codes</span>
                        </button>
                    </li>
                    <li>
                        <button name="users_information" class="mt-2	flex items-center p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                            <svg class="h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M10.644 17.08c2.866-.662 4.539-1.241 3.246-3.682-3.932-7.427-1.042-11.398 3.111-11.398 4.235 0 7.054 4.124 3.11 11.398-1.332 2.455.437 3.034 3.242 3.682 2.483.574 2.647 1.787 2.647 3.889v1.031h-18c0-2.745-.22-4.258 2.644-4.92zm-12.644 4.92h7.809c-.035-8.177 3.436-5.313 3.436-11.127 0-2.511-1.639-3.873-3.748-3.873-3.115 0-5.282 2.979-2.333 8.549.969 1.83-1.031 2.265-3.181 2.761-1.862.43-1.983 1.34-1.983 2.917v.773z"/></svg>
                            <span class="flex-1 ml-3 whitespace-nowrap">Create Users Information</span>
                        </button>
                    </li>
                </form>
                <div id="alert-3" class="<? echo $message ? '' : 'hidden'?> flex p-4 mb-4 bg-green-100 rounded-lg dark:bg-green-200" role="alert">
  <svg class="flex-shrink-0 w-5 h-5 text-green-700 dark:text-green-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
  <div class="ml-3 text-sm font-medium text-green-700 dark:text-green-800">
    Added Successfully!
  </div>
  <button onclick="removeMsg('alert-3')" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-green-200 dark:text-green-600 dark:hover:bg-green-300" data-dismiss-target="#alert-3" aria-label="Close">
    <span class="sr-only">Close</span>
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
  </button>
</div>
                </ul>
                <div>
                    <a href="#" class="inline-flex items-center text-xs font-normal text-gray-500 hover:underline dark:text-gray-400">
                        <svg class="mr-2 w-3 h-3" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z"></path></svg>
                        Note: This is only for catalopiso site.</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
         function removeMsg(id) {
           var element = document.getElementById(id);
               element.classList.add("hidden"); 
            }
      </script>
</body>
</html>
<?php
      exit();

    }
}