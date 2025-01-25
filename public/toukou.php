<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

$insert_sth = $dbh->prepare("INSERT INTO TOUKOU (text) VALUES (:text)");
$insert_sth->execute([
    ':text' => 'hello world!!!!!!!!!'
]);
print('insertできました');
