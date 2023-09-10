<?php
require '../connSQL.php';

$output = [
    'success' => false,
    'errorMessage' => "",
    'postData' => $_POST
];

$cid = $_POST['client_id'];
$pid = $_POST['book_id'];
$price = $_POST['book_price'];
$count = $_POST['book_count'];

// 檢查購物車存在嗎
$checkSql = "SELECT * FROM `cart` WHERE book_id = :bidx AND client_id = :cidx";
$checkStmt = $pdo->prepare($checkSql);
$checkStmt->bindParam(":bidx", $pid);
$checkStmt->bindParam(":cidx", $cid);
$checkStmt->execute();
$rowCount = $checkStmt->fetch();


if ($rowCount) {
    //  取得原來在購物車內數量
    $currentCount = $rowCount['book_count'];

    // 上面抓到的數量+這次更新的
    $updatedCount = $currentCount + $count;
    
    $sql = "UPDATE `cart` SET `book_count`= :count  WHERE book_id = :id AND client_id = :cid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $pid);
    $stmt->bindParam(":count", $updatedCount);
    $stmt->bindParam(":cid", $cid);
    $stmt->execute();

} else {
    $sql = "INSERT INTO `cart`(client_id,`book_id`,`book_price`,`book_count`)
    VALUES(:cid ,:pid ,:price ,:count )";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":cid", $cid);
    $stmt->bindParam(":pid", $pid);
    $stmt->bindParam(":price", $price);
    $stmt->bindParam(":count", $count);
    $stmt->execute();
}

if ($stmt->rowCount() == 1) {
    $output['success'] = true;

} else {
    $output['errorMessage'] = "新增失敗";

}


echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>