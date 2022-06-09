<?php

/**
 * Create Users [Create Wishlist] -> /set_uri?&n={name}&e={email}&p={phone_number}&s={store_code}&product=1&url={product_prefix}
 * Return Users [For Filters, Searches and ETC only] -> /set_uri?&product=0&return=1&url={product_prefix}
 * Return Users [Update Wishlist] ->
 * 
 * */

add_action('parse_request', 'userReceiver1');

function userReceiver1() {
$request_uri_string = $_SERVER['REQUEST_URI'];
    if(strpos($request_uri_string, 'generate-codes') !== false) {
		

    ?>
	<html>
<head>
</head>
<body>
        <table style="display: none" id="tblStocks" cellpadding="0" cellspacing="0">
            <tr>
                <th>Product Name</th>
                <th>QR CODES</th>
              </tr>
              <?php 
			
			global $wpdb;
$results = $wpdb->get_results($wpdb->prepare("SELECT post_name FROM wp_posts WHERE post_type IN ('product','product_variation') AND wp_posts.post_status = 'publish'"));


	foreach ($results as $geturldata ) {
    	echo '<tr>';
    	echo '<td>'.ucfirst(str_replace("-", " ", $geturldata->post_name)).'</td>';
    	echo '<td>=image("https://quickchart.io/qr?text=https://catalopiso.com/qrcode?product=/store/'.$geturldata->post_name.'/&size=150")</td>';
    	echo '</tr>';

	}
	
	?>
        </table>
        <br />
        <button onclick="exportData()">
            <span class="glyphicon glyphicon-download"></span>
            Download list</button>
			<script>
			function exportData(){
    /* Get the HTML data using Element by Id */
    var table = document.getElementById("tblStocks");
 
    /* Declaring array variable */
    var rows =[];
 
      //iterate through rows of table
    for(var i=0,row; row = table.rows[i];i++){
        //rows would be accessed using the "row" variable assigned in the for loop
        //Get each cell value/column from the row
        column1 = row.cells[0].innerText;
        column2 = row.cells[1].innerText;
 
    /* add a new records in the array */
        rows.push(
            [
                column1,
                column2,
            ]
        );
 
        }
        csvContent = "data:text/csv;charset=utf-8,";
         /* add the column delimiter as comma(,) and each row splitted by new line character (\n) */
        rows.forEach(function(rowArray){
            row = rowArray.join(",");
            csvContent += row + "\r\n";
        });
 
        /* create a hidden <a> DOM node and set its download attribute */
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();

		today = mm + '/' + dd + '/' + yyyy + '- QR_CODE_GENERATED';
        link.setAttribute("download", `${today}.csv`);
        document.body.appendChild(link);
         /* download the data file named "Stock_Price_Report.csv" */
        link.click();
}
			</script>
</body>
</html>
<?php
      exit();
	}
}

?>
