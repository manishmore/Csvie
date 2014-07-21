<?php
$host = 'localhost'; // MYSQL database host adress
$db = 'upload'; // MYSQL database name
$user = 'root'; // Mysql Datbase user
$pass = 'root'; // Mysql Datbase password
 if($_SERVER['REQUEST_METHOD'] == "POST") {
 function exportCSV() {
	$host = 'localhost'; // MYSQL database host adress
	$user = 'root'; // Mysql Datbase user
	$pass = 'root'; // Mysql Datbase password
	$con = mysql_connect($host, $user, $pass);
    
    if (!$con) {
      die('Could not connectss: ' . mysql_error());
    }

    //change databse name here 
    $db = 'test1';
    mysql_select_db("$db", $con);
    $getTable = "SELECT a.sku,a.vsku,a3.name  AS product_tags_name,a.title  AS product_name ,a.description  AS product_description,a.price  AS product_price,a.procesing_unit,a12.title AS shop_name,a14.iso2 AS ship_from_country,
a8.picture  AS product_pic_name,a15.title AS Category_title,a1.quantity AS product_quantity,a16.title AS section_name 
FROM item a
LEFT JOIN item_quantity a1 ON a.id = a1.item
LEFT JOIN item_tag  a2 ON a.id = a2.item
LEFT JOIN tags a3 ON a2.tag = a3.id
LEFT JOIN item_picture_to_color a6 ON a.id = a6.item
LEFT JOIN item_picture a7 ON a.id = a7.item
LEFT JOIN picture a8 ON a6.picture = a8.id
LEFT JOIN itemtype_description a9 ON a.id = a2.item
LEFT JOIN item_in_treasury a10 ON a.id = a10.item
LEFT JOIN users_treasury a11 ON a10.treasury_id = a11.treasury_id
LEFT JOIN shops a12 ON a.shop_id = a12.shop_id
LEFT JOIN category a13 ON a.category = a13.category_id
LEFT JOIN countries a14 ON a.ship_from_country = a14.country_id
LEFT JOIN category_description a15 ON a.category = a15.category_id
LEFT JOIN shops_section a16 ON  a.shop_section = a16.section_id
WHERE 1
GROUP BY a.id DESC";
  echo $getTable;
    $table = mysql_query ($getTable) or die ("Sql error : " . mysql_error());
    $exportCSV = fopen("/home/web/public_html/testing/export.csv", 'w');

    // fetch a row and write the column names out to the file
    $row = mysql_fetch_assoc($table);
    $line = "";
    $comma = "";
    foreach($row as $name => $value) {
      $line .= $comma . '"' . str_replace('"', '""', $name) . '"';
      $comma = "\t";
    }
    $line .= "\n";
    fputs($exportCSV, $line);

    // remove the result pointer back to the start
    mysql_data_seek($table, 0);

    // and loop through the actual data
    while($row = mysql_fetch_assoc($table)) {
      $line = "";
      $comma = "";
      foreach($row as $value) {
        $line .= $comma . '"' . str_replace('"', '""', $value) . '"';
        $comma = "\t";
      }
      $line .= "\n";
      fputs($exportCSV, $line);
    }
    fclose($exportCSV);

header('Location: http://localhost/testing/export.csv');
exit;
       
  }
 exportCSV();
}
?>



<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>EXPORT CSV Form MySQL</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link href="/css/core.css" rel="stylesheet" type="text/css" />
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
<section id="wrapper">
	<form action="" method="post" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
                 <tr>EXPORT CSV FILE :</tr>
			<tr>
				<td><input type="submit" id="btn" class="fl_l" value="Submit" /></td>
			</tr>
		</table>

	</form>

</section>

</body>
</html>

