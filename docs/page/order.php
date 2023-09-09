<?php
require "../connSQL.php";

// 抓到mySQL-order
// $order = "SELECT * FROM `order`";
// $sqlOrder = $pdo->prepare($order); //準備
// try {
//     $sqlOrder->execute(); //執行
//     $roworder = $sqlOrder->fetchAll(); //取得結果
// } catch (PDOException $e) { //例外
//     die("Error!: " . $e->getMessage() . "<br/>"); //例外執行
// }
// ;

// 抓到mySQL-book
$product = "SELECT * FROM `book`";
$sqlproduct = $pdo->prepare($product); //準備
try {
    $sqlproduct->execute(); //執行
    $rowproduct = $sqlproduct->fetchAll(); //取得結果
} catch (PDOException $e) { //例外
    die("Error!: " . $e->getMessage() . "<br/>"); //例外執行
}
;

// 抓到mySQL-client
$client = "SELECT * FROM `client`";
$sqlClient = $pdo->prepare($client);
try {
    $sqlClient->execute();
    $rowClient = $sqlClient->fetchAll();
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;

// 抓到mySQL-order_status
$orderStatus = "SELECT * FROM `order_status`";
$sqlorderStatus = $pdo->prepare($orderStatus);
try {
    $sqlorderStatus->execute();
    $roworderStatus = $sqlorderStatus->fetchAll();
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;

// 抓到mySQL-coupon
$coupon = "SELECT * FROM `coupon`";
$sqlCoupon = $pdo->prepare($coupon);
try {
    $sqlCoupon->execute();
    $rowCoupon = $sqlCoupon->fetchAll();
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;

// 抓到mySQL-delivery
$delivery = "SELECT * FROM `delivery`";
$sqlDelivery = $pdo->prepare($delivery);
try {
    $sqlDelivery->execute();
    $rowDelivery = $sqlDelivery->fetchAll();
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;

// 抓到mySQL-receipt
$receipt = "SELECT * FROM `receipt`";
$sqlReceipt = $pdo->prepare($receipt);
try {
    $sqlReceipt->execute();
    $rowReceipt = $sqlReceipt->fetchAll();
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;

// 抓到mySQL-pay
$pay = "SELECT * FROM `pay`";
$sqlPay = $pdo->prepare($pay);
try {
    $sqlPay->execute();
    $rowPay = $sqlPay->fetchAll();
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;

?>
<?php require '../component/page.php'; ?>
<?php require '../header.php'; ?>
<body>

<!-- 登入以及登入彈跳視窗 -->
<?php require '../login/login.php'; ?>
<!-- 導覽列 -->
<?php require '../navbar.php'; ?>
<?php if (isset($_SESSION['id'])): ?>

        <!-- 搜尋列 -->
        <div class="row">
        <?php require "../component/state.php" ?>
        <?php require "../component/amount.php" ?>
        <?php require "../component/date.php" ?>
        <?php require "../component/keyword.php" ?>
        </div>

        <!-- 訂單資料 -->
        <table class="w-100">
        <thead>
        <tr class="text-center bg-light">
        <th>
        <span>更改狀態</span><input class="ms-2" type="checkbox" id="checkAll">
        </th>
        <th class="d-flex justify-content-center">

        訂單編號
        <?php if (!isset($idSort) || $idSort === "idASC"): ?>
                <div class="d-flex flex-column">
                <a href="order.php?idSort=idDESC" role="button">
                <i class="fa-solid fa-caret-up"></i>
                </a>
        <?php elseif ($idSort === "idDESC"): ?>
                <a href="order.php?idSort=idASC" role="button">
                <i class="fa-solid fa-sort-up fa-rotate-180"></i>
                </a>
                </div>
        <?php endif; ?>

        </th>
        <th>會員編號</th>
        <th>消費金額</th>
        <th>付款方式</th>
        <th>創建日期</th>
        <th>狀態管理</th>
        <th>其他內容</th>
        </tr>
        </thead>
        <tbody class="accordion" id="orderDetail">
        <!-- 訂單顯示資料 -->
        <?php if (!$row): ?>
                <tr>
                <td colspan="8" class="text-center" >目前沒有資料。</td>
                </tr>
        <?php else: ?>
                <?php foreach ($row as $key => $value): ?>
                        <tr class="text-center">
                        <td>
                        <input type="checkbox" value="<?php echo $value["order_id"] ?>" name="batch_id[]"
                        form="statusbatch">
                        </td>
                        <td>
                        <?php echo $value["order_id"]; ?>
                        </td>
                        <td>
                        <?php echo $value["client_id"]; ?>
                        </td>
                        <td>
                        <?php echo $value["total"]; ?>
                        </td>
                        <td>
                        <?php echo $rowPay[$value["pay_id"] - 1]["pay_name"] ?>
                        </td>
                        <td>
                        <?php echo $value["order_create_date"]; ?>
                        </td>
                        <td class="d-flex justify-content-center">
                        <!-- 判斷狀態 -->
                        <?php foreach ($roworderStatus as $status) { ?>
                                <?php if ($status["order_status_id"] == $value["order_status_id"]): ?>
                                            <?php if ($status["order_status_id"] == 1): ?>
                                                        <div style="width:90px;">
                                                        <div class="border border-1 border-danger rounded-pill w-100 d-flex align-items-center"
                                                        style="color: #ff0000;">
                                                        <i class="fa-solid fa-circle p-1"></i>
                                                        <label class="pt-2">未完成</label>
                                                        </div>
                                                        </div>
                                            <?php elseif ($status["order_status_id"] == 2): ?>
                                                        <div style="width:90px;">
                                                        <div class="border border-1 border-primary rounded-pill w-100 d-flex align-items-center"
                                                        style="color: #1a53ff;font-family: 'CustomFont';">
                                                        <i class="fa-solid fa-circle p-1"></i>
                                                        <label class="pt-2">已完成</label>
                                                        </div>
                                                        </div>

                                            <?php elseif ($status["order_status_id"] == 3): ?>
                                                        <div style="width:90px;">
                                                        <div class="border border-1 border-dark-subtle rounded-pill w-100 d-flex align-items-center"
                                                        style="color: #9e9e9e;">
                                                        <i class="fa-solid fa-circle p-1"></i>
                                                        <label class="pt-2">取消</label>
                                                        </div>
                                                        </div>
                                            <?php endif; ?>
                                <?php endif; ?>
                        <?php } ?>
                        </td>
                        <td>
                        <span class="changeOrderDetailIcon" data-bs-toggle="collapse"
                        data-bs-target="#collapse<?php echo $key ?>" aria-controls="collapse<?php echo $key ?>">
                        <i class="mt-3 fa-solid fa-angles-down fa-lg" style="color: #cabf44;"></i>
                        </span>
                        </td>
                        </tr>
                        <tr>
                        <td colspan="8">
                        <!-- 訂單顯示資料範圍 -->
                        <div id="collapse<?php echo $key ?>" class="accordion-collapse collapse"
                        data-bs-parent="#orderDetail">
                        <div class="accordion-body">
                        <!-- 訂單明細 -->
                        <div class="row">
                        <div class="col-5 bg-light rounded-start-3">
                        <p>收貨人姓名：
                        <?php echo $value["consignee"]; ?>
                        </p>
                        <p>收貨人電話：
                        <?php echo $value["consignee_phone"]; ?>
                        </p>
                        <p>收貨人地址：
                        <?php echo substr($value["consignee_address"], 0, 9); ?>
                        </p>
                        </div>
                        <div class="col-5 bg-light rounded-end-3">
                        <p>配送方式：
                        <?php echo $rowDelivery[$value["delivery_id"] - 1]["delivery_name"]; ?>
                        </p>
                        <p>發票方式：
                        <?php echo $rowReceipt[$value["receipt_id"] - 1]["receipt_name"]; ?>
                        </p>
                        <p>　
                        優惠卷：
                        <?php 
                        $couponOK = ($value["coupon_id"] !== null) ? $rowCoupon[$value["coupon_id"] - 1]["coupon_name"] : '無' ;                        
                        echo $couponOK ;
                        ?>
                        </p>
                        </div>
                        <div class="col-2">
                        <div class="d-flex justify-content-center align-items-center w-100 h-100">
                        <a 
                        href="./order_detail.php?id=<?php echo $value["order_id"] ?>"><i
                        class="fa-regular fa-file-lines me-2"></i>查看明細</a>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </td>
                        </tr>
                <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
        </table>

        <!-- 分頁包 -->
        <div>
        <!-- 分頁+選擇金額 -->
        <?php if (isset($page) && isset($_GET['minmoney']) || isset($_GET['maxmoney'])): ?>
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                <li class="page-item">
                <a class="page-link"
                href="order.php?minmoney=<?php echo $min ?>&maxmoney=<?php echo $max ?>&page=<?php echo ($page > 1) ? $page - 1 : 1; ?>"
                aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                </a>
                </li>

                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                        <li class="page-item"> <a
                        href="order.php?minmoney=<?php echo $min ?>&maxmoney=<?php echo $max ?>&page=<?php echo $i ?>"
                        class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>

                <li class="page-item">
                <a class="page-link"
                href="order.php?minmoney=<?php echo $min ?>&maxmoney=<?php echo $max ?>&page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
                </ul>
                </nav>
                <!-- 分頁+日期 -->
        <?php elseif (isset($page) && isset($_GET['createDate'])): ?>
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                <li class="page-item">
                <a class="page-link"
                href="order.php?createDate=<?php echo $date ?>&page=<?php echo ($page > 1) ? $page - 1 : 1; ?>"
                aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                </a>
                </li>

                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                        <li class="page-item"> <a href="order.php?createDate=<?php echo $date ?>&page=<?php echo $i ?>"
                        class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>

                <li class="page-item">
                <a class="page-link"
                href="order.php?createDate=<?php echo $date ?>&page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
                </ul>
                </nav>
                <!-- 分頁+訂單 -->
        <?php elseif (isset($page) && isset($_GET['idSort'])): ?>
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                <li class="page-item" <?php if ($page == 1)
                    echo "disabled" ?>>
                        <a class="page-link" href="order.php?idSort=
<?php echo (isset($_GET['idSort'])) ? "idDESC" : "idASC" ?>
&page=<?php echo ($page > 1) ? $page - 1 : 1; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                </a>
                </li>

                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                        <li class="page-item"> <a
                        href="order.php?idSort=<?php echo (isset($_GET['idSort'])) ? "idDESC" : "idASC" ?>&page=<?php echo $i ?>"
                        class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>

                <li class="page-item" <?php if ($page == $totalpage)
                    echo "disabled" ?>>
                        <a class="page-link"
                        href="order.php?idSort=<?php echo (isset($_GET['idSort'])) ? "idDESC" : "idASC" ?>&page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
                </ul>
                </nav>
                <!-- 分頁+關鍵字 -->
        <?php elseif (isset($page) && isset($_GET['keyword'])): ?>
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                <li class="page-item" <?php if ($page == 1)
                    echo "disabled" ?>>
                        <a class="page-link"
                        href="order.php?keyword=<?php echo $stmt ?>&page=<?php echo ($page > 1) ? $page - 1 : 1; ?>"
                aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                </a>
                </li>

                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                        <li class="page-item"> <a href="order.php?keyword=<?php echo $stmt ?>&page=<?php echo $i ?>"
                        class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>

                <li class="page-item" <?php if ($page == $totalpage)
                    echo "disabled" ?>>
                        <a class="page-link"
                        href="order.php?keyword=<?php echo $stmt ?>&page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
                </ul>
                </nav>
        <?php else: ?>
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                <li class="page-item" <?php if ($page == 1)
                    echo "disabled" ?>>
                        <a class="page-link" href="order.php?page=<?php echo ($page > 1) ? $page - 1 : 1; ?>"
                aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                </a>
                </li>

                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                        <li class="page-item"> <a href="order.php?page=<?php echo $i ?>"
                        class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>

                <li class="page-item" <?php if ($page == $totalpage)
                    echo "disabled" ?>>
                        <a class="page-link"
                        href="order.php?page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
                </ul>
                </nav>
        <?php endif; ?>
        </div>
<?php endif; ?>
<?php require '../order/orderUse.php'; ?>
</body>
</html>