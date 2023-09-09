<?php
require '../connSQL.php';
header('Content-Type:application/json');

$output = [
    'success' => false,
    'errorMessage' => '',
    'data' => $_GET
];


$sql = "DELETE FROM `product` WHERE product_id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET["pid"]]);

if ($stmt->rowCount() == 1) {
    $output['success'] = true;
} else {
    $output['errorMessage'] = "刪除失敗";
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>