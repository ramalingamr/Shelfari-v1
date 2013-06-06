<?php
$dsn = "pgsql:"
    . "host=ec2-23-21-130-168.compute-1.amazonaws.com;"
    . "dbname=d3p8gu2ctu7h4n;"
    . "user=gictefoytslqtx;"
    . "port=5432;"
    . "sslmode=require;"
    . "password=YhDlOuqymvDAh9HPzBZhgO_jH7";
$db = new PDO($dsn);
$query = "SELECT * from book_entity;";
$result = $db->query($query);
$rows = $result->fetchAll(PDO::FETCH_ASSOC);
$result->closeCursor();
echo json_encode($rows);
?>
