<?php 

$db = new sqlite3($_POST['db']);

if(isset($_POST['busyTimeout']))
{
	$db->busyTimeout(intval($_POST['busyTimeout']));
}

$method = $_POST['method'];

if($method == 'exec')
{
	$db->exec($_POST['queryString']);
}

$result = array();

if($method == 'query')
{
	$q = $db->query($_POST['queryString']);
	while($row = $q->fetchArray())
	{
		$result[] = $row;
	}
	
	echo json_encode($result);
}

$db->close();