<?php
require '../connSQL.php';

$output = [
    'success' => false,
    'errorMessage' => '',
    'data' => $_POST
];

$cid = $_POST['client_id'];
$pid = $_POST['product_id'];
$price = $_POST['product_price'];
$count = $_POST['product_count'];
$name = $_POST['product_name'];

// 檢查購物車product_id=某個ID的產品
$checkSql = "SELECT * FROM `cart` WHERE product_id = :idx";
$checkStmt = $pdo->prepare($checkSql);
$checkStmt->bindParam(":idx", $pid);
$checkStmt->execute();
$rowCount = $checkStmt->fetch();


if ($rowCount) {
    //  取得原來在購物車內這個ID的數量
    $currentCount = $rowCount['product_count'];

    // 上面抓到的原本數量+這次FORM上回傳的
    $updatedCount = $currentCount + $count;
    
    $sql = "UPDATE `cart` SET `product_count`= :count  WHERE product_id = :id";
    $stmt = $pdo->prepare($sql);
    $date = date('Y-m-d h:i:s');

    $stmt->bindParam(":id", $pid);
    $stmt->bindParam(":count", $updatedCount);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $output['success'] = true;
    } else {
        $output['errorMessage'] = "更新失敗";
    }

} else {
    $sql = "INSERT INTO `cart`(client_id,`product_id`,`product_price`,`product_name`,`product_count`)
    VALUES(:cid,:pid, :price , :name1 ,:count )";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":cid", $cid);
    $stmt->bindParam(":pid", $pid);
    $stmt->bindParam(":name1", $name);
    $stmt->bindParam(":price", $price);
    $stmt->bindParam(":count", $count);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $output['success'] = true;


    } else {
        $output['errorMessage'] = "新增失敗";
    }
}




echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>