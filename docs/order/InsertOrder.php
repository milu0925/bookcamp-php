<?php
require './connSQL.php';

//訂單資料寫入
if (isset($_SESSION['id'])) {

    // 抓到mySQL-total
    $totalsql = "SELECT SUM(product_price*product_count) as total FROM `cart` WHERE client_id = ?";
    $total = $pdo->prepare($totalsql);
    try {
        $total->execute([$_SESSION['id']]);
        $rowtotal = $total->fetch();

    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }
    ;

    //訂單資料寫入
    $ordersql = "INSERT INTO `bookorder`(`consignee`,`consignee_phone`,`consignee_address`,`client_id`,`delivery_id`,`receipt_id`,`pay_id`,`total`) VALUES(?,?,?,?,?,?,?,?)
";
    $order = $pdo->prepare($ordersql);
    $order->execute([$_POST['consignee'], $_POST['consigneephone'], $_POST['consigneeaddress'], $_POST['clientid'], $_POST['delivery'], $_POST['receipt'], $_POST['pay'], $rowtotal['total'],]);


    // 明細資料寫入
    $detailsql = "INSERT INTO `bookorder_detail`(`order_id`,`product_id`,`product_price`,`product_count`,`client_id`) VALUES(?,?,?,?,?)";
    $orderdetail = $pdo->prepare($detailsql);

    $orderIDs = $_POST['oid'];
    $orderIDs1 = (int)$orderIDs[0] ;
    $productIDs = $_POST['pid'];
    $productPrices = $_POST['pprice'];
    $productCounts = $_POST['pcount'];
    $clientID = $_SESSION['id'];

    for ($i = 0; $i < count($orderIDs); $i++) {
        $orderdetail->execute([$orderIDs[$i], $productIDs[$i], $productPrices[$i], $productCounts[$i], $clientID]);
    }
    ;

    // 運費計算
    $deliveryfee = "UPDATE bookorder SET delivery_fee = 0 WHERE total > 3000 ";
    $fee = $pdo->prepare($deliveryfee);
    try {
        $fee->execute();
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }
    ;
    
    // TOTAL運費計算
    $totall = "UPDATE bookorder SET total = total+60 WHERE total < 3000 AND order_id = ?";
    $total = $pdo->prepare($totall);
    try {
        
        $total->execute([$orderIDs1]);
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }
    ;

    // 刪除購物車已結帳內容
    $deletesql = "DELETE FROM `cart` WHERE product_id=?";
    $delete = $pdo->prepare($deletesql);
    try {
        for ($i = 0; $i < count($orderIDs); $i++) {
            $delete->execute([$productIDs[$i]]);
        }
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }
}
header("location:./product/product.php");
?>