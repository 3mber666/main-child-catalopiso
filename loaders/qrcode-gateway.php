<?php
add_action('parse_request', 'QrPageUrlHandler');

function QrPageUrlHandler() {
    
    $request_uri_string = $_SERVER['REQUEST_URI'];
    if(strpos($request_uri_string, 'qrcode') !== false) {
        
    ?>

<?php
// Start session on page load! 
// if (!isset($_SESSION)) {
//     session_start();
// }


// session_destroy();

$getItem = sanitizedText($_GET['product']);
$sanitizedEmail = validateEmail(($_POST['email']));
$sanitizedName = sanitizedText($_POST['name']);
$sanitizedPhone = sanitizedText($_POST['phone']);
$sanitizedStoreCode = sanitizedText($_POST['store-code']);
$openMsg = false;

if (isset($_POST['confirm'])) {

    $email_error = isEmailInUsed($sanitizedEmail);
    $get_url_from_db = storeCodeState($sanitizedStoreCode);

    $geturldb_1 = getUsersPerStore($sanitizedStoreCode);
    foreach ($geturldb_1 as $geturldata ) {
         $items[] = $geturldata->email;
    }

 if($get_url_from_db) {
   if(!$email_error) {
      $openConfirmation = true;
      global $wpdb;
      
      $store_data_table = $wpdb->prefix . "store_codes";
      $geturldb = $wpdb->get_results( "SELECT * FROM $store_data_table WHERE `store_code` = '$sanitizedStoreCode'");
            
      foreach ($geturldb as $geturldata ) {
         $get_store_name = $geturldata->store_name;
         $get_store_logo = $geturldata->store_logo;
      }
   }
} else {
      $message = "Store code doesn't exist";
   }
}

// Prompt if store code is verified!
if(isset($_POST['submit'])) {
    
    $get_url_from_db = storeCodeState($sanitizedStoreCode);

    $openMsg = true;
    
    // Set users information
    // setUserData($sanitizedName, $sanitizedEmail, $sanitizedPhone, $sanitizedStoreCode);
    
    // Add to woocommecerce #user = @email, #pass = phone_number
    // Cleaning Special Characters

    $cleanNumber = cleanPhoneNumber($sanitizedPhone);
    wc_create_new_customer($sanitizedEmail, '', $cleanNumber, $sanitizedName, $sanitizedPhone, $sanitizedStoreCode);
   

    // Set cookies for redirection [verified_user, url] [24 hours interval]
    setCookies('verified_user', $sanitizedStoreCode, 3600);
    setCookies('catalopiso_url', $get_url_from_db, 3600);

    $encode_name = encodeString($sanitizedName);
    $encode_email = encodeString($sanitizedEmail);
    $encode_phone = encodeString($sanitizedPhone);
    $encode_store_code = encodeString($sanitizedStoreCode);


   header("refresh:5; url=$get_url_from_db/$getItem?get&n=$encode_name&e=$encode_email&p=$encode_phone&s=$encode_store_code");
    
   // destroy current session if success
   // session_destroy();

   
   }

// check if cookies is active
if(isset($_COOKIE["verified_user"])) {
      $get_url_cookie = $_COOKIE["catalopiso_url"];
      header("Location: $get_url_cookie/$getItem");
}


?>


<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://cdn.tailwindcss.com"></script>
      <title>QR CODE</title>
   </head>
   <body>
      <div style="background-color: rgb(17 24 39)" class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
         <div class="bg-white shadow-md rounded-md py-5 px-5 max-w-md w-full space-y-8">
            <div>
               <img class="mx-auto h-12 w-auto" src="https://i.ibb.co/Sr3gJZ9/Screenshot-14-removebg-preview.png" alt="Workflow">
               <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                  Catalopiso
               </h2>
               <!-- <p class="mt-2 text-center text-sm text-gray-600">
                  Or
                  <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                      start your 14-day free trial
                  </a>
                  </p> -->
            </div>
            <form class="mt-8 space-y-6" method="POST">
            <div id="confirmation-data" class="<? echo $openConfirmation ? '' : 'hidden' ?> bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                  <div class="flex justify-end px-4 pt-4">
                     <button id="dropdownButton" data-dropdown-toggle="dropdown" class="hidden sm:inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5" type="button">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                           <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                     </button>
                     <div id="dropdown" class="hidden z-10 w-44 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700">
                        <ul class="py-1" aria-labelledby="dropdownButton">
                           <li>
                              <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                           </li>
                           <li>
                              <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Export Data</a>
                           </li>
                           <li>
                              <a href="#" class="block py-2 px-4 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="flex flex-col items-center pb-10">
                     <img class="mb-3 w-24 h-24 shadow-lg" src="<? echo $get_store_logo ? $get_store_logo : 'https://eco-trailer.co.uk/wp-content/uploads/2016/03/placeholder-blank.jpg' ?>" alt="Bonnie image"/>
                     <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white"><? echo $get_store_name ? $get_store_name : '' ?></h5>
                     <span class="text-sm text-gray-500 dark:text-gray-400">Are you working with <? echo $get_store_name ? $get_store_name : '' ?>?</span>
                     <div class="flex mt-4 space-x-3 lg:mt-6">
                        <button id="submit" name="submit" type="submit" class="inline-flex items-center py-2 px-4 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Confirm</button>
                        <a onclick="removeMsg('confirmation-data')" class="inline-flex items-center py-2 px-4 text-sm font-medium text-center text-gray-900 bg-white rounded-lg border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-700 dark:focus:ring-gray-700">Close</a>
                     </div>
                  </div>
               </div>
               <input type="hidden" name="remember" value="True">
               <div class="rounded-md shadow-sm -space-y-px">
                  <div>
                     <label for="name" class="sr-only">Name</label>
                     <input id="name" value="<? echo $sanitizedName ? $sanitizedName : '' ?>" name="name" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Name">
                  </div>
                  <div>
                     <label for="email" class="sr-only">Email</label>
                     <input id="email" value="<? echo $sanitizedEmail ? $sanitizedEmail : '' ?>" name="email" type="text" autocomplete="current-email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email">
                  </div>
                  <div>
                     <label for="phone" class="sr-only">Phone</label>
                     <input id="phone" value="<? echo $sanitizedPhone ? $sanitizedPhone : '' ?>" name="phone" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Phone">
                  </div>
                  <div>
                     <label for="store-code" class="sr-only">Store Code</label>
                     <input id="store-code" value="<? echo $sanitizedStoreCode ? $sanitizedStoreCode : '' ?>" name="store-code" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Store Code">
                  </div>
               </div>
               <div class="flex items-center justify-between">
                  <div class="flex items-center"></div>
                  <div class="text-sm">
                     <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                     How it works?
                     </a>
                  </div>
               </div>
               <div>
                  <button name="confirm" type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                     <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="True">
                           <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                     </span>
                     Go to Store
                  </button>
               </div>

               <div id="alert-msg-2" class="<?php echo $openMsgError ? "" : "hidden" ?> flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200" role="alert">
                  <svg class="flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                  </svg>
                  <div class="ml-3 text-sm font-medium text-red-700 dark:text-red-800">
                     Store Code is not found!
                  </div>
                  <button onclick="removeMsg('alert-msg-2')" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300" data-dismiss-target="#alert-2" aria-label="Close">
                     <span class="sr-only">Close</span>
                     <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                     </svg>
                  </button>
               </div>
               
               <div id="alert-msg" class="<?php echo isset($message) ? '' : 'hidden'; ?> flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200" role="alert">
                  <svg class="flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                  </svg>
                  <div class="ml-3 text-sm font-medium text-red-700 dark:text-red-800">
                     <?php echo isset($message) ? $message : ''; ?> 
                  </div>
                  <button onclick="removeMsg('alert-msg')" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300" data-dismiss-target="#alert-2" aria-label="Close">
                     <span class="sr-only">Close</span>
                     <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                     </svg>
                  </button>
               </div>






               <div id="alert-3" class="<?php echo $openMsg ? "" : "hidden" ?> flex p-4 mb-4 bg-green-100 rounded-lg dark:bg-green-200" role="alert">
                  <svg class="flex-shrink-0 w-5 h-5 text-green-700 dark:text-green-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                  </svg>
                  <div class="ml-3 text-sm font-medium text-green-700 dark:text-green-800">
                     You'll be redirected in 5 seconds. <a href="<? echo $rest_ds ?>"class="font-semibold underline hover:text-green-800 dark:hover:text-green-900">Go to this link!</a>.
                  </div>
                  <button onclick="removeMsg('alert-3')" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-green-200 dark:text-green-600 dark:hover:bg-green-300" data-dismiss-target="#alert-3" aria-label="Close">
                     <span class="sr-only">Close</span>
                     <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                     </svg>
                  </button>
               </div>
            </form>
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