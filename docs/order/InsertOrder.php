<?php
require '../connSQL.php';

//訂單資料寫入
if (isset($_SESSION['id'])) {

    // 抓到mySQL-total
    $totalsql = "SELECT SUM(book_price*book_count) as total FROM `cart` WHERE client_id = ?";
    $total = $pdo->prepare($totalsql);
    try {
        $total->execute([$_SESSION['id']]);
        $rowtotal = $total->fetch();

    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }
    ;

    //訂單資料寫入
    $ordersql = "INSERT INTO ``order``(`consignee`,`consignee_phone`,`consignee_address`,`client_id`,`delivery_id`,`receipt_id`,`pay_id`,`total`) VALUES(?,?,?,?,?,?,?,?)
";
    $order = $pdo->prepare($ordersql);
    $order->execute([$_POST['consignee'], $_POST['consigneephone'], $_POST['consigneeaddress'], $_POST['clientid'], $_POST['delivery'], $_POST['receipt'], $_POST['pay'], $rowtotal['total'],]);


    // 明細資料寫入
    $detailsql = "INSERT INTO ``order`_detail`(`order_id`,`book_id`,`book_price`,`book_count`,`client_id`) VALUES(?,?,?,?,?)";
    $orderdetail = $pdo->prepare($detailsql);

    $orderIDs = $_POST['oid'];
    $orderIDs1 = (int)$orderIDs[0] ;
    $bookIDs = $_POST['pid'];
    $bookPrices = $_POST['pprice'];
    $bookCounts = $_POST['pcount'];
    $clientID = $_SESSION['id'];

    for ($i = 0; $i < count($orderIDs); $i++) {
        $orderdetail->execute([$orderIDs[$i], $bookIDs[$i], $bookPrices[$i], $bookCounts[$i], $clientID]);
    }
    ;

    // 運費計算
    $deliveryfee = "UPDATE `order` SET delivery_fee = 0 WHERE total > 3000 ";
    $fee = $pdo->prepare($deliveryfee);
    try {
        $fee->execute();
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }
    ;
    
    // TOTAL運費計算
    $totall = "UPDATE `order` SET total = total+60 WHERE total < 3000 AND order_id = ?";
    $total = $pdo->prepare($totall);
    try {
        
        $total->execute([$orderIDs1]);
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }
    ;

    // 刪除購物車已結帳內容
    $deletesql = "DELETE FROM `cart` WHERE book_id=?";
    $delete = $pdo->prepare($deletesql);
    try {
        for ($i = 0; $i < count($orderIDs); $i++) {
            $delete->execute([$bookIDs[$i]]);
        }
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }
}
header("location:./book/book.php");
?>