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
          $con = mysql_connect("localhost","root","root");

          if (!$con){
            die('Could not connect: ' . mysql_error());
          }
          /*
              select database on import.
          */
          mysql_select_db("test1", $con);
                                    
          $csvArray = ImportCSV2Array($handle);
                
      foreach($csvArray as $row){
        
        $shop_section = NULL;
        $shop_section_text = NULL;
        $ship_from_country = NULL;
        $category = '218';
        $item = '243';
	    $user = '59';
	    $product_name = mysql_real_escape_string($row['product_name']);
	    $pro_description = mysql_real_escape_string($row['product_description']);
	    $shop_id = '40';
	    $shop_section = '81';
	    $shop_section_text = 'NULL';
	    $price = $row['product_price'];
	    $procesing_unit = '2';
	    $ship_from_country = '107';
	    $created_at = "NOW()";
	    $hand_picked = '1';
	    $active = '';
	    $store = '' ;
        $procesing_unit = mysql_real_escape_string($row['procesing_unit']);
        $pro_quantity = $row['product_quantity'];
        $picture_url = $row['product_pic_name'];
        $picture_name = '';
        //$sql = mysql_query("SELECT category_id FROM `category_description` WHERE title= '".mysql_real_escape_string($row['Category_title'])."' LIMIT 1");
        //$row2 = mysql_fetch_array($sql);
           
           $shop_section = mysql_query("SELECT section_id,title FROM shops_section where title ='".mysql_real_escape_string($row['section_name'])."';");
    
           $shop_section_name = mysql_fetch_array($shop_section) or die(mysql_error());
           $shop_section = $shop_section_name['section_id'];
           $shop_section_text = $shop_section_name['title'];
             if(empty($shop_section)){
		    $section_name = mysql_real_escape_string($row['section_name']);
		    $shops_section = mysql_query("INSERT INTO `shops_section`( `shop_id`, `title`, `sort_order`, `meta_keywords`) VALUES ('41','$section_name','0','$section_name');");
		    $shop_section = mysql_insert_id();
		    $section_name = mysql_query("SELECT title FROM shops_section where section_id ='$shop_section';");
		    $section_name = mysql_fetch_array($shop_section) or die(mysql_error());
		    $section_name = $section_name['title'];
	   }
             /*
               category find it out or create new one from csv file.
              first sql :-
             SELECT a1.category_id  
             FROM category_description a  
             INNER JOIN category a1 ON a.category_id = a1.category_id
             WHERE a.title = 'Eyewear' AND a1.parent_id IS NULL;               
			second sql :-
			SELECT a.* 
			FROM category_description a  
			INNER JOIN category a1 ON a.category_id = a1.category_id
			WHERE a.title = 'Eyewear' AND a1.parent_id = 1
			third sql :-

			1=>2=>3
			SELECT a.title AS first_title,a3.category_id,a3.title AS second_title,a4.category_id,a6.category_id,a5.title
			FROM category_description a  
			LEFT JOIN category a1 ON a.category_id = a1.category_id 
			LEFT JOIN category_description a3 ON a1.parent_id = a3.category_id 
			LEFT JOIN category a4 ON a3.category_id = a4.category_id
			LEFT JOIN category_description a5 ON a4.parent_id = a5.category_id 
			LEFT JOIN category a6 ON a5.category_id = a6.category_id
			WHERE a.`title`= 'Cotton' AND a3.title = 'Blanket' AND a6.parent_id IS NULL
			GROUP BY a1.category_id;
			1=>2
			SELECT a.title AS first_title,a1.category_id AS 2and_id , a3.title AS second_title,a4.category_id AS 1st_id
			FROM category_description a  
			LEFT JOIN category a1 ON a.category_id = a1.category_id
			LEFT JOIN category_description a3 ON a1.parent_id = a3.category_id 
			LEFT JOIN category a4 ON a3.category_id = a4.category_id
			WHERE a.`title`= 'Wristlet'  AND a3.`title`= 'Bags and Purses' AND a4.parent_id IS NULL
			group by a1.category_id 

			1=>2=>3=>4
			SELECT a.category_id AS 4th_id, a4.category_id AS 3rd_id ,a6.category_id AS 2and_id, a8.category_id AS 1st_id 
			FROM category_description a  
			LEFT JOIN category a1 ON a.category_id = a1.category_id 
			LEFT JOIN category_description a3 ON a1.parent_id = a3.category_id 
			LEFT JOIN category a4 ON a3.category_id = a4.category_id
			LEFT JOIN category_description a5 ON a4.parent_id = a5.category_id 
			LEFT JOIN category a6 ON a5.category_id = a6.category_id
			LEFT JOIN category_description a7 ON a6.parent_id = a7.category_id 
			LEFT JOIN category a8 ON a7.category_id = a8.category_id
			WHERE a.`title`= 'Cotton' AND a3.title = 'Blanket' AND a6.parent_id IS NULL
			GROUP BY a1.category_id;
            */
               $exload = explode('/' ,$row['Category_title']);
               $count = count($exload);
              //three places category sql.
              if($count = '3'){
             $pro_category = mysql_query("SELECT a.category_id AS 3st_id
		FROM category_description a  
		LEFT JOIN category a1 ON a.category_id = a1.category_id
		LEFT JOIN category_description a3 ON a1.parent_id = a3.category_id 
		LEFT JOIN category a4 ON a3.category_id = a4.category_id
		LEFT JOIN category_description a5 ON a4.parent_id = a5.category_id 
		LEFT JOIN category a6 ON a5.category_id = a6.category_id
		WHERE a.title = '".$exload[2]."' AND a3.title = '".$exload[1]."' AND a6.parent_id IS NULL 
		group by a1.category_id");
             }else if($count = '2'){
              $pro_category = mysql_query("SELECT a1.category_id AS 2and_id 
				FROM category_description a  
				LEFT JOIN category a1 ON a.category_id = a1.category_id
				LEFT JOIN category_description a3 ON a1.parent_id = a3.category_id 
				LEFT JOIN category a4 ON a3.category_id = a4.category_id
				WHERE a.`title`= '".$exload[1]."'  AND a3.`title`= '".$exload[0]."' AND a4.parent_id IS NULL
				group by a1.category_id");
               }else if($count = '1'){
		         $pro_category = mysql_query("SELECT a1.category_id
		                         FROM category_description a  
		                         INNER JOIN category a1 ON a.category_id = a1.category_id
		                         WHERE a.title = '".$exload[0]."' AND a1.parent_id IS NULL");
               }else if($count=4){
				$pro_category=	mysql_query("SELECT a.category_id AS 1st_id
									FROM category_description a
									LEFT JOIN category a1 ON a.category_id = a1.category_id
									LEFT JOIN category_description a3 ON a1.parent_id = a3.category_id
									LEFT JOIN category a4 ON a3.category_id = a4.category_id
									LEFT JOIN category_description a5 ON a4.parent_id = a5.category_id
									LEFT JOIN category a6 ON a5.category_id = a6.category_id
									LEFT JOIN category_description a7 ON a6.parent_id = a7.category_id
									LEFT JOIN category a8 ON a7.category_id = a8.category_id
									WHERE a.`title`= '".$exload[3]."' AND a3.title = '".$exload[2]."' AND a8.parent_id IS NULL
									GROUP BY a1.category_id;");
			   }
            $pro_category = mysql_fetch_array($pro_category) or die(mysql_error());
            var_dump($pro_category[0]);
            $category_id = $pro_category[0];
             /*
              insert new product in item table
             */
           $sql = "INSERT INTO `item` (`category`, `user`, `title`,`description`,`shop_id`,`shop_section`,`shop_section_text`,`price`,`procesing_unit`, `ship_from_country`, `created_at`, `hand_picked`, `active`, `store`, `fee_paid`,`approved`) VALUES ('$category_id','59','$product_name','$pro_description','40','$shop_section','$shop_section_text','$price', '$procesing_unit', '$ship_from_country', 'NOW()','1', '1', '40','1', '1')";

           $sql = mysql_query($sql);

           $category = mysql_fetch_array($sql) or die(mysql_error());
           $last_item = mysql_insert_id();
		/*
		products quantity
		*/
           $quantity = mysql_query("INSERT INTO `item_quantity`(`item`,`quantity`) VALUES ('$last_item','$pro_quantity')");

            //$itemtype_des = mysql_query("INSERT INTO itemtype_description (`type`, `language`, `title`) VALUES ([value-2],[value-3],[value-4]);";

	   /*
	    product image insert start here
	   */
	   $picture = mysql_query("INSERT INTO `picture`(`picture`, `created_at`, `store`) VALUES ('$picture_url','NOW()','Upload_Model_Locale');");
               
           $picture_id = mysql_insert_id();
           /*
            will save $picture_id and item means products ID in item picture table.
           */
           $item_picture = mysql_query("INSERT INTO item_picture (`item`, `picture`, `sort_order`) VALUES ('$last_item','$picture_id','0');");
				 /*
				  * base path of web site and than picture path for product image. 
				  * */	
    	   $base_url = "http://localhost/testing/Csvie/".$picture_url;

           $picture_temp = mysql_query("INSERT INTO `picture_temp` (`id`,`size` ,`width`,`height`,`image`,`original`,`mime`) VALUES (
'$picture_id', '_D', '250', '250', '$base_url', '$base_url', 'image/jpeg');");
           $picture_temp = mysql_query("INSERT INTO `picture_temp` (`id`,`size` ,`width`,`height`,`image`,`original`,`mime`) VALUES (
'$picture_id', '_A', '250', '250', '$base_url', '$base_url', 'image/jpeg');");
            $picture_temp = mysql_query("INSERT INTO `picture_temp` (`id`,`size` ,`width`,`height`,`image`,`original`,`mime`) VALUES (
'$picture_id', '_B', '250', '250', '$base_url', '$base_url', 'image/jpeg');");
             $picture_temp = mysql_query("INSERT INTO `picture_temp` (`id`,`size` ,`width`,`height`,`image`,`original`,`mime`) VALUES (
'$picture_id', '_C', '250', '250', '$base_url', '$base_url', 'image/jpeg');");

          /*
          * product tag here
          * */
	/*
	//product tags here
	$product_tags = '';
	if($product_tag){
	 $tags = mysql_query("INSERT INTO `tags`( `name`) VALUES ([value-2]);");
	 $item_tags = mysql_query("INSERT INTO `item_tag`(`id`, `item`, `tag`) VALUES ([value-1],[value-2],[value-3]);");
	}
	*/
          }
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
