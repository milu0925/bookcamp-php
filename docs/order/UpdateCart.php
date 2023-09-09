<?php
require "./connSQL.php";

$id = $_POST['pid'];
$count = $_POST['pcount'];

//每次點擊時都刷新購物車的數量進資料庫
$updateCart = "UPDATE cart SET product_count = ?  WHERE product_id = ?";
$sqlUpdate = $pdo->prepare($updateCart);

try {
    $sqlUpdate->execute([$count, $id]);

} catch (PDOException $e) {
    echo "Error updating cart item: " . $e->getMessage();
}


?>