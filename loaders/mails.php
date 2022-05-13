<?php

add_action('parse_request', 'todaysCustomer');
function todaysCustomer() {

$request_uri_string = $_SERVER['REQUEST_URI'];
    if(strpos($request_uri_string, 'yesterday') !== false) {

        $code = sanitizedText($_GET['code']);

        $open = false;
        if($code === 'yew') {
            $open = true;
        }


        global $wpdb;
    
    $user_data_table = $wpdb->prefix . "users_store_data";
    $store_data_table = $wpdb->prefix . "store_codes";

    
    $get_user_x = $wpdb->get_results ("SELECT  $user_data_table.id, 
    $user_data_table.timestamp, 
    $user_data_table.name, 
    $user_data_table.email, 
    $user_data_table.name, 
    $user_data_table.phone, 
    $user_data_table.key, 
    $user_data_table.store_code,
    $store_data_table.store_url
    FROM $user_data_table
    INNER JOIN wp_store_codes
    ON $user_data_table.store_code=$store_data_table.store_code WHERE DATE(timestamp) = CURRENT_DATE()-1");
    
    ?>

<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://cdn.tailwindcss.com"></script>
      <title>Yesterday Data</title>
   </head>
   <body>

   <ul id="authors"></ul>
   <ul id="ul"></ul>
   <div class="sm:px-6 w-full">
            <div class="px-4 md:px-10 py-4 md:py-7">
                <div class="flex items-center justify-between"></div>
            </div>
            <div class="bg-white dark:bg-gray-900  py-4 md:py-7 px-4 md:px-8 xl:px-10">
                <div class="sm:flex items-center justify-between">
                    <div class="flex items-center">
                        <a class="rounded-full focus:outline-none focus:ring-2  focus:bg-indigo-50 focus:ring-indigo-800" href=" javascript:void(0)">
                            <div class="py-2 px-8 bg-indigo-100 text-indigo-700 rounded-full">
                                <p>All</p>
                            </div>
                        </a>
                        <!-- <a class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800 ml-4 sm:ml-8" href="javascript:void(0)">
                            <div class="py-2 px-8 text-gray-600 dark:text-gray-200  hover:text-indigo-700 hover:bg-indigo-100 rounded-full ">
                                <p>Done</p>
                            </div>
                        </a>
                        <a class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800 ml-4 sm:ml-8" href="javascript:void(0)">
                            <div class="py-2 px-8 text-gray-600 dark:text-gray-200  hover:text-indigo-700 hover:bg-indigo-100 rounded-full ">
                                <p>Pending</p>
                            </div>
                        </a> -->
                    </div>
                    <!-- <button onclick="popuphandler(true)" class="focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 bg-indigo-700 hover:bg-indigo-600 focus:outline-none rounded">
                        <p class="text-sm font-medium leading-none text-white">Export User</p>
                    </button> -->
                </div>
                <div class="mt-7 overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <tbody>
                             <?php foreach($get_user_x as $geturldata): ?>
                                <tr class="h-3"></tr>
                                <tr tabindex="0" class="focus:outline-none  h-16 border border-gray-100 dark:border-gray-600  rounded">
                                <td>
                                    <div class="ml-5">
                                        <div class="bg-gray-200 dark:bg-gray-800  rounded-sm w-5 h-5 flex flex-shrink-0 justify-center items-center relative">
                                            <input placeholder="checkbox" type="checkbox" class="focus:opacity-100 checkbox opacity-0 absolute cursor-pointer w-full h-full" />
                                            <div class="check-icon hidden bg-indigo-700 text-white rounded-sm">
                                                <img src="https://tuk-cdn.s3.amazonaws.com/can-uploader/tasks-svg7.svg" alt="check-icon">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td  class="focus:text-indigo-600 ">
                                    <div class="flex items-center pl-5">
                                        <p class="text-base font-medium leading-none text-gray-700 dark:text-white  mr-2"><?= $geturldata->name; ?></p>
                                    </div>
                                </td>
                                <td class="pl-24">
                                    <div class="flex items-center">
                                    <svg class="fill-gray-700 dark:fill-white" width="20" height="20" viewBox="0 0 24 24"><path d="M8.012 15.876v4.124l1.735-2.578-1.735-1.546zm-4.026 5.871c-.645.405-1.311.765-1.986 1.069l.492 1.184c.675-.303 1.343-.658 1.992-1.056l-.498-1.197zm3.014-2.407c-.59.581-1.253 1.171-1.932 1.67l.505 1.214c.487-.346.977-.758 1.427-1.146v-1.738zm9-13.34l-3.195 12.716-4.329-3.855 4.154-4.385-5.568 3.849-3.843-.934 12.781-7.391zm-2 .001l-.944.546c-.034-.178-.056-.359-.056-.547 0-1.654 1.346-3 3-3s3 1.346 3 3c0 1.557-1.196 2.826-2.716 2.971l.266-1.058c.835-.24 1.45-1.001 1.45-1.913 0-1.104-.896-2-2-2s-2 .896-2 2.001zm8-.001c0 3.313-2.687 6-6 6l-.471-.024.497-1.979c2.194-.015 3.974-1.801 3.974-3.997 0-2.206-1.794-4-4-4s-4 1.794-4 4c0 .371.067.723.162 1.064l-1.779 1.029c-.243-.653-.383-1.356-.383-2.093 0-3.313 2.687-6 6-6s6 2.687 6 6z"/></svg>
                                        <p class="text-sm leading-none text-gray-600 dark:text-gray-200  ml-2"><?= $geturldata->email; ?></p>
                                    </div>
                                </td>
                                <td class="pl-5">
                                    <div class="flex items-center">
                                    <svg class="fill-gray-700 dark:fill-white" width="24" height="24" viewBox="0 0 24 24"><path d="M20 22.621l-3.521-6.795c-.008.004-1.974.97-2.064 1.011-2.24 1.086-6.799-7.82-4.609-8.994l2.083-1.026-3.493-6.817-2.106 1.039c-7.202 3.755 4.233 25.982 11.6 22.615.121-.055 2.102-1.029 2.11-1.033z"/></svg>
                                        <p class="text-sm leading-none text-gray-600 dark:text-gray-200  ml-2"><?= $geturldata->phone; ?></p>
                                    </div>
                                </td>
                                <td class="pl-5">
                                </td>
                                <td class="pl-5">
                                    <button class="py-3 px-6 focus:outline-none text-sm leading-none text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800 rounded">Email Received</button>
                                </td>
                                <td class="pl-4">
                                    <a target="_blank" href="<?= $geturldata->store_url; ?>/project-boards/?key=<?= $geturldata->key; ?>"><button class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-gray-600 dark:text-gray-200  py-3 px-5 bg-gray-100 rounded hover:bg-gray-200 dark:hover:bg-gray-700   dark:bg-gray-800  focus:outline-none">View Project Board</button></a>
                                </td>
                                <td>
                                    <div class="relative px-5 pt-2">
                                        <button class="focus:ring-2 rounded-md focus:outline-none" onclick="dropdownFunction(this)" role="button" aria-label="option">
                                            <img  class="dropbtn" onclick="dropdownFunction(this)" src="https://tuk-cdn.s3.amazonaws.com/can-uploader/tasks-svg6.svg" alt="dropdown">
                                        </button>
                                        <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                            <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                                <p>Edit</p>
                                            </div>
                                            <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                                <p>Delete</p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
    <?php endforeach; ?>

                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <style>
            .checkbox:checked + .check-icon {
                display: flex;
            }
        </style>
   </body>
</html>

<?php
      exit();
	}
}

?>