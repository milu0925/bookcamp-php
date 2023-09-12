<?php
require '../connSQL.php';


$totalAmount = 0; //總金額
$book = []; //有購買的書編號
$price = []; //有購買的書價格
$count = []; //有購買的書數量



//訂單資料寫入
if (isset($_SESSION['id'])) {

    $cart = $_POST['cartid'];

    // 抓到mySQL-total
    $totalsql = "SELECT SUM(book_price*book_count) as total,book_id,book_price,book_count FROM `cart` WHERE cart_id = ?";
    $total = $pdo->prepare($totalsql);
    try {

        for ($i =0 ; $i < count($cart) ; $i++ ){
            $total->execute([$cart[$i]]);
            $row = $total->fetch(PDO::FETCH_ASSOC);
            $totalAmount += $row['total'];
            array_push($book, $row['book_id']);
            array_push($price, $row['book_price']);
            array_push($count, $row['book_count']);
        };

    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    };


    //訂單資料寫入
    $ordersql = "INSERT INTO `order` (`consignee`,`consignee_phone`,`consignee_address`,`client_id`,`delivery_id`,`receipt_id`,`pay_id`,`order_status_id`,`total`) VALUES(?,?,?,?,?,?,?,?)";
    $order = $pdo->prepare($ordersql);
    $order->execute([$_SESSION['name'], $_SESSION['phone'], $_SESSION['address'], $_SESSION['id'], 1, 1, 1,3,$totalAmount]);

    // 訂單明細資料寫入
    $detailsql = "INSERT INTO `order_detail` (`order_id`,`book_id`,`book_price`,`book_count`) VALUES(?,?,?,?)";
    $orderdetail = $pdo->prepare($detailsql);

    $orderIDs = $_POST['oid'];

    for ($i = 0; $i < count($cart) ; $i++) {
        $orderdetail->execute([$orderIDs, $book[$i], $price[$i], $count[$i]]);
    };


    //  刪除購物車已結帳內容
    $deletesql = "DELETE FROM `cart` WHERE cart_id=?";
    $delete = $pdo->prepare($deletesql);
    try {
        for ($i = 0; $i < count($cart); $i++) {
            $delete->execute([$cart[$i]]);
        }
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }

}
header("location: /phpProject/docs/page/product.php");
?>