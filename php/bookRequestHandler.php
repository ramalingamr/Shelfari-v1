<?php
$dsn = "pgsql:"
    . "host=ec2-23-21-129-229.compute-1.amazonaws.com;"
    . "dbname=dat4hqf42ko5a9;"
    . "user=lfltlstiafqcgs;"
    . "port=5432;"
    . "sslmode=require;"
    . "password=lil1pqUyPo2rkAFptcPTdolpDl";
$db = new PDO($dsn);
$request_method = $_SERVER['REQUEST_METHOD'];
$bookname = $authorname = $status = $mode = $oldbookname = '';
switch($request_method){
case "GET" : 
	// Handle getAll/search requests
	if(isset($_GET["bookname"]))
	$bookname = $_GET["bookname"];
	if(isset($_GET["authorname"]))	
	$authorname = $_GET["authorname"];
	if(isset($_GET["status"]))	
	$status = $_GET["status"];
	$query = "SELECT * from book_entity where bookname like '%$bookname%' AND authorname like '%$authorname%' AND status like '%$status%'";
	$result = $db->query($query);
	$rows = $result->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($rows);
	break;
case "PUT" :
	// Handle update requests
	$requestData = file_get_contents('php://input');
	$json = json_decode($requestData,true);
	$bookname = $json["bookname"]; 
	$authorname = $json["authorname"];
	$status = $json["status"];
	$oldbookname = $json["oldbookname"];
	$query = "UPDATE book_entity ".
		"SET bookname = '" . $bookname . 
		"', authorname = '" . $authorname .
		"', status = '" . $status .
		"' where bookname = '" . $oldbookname . "';";  
	$result = $db->query($query);
	echo json_encode($result);
	break;
case "POST" :
	// Handle insert and delete requests
	$requestData = file_get_contents('php://input');
	$json = json_decode($requestData,true);
	$username = 'userX';
	$bookname = $json["bookname"]; 
	$authorname = $json["authorname"];
	$status = $json["status"];
	if(isset($json["remove"])){
		$query = "DELETE from book_entity where bookname LIKE '$bookname';";  
		$result = $db->query($query);
		echo json_encode($result);	
	}else{
		// Generate a 10 character random string for id
		$randomStr = '';
		for($i=0;$i<10;$i++){
			$randomChar = chr(rand(0,26)+97);
			$randomStr = $randomStr.$randomChar;
		}
		$query="INSERT INTO BOOK_ENTITY VALUES('$bookname','$authorname','$status','$randomStr');";
		$result = $db->query($query);
		echo json_encode($randomStr);
	}	
}

?>
