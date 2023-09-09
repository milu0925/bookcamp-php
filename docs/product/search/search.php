<?php
require '../conn/connSQL.php';

header('Content-Type:application/json');

$sql = "SELECT `product_name` FROM `product` WHERE `product_name` LIKE ? LIMIT 10";

$stmt = $pdo->prepare($sql);
$debug =isset($_GET['term']) ? $_GET['term'] : ''; //為了讓這網頁不要跳錯誤
$stmt->execute(['%' . $debug . '%']);
$row = $stmt->fetchAll();

foreach ($row as $value) {
    $array[] = $value['product_name'];
};

echo json_encode($array, JSON_UNESCAPED_UNICODE);
?>