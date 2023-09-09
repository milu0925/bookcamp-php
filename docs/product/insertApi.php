<?php
require __DIR__ . "../connSQL.php";
header('Content-Type:application/json');
$output = [
    'success' => false,
    'errorMessage' => "",
    'postData' => $_POST
];

$sql = "INSERT INTO `product`(`product_name`,`product_price`,`product_quantity`,`product_comment`)
    VALUES(?,?,?,?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['product_name'],
    $_POST['product_price'],
    $_POST['product_quantity'],
    $_POST['product_comment'],
]);

if ($stmt->rowCount() == 1) {
    $output['success'] = true;

} else {
    $output['errorMessage'] = "新增失敗";

}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>