<?php
require '../connSQL.php';

header('Content-Type:application/json');

$sql = "SELECT * FROM `product`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll();

echo json_encode($rows, JSON_UNESCAPED_UNICODE);

?>