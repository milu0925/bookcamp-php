<?php
require '../connSQL.php';
require '../header.php';


// 顯示購物車資訊
$cart = "SELECT * FROM cart WHERE client_id = ? AND book_id IN (" . implode(',', $_POST['cartid']) . ")";
$sqlCart = $pdo->prepare($cart);

try {
    $sqlCart->execute([$_SESSION['id']]);
    $rows = $sqlCart->fetchAll();

} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;

// 抓到訂單數要傳進明細
$orderdetail = "SELECT COUNT(*) FROM `bookorder`;";
$sqlorderdetail = $pdo->prepare($orderdetail);
try {
    $sqlorderdetail->execute();
    $rowdetail = $sqlorderdetail->fetchColumn();
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;

// 抓到訂單
$order = "SELECT * FROM `bookorder` WHERE order_id=? ;";
$sqlorder = $pdo->prepare($order);
try {
    $sqlorder->execute([$_SESSION['id']]);
    $row = $sqlorder->fetchAll();
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

// 抓到mySQL-coupon
$coupon = "SELECT * FROM `coupon` WHERE client_id = ?";
$sqlCoupon = $pdo->prepare($coupon);
try {
    $sqlCoupon->execute();
    $rowCoupon = $sqlCoupon->fetchAll();
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;


// 移除購物車內容
if (isset($_GET["remove"])) {
    $removeid = $_GET['remove'];
    $CartId = "DELETE FROM `cart` WHERE book_id=?";
    $sqldel = $pdo->prepare($CartId);
    try {
        $sqldel->execute([$removeid]);
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }
    ;
    header("Location: Cart.php");
}

?>


<body>
<?php require '../login/login.php'; ?>
        <h2>結帳畫面</h2>
        <form action="../CRUD/OrderInsert.php" method="POST">
            <div class="row">
                <div class="col-6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>序</th>
                                <th>產品名稱</th>
                                <th>產品價格</th>
                                <th>產品數量</th>
                                <th>小計</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $key => $value): ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="oid[]" class="oid" value="<?= $rowdetail + 1 ?>">
                                                <input type="hidden" name="pid[]" class="pid" value="<?= $value['book_id'] ?>">
                                                <?= $key + 1 ?>
                                            </td>
                                            <td>
                                                <?= $value['book_name'] ?>
                                            </td>
                                            <td>
                                                <input class="pprice" name="pprice[]" type="hidden"
                                                    value="<?= $value['book_price'] ?>">
                                                <?= $value['book_price'] ?>
                                            </td>
                                            <td>
                                                <div class="input-group changecount">
                                                    <input type="hidden" name="pcount[]" value="<?= $value['book_count'] ?>">
                                                    <?= $value['book_count'] ?>
                                                </div>
                                            </td>
                                            <td class="new">
                                            </td>

                                        </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>運費</td>
                                <td id="fee"> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>總計</td>
                                <td class="total">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <!-- 付款方式 -->
                    <div class="row">
                        <div class="col-10">
                            <h6 class="border-bottom">付款方式</h6>
                            <div class="d-flex">
                                <div class="d-flex align-items-center">
                                    <input type="radio" class="pe-1" name="pay" value="<?= $rowPay[0]['pay_id'] ?>" checked>
                                    <div class="imgdiv">
                                        <img src="../../img/<?= $rowPay[0]['pay_img'] ?>" alt="" class="w-100">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="radio" class="pe-1" name="pay" value="<?= $rowPay[1]['pay_id'] ?>">
                                    <div class="imgdiv">
                                        <img src="../../img/<?= $rowPay[1]['pay_img'] ?>" alt="" class="w-100">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="radio" class="pe-1" name="pay" value="<?= $rowPay[2]['pay_id'] ?>">
                                    <div class="imgdiv">
                                        <img src="../../img/<?= $rowPay[2]['pay_img'] ?>" alt="" class="w-100">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="radio" class="pe-1" name="pay" value="<?= $rowPay[3]['pay_id'] ?>">
                                    <div class="imgdiv">
                                        <img src="../../img/<?= $rowPay[3]['pay_img'] ?>" alt="" class="w-100">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2"></div>
                    </div>
                    <!-- 收貨人/配送方式 -->
                    <div class="row pt-5">
                        <div class="col-6">
                            <h6 class="border-bottom">收貨人名稱</h6>
                            <input type="hidden" name="clientid" value="<?= $_SESSION['id'] ?>">

                            <label for="consignee">姓名</label><br>
                            <input type="text" name="consignee" id="consignee" value="<?= $_SESSION['name'] ?>"><br>
                            <label for="consigneephone">聯絡電話</label><br>
                            <input type="text" name="consigneephone" id="consigneephone"
                                value="<?= $_SESSION['phone'] ?>"><br>
                            <label for="consigneeaddress">收貨地址</label><br>
                            <input type="text" name="consigneeaddress" id="consigneeaddress"
                                value="<?= $_SESSION['address'] ?>"><br>
                        </div>
                        <div class="col-4">
                            <h6 class="border-bottom">配送方式</h6>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="pe-1" name="delivery"
                                    value="<?= $rowDelivery[0]['delivery_id'] ?>" checked>
                                <span>
                                    <?= $rowDelivery[0]['delivery_name'] ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="pe-1" name="delivery"
                                    value="<?= $rowDelivery[1]['delivery_id'] ?>">
                                <span>
                                    <?= $rowDelivery[1]['delivery_name'] ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="pe-1" name="delivery"
                                    value="<?= $rowDelivery[2]['delivery_id'] ?>">
                                <span>
                                    <?= $rowDelivery[2]['delivery_name'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- 發票 -->
                    <div class="row pt-5">
                        <div class="col-6">
                            <h6 class="border-bottom">發票</h6>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="pe-1" name="receipt"
                                    value="<?= $rowReceipt[0]['receipt_id'] ?>" checked>
                                <span>
                                    <?= $rowReceipt[0]['receipt_name'] ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="pe-1" name="receipt"
                                    value="<?= $rowReceipt[1]['receipt_id'] ?>">
                                <span>
                                    <?= $rowReceipt[1]['receipt_name'] ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="pe-1" name="receipt"
                                    value="<?= $rowReceipt[2]['receipt_id'] ?>">
                                <span>
                                    <?= $rowReceipt[2]['receipt_name'] ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-4">
                            <h6 class="border-bottom">優惠卷</h6>
                        <div class="d-flex align-items-center">
                            <input type="radio" class="pe-1" name="coupon[]"
                                value="">
                            <span>
                            </span>
                        </div>
                            <div class="d-flex align-items-end">
                                <input type="submit" value="送出訂單" class="nes-btn is-primary">
                            </div>
                        </div>
                        <div class="col-2"></div>
                    </div>

                </div>
            </div>
        </form>

        <?php require '../script/checkoutUse.php'; ?>