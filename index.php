<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<pre>
<?php 

require_once('client/assqlite.class.php');

/*CONNECT*/
$db = new assqlite("http://example.com/path_to_folder/", "example.db");

/*SET BUSY TIMEOUT*/
$db->busyTimeout(300);

/*EXECUTE QUERY*/
$db->exec(" DELETE FROM `users` WHERE id > 100 ");


/*PERFORM QUERY*/
$results = $db->query("SELECT * FROM users LIMIT 3");

/*FETCH ROW AS OBJECT*/
while($row = $results->fetchObject()){
	print_r($row->{'id'});
}

/*FETCH ROWS COUNT*/
$rows = $results->num_rows();

/*RESET ARRAY WITH RESULTS TO POSITION, CANT BE GREATER THAN NUM_ROWS() RETURNS */
$results->data_seek(0);


while($row = $results->fetchArray()){
	print_r($row['id']);
}


?>
</pre>
</body>
</html>