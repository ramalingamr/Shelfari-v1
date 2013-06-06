<?php
$dsn = "pgsql:"
    . "host=ec2-23-21-130-168.compute-1.amazonaws.com;"
    . "dbname=d3p8gu2ctu7h4n;"
    . "user=gictefoytslqtx;"
    . "port=5432;"
    . "sslmode=require;"
    . "password=YhDlOuqymvDAh9HPzBZhgO_jH7";
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
