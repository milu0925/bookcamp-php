<?php

// 分頁
$page = isset($_GET["page"]) ? $_GET["page"] : 1; //代表第幾頁
$showpage = 10; //每頁顯示的數量
$startpage = ($page - 1) * $showpage;

$ordersql1 = "SELECT count(*) as totalpage FROM `order`";
$stmt = $pdo->prepare($ordersql1);
$stmt->execute();
$totalrec = $stmt->fetchColumn(); //訂單總筆數
$totalpage = ceil($totalrec / $showpage); //計算分頁

//依照判斷去印出顯示的資料
//假如有抓到日期選擇的form
if (isset($_GET['createDate'])) {
    $date = $_GET['createDate'];
    $datesearch = "SELECT * FROM `order` WHERE DATE(order_create_date)=? ORDER BY order_create_date LIMIT $startpage, $showpage ;";
    $sqldateSearch = $pdo->prepare($datesearch);
    try {
        $sqldateSearch->execute([$date]);
        $row = $sqldateSearch->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    // 抓到設定最大值和最小值
} elseif (isset($_GET['minmoney']) && isset($_GET['maxmoney'])) {

    $min = $_GET['minmoney'];
    $max = $_GET['maxmoney'];

    if ($max == "") {
        $max = 999999999999;
    }
    $money = "SELECT * FROM `order` WHERE total BETWEEN :min AND :max ORDER BY total LIMIT $startpage, $showpage ;";
    $sqlmoney = $pdo->prepare($money);

    try {
        $sqlmoney->bindParam(':min', $min);
        $sqlmoney->bindParam(':max', $max);
        $sqlmoney->execute();
        $row = $sqlmoney->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    // 訂單編號排序
} elseif (isset($_GET['idSort'])) {
    $idSort = $_GET['idSort'];
    switch ($idSort) {
        case "idDESC":
            $sqlsort = "SELECT * FROM `order` ORDER BY order_id DESC LIMIT $startpage, $showpage ;";
            break;
        case "idASC":
            $sqlsort = "SELECT * FROM `order` ORDER BY order_id ASC LIMIT $startpage, $showpage ;";
            break;
    }
    $sort = $pdo->prepare($sqlsort);

    try {
        $sort->execute();
        $row = $sort->fetchAll();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }

} elseif (isset($_GET['keyword'])) {
    $stmt = $_GET['keyword'];

    $word =
        "SELECT *
FROM `order`
WHERE (
EXISTS (
SELECT 1
FROM pay
WHERE pay_name = :num1
AND pay_id = order.pay_id
)
OR EXISTS (
SELECT 1
FROM delivery
WHERE delivery_name = :num2
AND delivery_id = order.delivery_id
)
OR EXISTS (
SELECT 1
FROM receipt
WHERE receipt_name = :num3
AND receipt_id = order.receipt_id
)
OR EXISTS (
SELECT 1
FROM order_status
WHERE order_status_name = :num4
AND order_status_id = order.order_status_id
)
)
ORDER BY order_id DESC LIMIT $startpage, $showpage;";
    $sqlkeyword = $pdo->prepare($word);
    try {
        $sqlkeyword->bindParam(":num1", $stmt);
        $sqlkeyword->bindParam(":num2", $stmt);
        $sqlkeyword->bindParam(":num3", $stmt);
        $sqlkeyword->bindParam(":num4", $stmt);
        $sqlkeyword->execute();
        $row = $sqlkeyword->fetchAll();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }


} else {
    $order = "SELECT * FROM `order` ORDER BY order_id DESC LIMIT $startpage, $showpage ";
    $sqlOrder = $pdo->prepare($order); //準備
    try {
        $sqlOrder->execute(); //執行
        $row = $sqlOrder->fetchAll(); //取得結果
    } catch (PDOException $e) { //例外
        die("Error!: " . $e->getMessage() . "<br/>"); //例外執行
    }
};

?>

