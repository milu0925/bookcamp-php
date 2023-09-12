<?php
require "../connSQL.php";

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
<?php require '../CRUD/OrderRead.php'; ?>
<?php require '../header.php'; ?>
<body>

<!-- 登入以及登入彈跳視窗 -->
<?php require '../login/login.php'; ?>
<!-- 導覽列 -->
<?php require '../navbar.php'; ?>

<!-- 登入會員後才可以查看 -->
<?php if (isset($_SESSION['id'])): ?>
        <!-- 搜尋列 -->
        <div class="container">
        <div class="row bg-dark text-white p-2 m-2 rounded-3 border border-warning-subtl">
            <?php require "../component/state.php" ?>
            <?php require "../component/amount.php" ?>
            <?php require "../component/date.php" ?>
            <?php require "../component/keyword.php" ?>
        </div>
        </div>
        <div class="container">
        <!-- 訂單資料 -->
        <div class="row headerOrder">
            <div class="col-1">
                <span>狀態</span>
                <input class="ms-2" type="checkbox" id="checkAll">
            </div>
            <div class="col-1">
                <span>編號</span>
                <?php if (!isset($idSort) || $idSort === "idASC"): ?>
                    <a href="order.php?idSort=idDESC" role="button">
                    <i class="fa-solid fa-caret-up"></i>
                    </a>
                <?php elseif ($idSort === "idDESC"): ?>
                    <a href="order.php?idSort=idASC" role="button">
                    <i class="fa-solid fa-sort-up fa-rotate-180"></i>
                    </a>
                <?php endif; ?>
            </div>
            <div class="col-1">會員編號</div>
            <div class="col-1">消費金額</div>
            <div class="col-2">付款方式</div>
            <div class="col-2">創建日期</div>
            <div class="col-2">狀態管理</div>
            <div class="col-2">其他內容</div>
        </div>
        <div class="row accordion bg-white p-3 rounded-3 mb-4" id="orderDetail">
            <?php if (!$row): ?>
                    <div class="col-12 text-center" >目前沒有資料。</div>
            <?php else: ?>
                <?php foreach ($row as $key => $value): ?>
                    <div class="row">
                        <div class="col-1"><input type="checkbox" value="<?php echo $value["order_id"] ?>" name="batch_id[]" form="statusbatch"></div>
                        <div class="col-1"><?php echo $value["order_id"]; ?></div>
                        <div class="col-1"><?php echo $value["client_id"]; ?></div>
                        <div class="col-1"><?php echo $value["total"]; ?></div>
                        <div class="col-2"><?php echo $rowPay[$value["pay_id"] - 1]["pay_name"] ?></div>
                        <div class="col-2"><?php echo $value["order_create_date"]; ?></div>
                        <div class="col-2 d-flex justify-content-center">
                            <!-- 狀態判斷 -->
                            <?php foreach ($roworderStatus as $status) { ?>
                            <?php if ($status["order_status_id"] == $value["order_status_id"]): ?>
                                <?php if ($status["order_status_id"] == 1): ?>
                                    <div style="width:90px;">
                                        <div class="border border-1 border-danger rounded-pill w-100 d-flex align-items-center" style="color: #ff0000;">
                                        <i class="fa-solid fa-circle p-1"></i>
                                        <label class="pt-2">未完成</label>
                                        </div>
                                    </div>
                                <?php elseif ($status["order_status_id"] == 2): ?>
                                    <div style="width:90px;">
                                        <div class="border border-1 border-primary rounded-pill w-100 d-flex align-items-center" style="color: #1a53ff;font-family: 'CustomFont';">
                                        <i class="fa-solid fa-circle p-1"></i>
                                        <label class="pt-2">已完成</label>
                                        </div>
                                    </div>
                                <?php elseif ($status["order_status_id"] == 3): ?>
                                    <div style="width:90px;">
                                        <div class="border border-1 border-dark-subtle rounded-pill w-100 d-flex align-items-center" style="color: #9e9e9e;">
                                        <i class="fa-solid fa-circle p-1"></i>
                                        <label class="pt-2">取消</label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php } ?>
                        </div>
                        <div class="col-2 d-flex justify-content-center align-items-center">
                            <span class="changeOrderDetailIcon" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $key ?>" aria-controls="collapse<?php echo $key ?>">
                                <i class="mt-3 fa-solid fa-angles-down fa-lg" style="color: #cabf44;"></i>
                            </span>
                        </div>
                    </div>
                    <!-- 訂單明細 -->
                    <div id="collapse<?php echo $key ?>" class="accordion-collapse collapse" data-bs-parent="#orderDetail">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-5">
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
                                <div class="col-5">
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
                                    <a href="./order_detail.php?id=<?php echo $value["order_id"] ?>" class="btn btn-outline-primary"><i class="fa-regular fa-file-lines me-2"></i>查看明細</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php require '../component/page.php'; ?>
<?php require '../script/orderUse.php'; ?>
</body>
</html>