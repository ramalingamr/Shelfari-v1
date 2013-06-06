<?php
$dsn = "pgsql:"
    . "host=ec2-23-21-129-229.compute-1.amazonaws.com;"
    . "dbname=dat4hqf42ko5a9;"
    . "user=lfltlstiafqcgs;"
    . "port=5432;"
    . "sslmode=require;"
    . "password=lil1pqUyPo2rkAFptcPTdolpDl";
$db = new PDO($dsn);
// Hardcoded user name
$userName='userX';
$bookName=$_POST["bookname"];
$authorName=$_POST["authorname"];
$status=$_POST["status"];
$query="INSERT INTO BOOK_ENTITY VALUES('$userName','$bookName','$authorName','$status');";
$result = $db->query($query);
echo json_encode($result);
?>
