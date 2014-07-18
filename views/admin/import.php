<?php

$message = null;

$allowed_extensions = array('csv');


$upload_path = '/home/web/public_html/testing/Csvie/views/admin';

if (!empty($_FILES['file'])) {

	if ($_FILES['file']['error'] == 0) {

		// check extension
		$file = explode(".", $_FILES['file']['name']);
		$extension = array_pop($file);

		if (in_array($extension, $allowed_extensions)) {

			if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path.'/'.$_FILES['file']['name'])) {

				if (($handle = fopen($upload_path.'/'.$_FILES['file']['name'], "r")) !== false) {

				function ImportCSV2Array($filename)
				{
				    $row=0;
				    $col=0;
				    $handle = $filename;
				    if($handle)
				    {
					while(($row=fgetcsv($handle,4096))!==false)
					{
					    if(empty($fields))
					    {
						$fields=$row;
						continue;
					    }
					   
					    foreach($row as $k=>$value)
					    { 
						$results[$col][$fields[$k]] = $value;
					    }
					    $col++;
					    unset($row);
					}
					if(!feof($handle))
					{
					    echo"Error: unexpected fgets() failn";
					}
					fclose($handle);
				    }

				 return $results;
				}
                                 /*
	                          connection to database 
                               */
                              $con=mysql_connect("localhost","root","root");

				if (!$con){
				     die('Could not connect: ' . mysql_error());  
				 }
				/*
                                       select database on import.
				*/
				mysql_select_db("test1", $con);
                                    
		                $csvArray = ImportCSV2Array($handle);
				$shop_section= NULL;
                                $shop_section_text = NULL; 
                                $ship_from_country = NULL; 
                                $category = '218'; 
                                $item = '243';
				
                                foreach($csvArray as $row){
   var_dump($row);
                                $sql = mysql_query("SELECT category_id FROM `category_description` WHERE title= '".mysql_real_escape_string($row['Category_title'])."' LIMIT 1"); 
                                $row2 = mysql_fetch_array($sql);  
var_dump($row2);
                          die('dfd');      
				/*
                                     $sql_quty = mysql_query("INSERT INTO `item_quantity`(`item`, `variation`, `subvariation`, `quantity`) VALUES ( '""',[value-3],[value-4], ["product_quantity"])");
                                     $read  = fopen( $this->par2parOnlinePath.$this->newDirectory.'/'.$this->organizationFile, 'r' );
    $newRecordsToInsert  = fopen( $this->par2parOnlinePath.$this->newDirectory.'/importOrgRelationship.sql', 'w' );
							       "INSERT INTO civicrm_contact ( external_identifier, contact_type, sort_name, first_name, last_name, display_name  ) values ('{$ext_id}','Individual', '{$sort_name}', '{$first_name1}', '{$last_name1}', '{$display_name}') ON DUPLICATE KEY UPDATE external_identifier = '{$ext_id}', contact_type = 'Individual', sort_name = '{$sort_name}', first_name = '{$first_name1}', last_name = '{$last_name1}', display_name = '{$display_name}';\n";
				*/
                              //"SELECT @section_id := section_id FROM `shops_section` WHERE `title` = 'Earrings'");

                              $sql1 = "SELECT section_id FROM shops_section where title ='".mysql_real_escape_string($row['section_name'])."';";
	                      $sql1 .= ("SELECT category_id FROM category_description WHERE title ='".mysql_real_escape_string($row['Category_title'])."' LIMIT 1");  		                      
                              echo $sql1;  
                              $sql = mysql_query($sql);
                              $row = mysql_fetch_array($sql);
                              var_dump($row); 
                             die('dfdf');
                                    /*
          hi .. do't have any task for the day       
 $sql = "INSERT INTO `item` (`category`, `user`, `title`, `description`,`shop_id`,`shop_section`,`shop_section_text`,`price`,`procesing_unit`, `ship_from_country`, `created_at`, `hand_picked`, `active`, `store`, `fee_paid`,`approved`) VALUES ('$category','59','$row['product_name']','$row['product_description']','40','$shop_section','$shop_section_text','$row['price']', '$row['procesing_unit']', '$ship_from_country', 'NOW()','1', '1', '40','1', '1')";
              
                      $last_created_id =  mysql_insert_id();
		      */
                      //$sql .= "INSERT INTO `item_quantity`(`item`,`quantity`) VALUES ($last_created_id,$row['quantity'])";
                     // $statement = $db->prepare($sql);
		     // $statement->execute($value);
                       die('dfdf');             
                   }

                                    /*
					$keys = array();



					$out = array();

					$insert = array();

					$line = 1;

					while (($row = fgetcsv($handle, 0, ',', '"')) !== FALSE) {

            foreach($row as $key => $value) {
              if ($line === 1) {
                $keys[$key] = $value;
              } else {
                $out[$line][$key] = $value;
              }
            }
            $line++;
          }

          fclose($handle);

          if (!empty($keys) && !empty($out)) {

            $db = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
            $db->exec("SET CHARACTER SET utf8");

            foreach($out as $key => $value) {
                 var_dump($keys);
                 var_dump($value['2']);
		  sku	vsku	product_tags_name	product_name	product_description

                 $category= mysql_real_escape_string(trim());
		 $sku = $value['0'];
                 $vsku = $value['1'];      
                 $user = '59'; 
		 $title = mysql_real_escape_string($value['3']); 
		 $description = mysql_real_escape_string($value['4']); 
		 $shop_id = '40';
		 $shop_section = '81';
		 $shop_section_text = 'NULL';
		 $price = '20';
		 $procesing_unit = '2';
		 $ship_from_country = '107';
                 $created_at = "NOW()";
		 $hand_picked = '1'; 
		 $active = ;
                 $store = ;
                 
                  
		 $sql = "INSERT INTO `item` (`category`, `user`, `title`, `description`,`shop_id`,`shop_section`,`shop_section_text`,`price`,`procesing_unit`, `ship_from_country`, `created_at`, `hand_picked`, `active`, `store`, `fee_paid`,`approved`) VALUES ('$category','$user','$title','$description','$shop_id','$shop_section','$shop_section_text','$price', '$procesing_unit', '$ship_from_country', '$created_at','$hand_picked', '$active', '$store','1', '1')";
          
              $last_created_id =  mysql_insert_id();
   	      //printf("$this->row Last inserted record has id %d\n", mysql_insert_id() ."\n");

              /*
              "SELECT * FROM `shops` WHERE title = ;";
              $sql  = "INSERT INTO `books` (`";
              $sql .= implode("`, `", $keys);
              $sql .= "`) VALUES (";
              $sql .= implode(", ", array_fill(0, count($keys), "?"));
              $sql .= ")";
              */
	 /* 
		     echo $sql;

	die('sdsd');
		      $statement = $db->prepare($sql);
		      $statement->execute($value);
	

            }

            $message = '<span class="green">File has been uploaded successfully</span>';
          }
*/
				}

			}

		} else {
			$message = '<span class="red">Only .csv file format is allowed</span>';
		}

	} else {
		$message = '<span class="red">There was a problem with your file</span>';
	}

}

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Upload CSV to MySQL</title>
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
			<tr>
				<th><label for="file">Select file</label> <?php echo $message; ?></th>
			</tr>
			<tr>
				<td><input type="file" name="file" id="file" size="30" /></td>
			</tr>
			<tr>
				<td><input type="submit" id="btn" class="fl_l" value="Submit" /></td>
			</tr>
		</table>

	</form>

</section>

</body>
</html>

INSERT INTO `item` ( `category`, `user`, `title`, `description`, `shop_id`, `shop_section`, `shop_section_text`, `price`, `procesing_unit`, `ship_from_country`, `created_at`, `hand_picked`, `active`, `store`, `fee_paid`, `approved`) VALUES
( 218, 59, 'Fashionable Peacock Earrings', 'Fashionable Peacock Earrings – Online Shopping for Earrings.This beautiful Necklace is handcrafted in copper alloy with imitation Pearls and Polki of very fine quality and semi precious stones B2 These Necklace are coated with 18 kt white gold polish which give them a unique look . An absolutely stunning piece to own and are very light in weight.', 40, 81, 'Latest', 24.2957, '1 Day', 107, '2014-03-26 10:25:57', 1, 1, 'Upload_Model_Locale', 1, 1)
