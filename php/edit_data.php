<?php
	include('db_conn.php');
	// Variablen aus POST übertragen
    $id = $_POST['pk'];
    $field = $_POST['name'];
    $value = $_POST['value'];
	$table = $_GET['t'];
	
    if(!empty($value)) {
        
		  $sql_edit = 'update '.$table.' set '.mysql_escape_string($field).'="'.mysql_escape_string($value).'" where ID = "'.mysql_escape_string($id).'"';
          $result = mysqli_query($sql_edit,$db_conn);
        
        //here, for debug reason we just return dump of $_POST, you will see result in browser console
        print_r($_POST);
    } else {
        /* 
        In case of incorrect value or error you should return HTTP status != 200. 
        Response body will be shown as error message in editable form.
        */
        header('HTTP/1.0 400 Bad Request', true, 400);
        echo "This field is required!";
    }
?>