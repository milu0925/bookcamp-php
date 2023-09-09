<?php
require '../connSQL.php';
header('Content-Type:application/json');

$output = [
    'success' => false,
    'errorMessage' => '',
    'data' => $_POST
];

$sql = "UPDATE `product` SET `product_name`=? ,`product_price`=? ,`product_quantity`=? ,`product_comment`=? WHERE product_id=?;";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['product_name'],
    $_POST['product_price'],
    $_POST['product_quantity'],
    $_POST['product_comment'],
    $_POST['product_id']
]);

//rowCount() 不管CRUD 都會回傳異動變數
//如果修改多筆rowCount() >= 1
if ($stmt->rowCount() == 1) {
    $output['success'] = true;

} else {
    $output['errorMessage'] = "刪除失敗";
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>