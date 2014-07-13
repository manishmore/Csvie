
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
 
File to import:<br />
 
<input size='30' type='file' name='filename'>
 
<input type="submit" name="submit" value="Upload"></form>
 


<?php

    $host = 'localhost'; // MYSQL database host adress
    $user = 'root'; // Mysql Datbase user
    $pass = 'root'; // Mysql Datbase password
    $con = mysql_connect($host, $user, $pass);
    
    if (!$con) {
      die('Could not connectss: ' . mysql_error());
    }
    $db = 'upload';

    mysql_select_db("$db", $con);

//Assuming that, your connect.php file contains the database related information, ie. hostname, username etc.
 
   //Upload File
    if (isset($_POST['submit'])) {
    if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
       
     
    //Import uploaded file to Database
    $row = 1;
    $handle = fopen($_FILES['filename']['tmp_name'], "r");
 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
   
 
 
              //Update Database Values
 
        $import="insert into test (name, data) VALUES('".mysql_real_escape_string($data[0])."', '".mysql_real_escape_string($data[1])."')";
        mysql_query($import) or die(mysql_error());
     
    }
 
 
    fclose($handle);
     }
}
    ?>
 
