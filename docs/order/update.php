<?php
require "./connSQL.php";

// 更新訂單總金額
// if (isset($_GET["order_id"]) && isset($_POST["total"])) {
//     $sqlGetTotal = "UPDATE bookorder o
//     JOIN coupon co ON o.coupon_id = co.coupon_id
//     JOIN 
//     (SELECT detail.order_id , detail.delivery_fee, 
//        SUM(detail.product_price * detail.product_count) AS total
//        FROM bookorder_detail detail
//        GROUP BY detail.order_id
//     ) sub ON o.order_id = sub.order_id
//     SET o.total = CASE
//         WHEN co.coupon_id IN (1, 2) THEN (sub.total * co.coupon_value) + sub.delivery_fee
//         WHEN co.coupon_id = 3 THEN (sub.total - co.coupon_value) + sub.delivery_fee
//         ELSE o.total = :total
//         END
//     WHERE co.coupon_id IN (1, 2, 3) and order_id = :order_id";
//     $id = $_GET["order_id"];
//     $total = $_POST["total"];

//     $sqltotal = $pdo->prepare($sqlGetTotal);
//     try {
//         $sqltotal->bindParam(':total', $total);
//         $sqltotal->bindParam(':order_id', $id);
//         $sqltotal->execute();

//         echo "更新總金額成功";
//         header("location: order.php");
//     } catch (PDOException $e) {
//         echo $e->getMessage();
//     }
// } 


// 變更訂單狀態
if (isset($_POST["batch_id"]) && isset($_POST["batch_status"])) {

    $batch_status = array($_POST["batch_status"]);
    $batch_id = $_POST["batch_id"];

    var_dump($batch_id); //表示多個選擇
    $batch_params = str_repeat('?,', count($batch_id) - 1) . '?';
    $sql = "UPDATE bookorder SET order_status_id = ? WHERE order_id IN ($batch_params)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute(array_merge($batch_status, $batch_id)); //合併陣列(這邊用array_unshift會失敗)
        $rows = $stmt->fetchAll();
        echo "更改狀態成功。";
        header("location: order.php");
    } catch (PDOException $e) {
        echo $e->getMessage();
        echo "something went wrong";
    }
} 



?>