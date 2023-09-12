<?php
require "../connSQL.php";

$id = $_POST['pid'];
$count = $_POST['pcount'];

//每次點擊時都刷新購物車的數量進資料庫
$updateCart = "UPDATE cart SET book_count = ?  WHERE book_id = ? AND client_id = ?";
$sqlUpdate = $pdo->prepare($updateCart);

try {
    $sqlUpdate->execute([$count, $id, $_SESSION['id']]);

} catch (PDOException $e) {
    echo "Error updating cart item: " . $e->getMessage();
}


?>