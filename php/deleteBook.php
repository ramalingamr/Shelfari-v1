<?php
$dsn = "pgsql:"
    . "host=ec2-23-21-129-229.compute-1.amazonaws.com;"
    . "dbname=dat4hqf42ko5a9;"
    . "user=lfltlstiafqcgs;"
    . "port=5432;"
    . "sslmode=require;"
    . "password=lil1pqUyPo2rkAFptcPTdolpDl";
$db = new PDO($dsn);
$bookName=$_GET["bookname"];
$query = "SELECT * from book_entity where bookname like'".$bookName."';";
$result = $db->query($query);
$rows = $result->fetch(PDO::FETCH_ASSOC);
$result->closeCursor();
echo json_encode($rows);
?>
