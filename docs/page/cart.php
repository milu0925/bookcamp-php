<?php
require '../connSQL.php';

// 顯示購物車資訊
$cart = "SELECT * FROM `cart` c
JOIN book b on b.book_id = c.book_id
WHERE c.client_id = ?";
$sqlCart = $pdo->prepare($cart);

try {
    $sqlCart->execute([$_SESSION['id']]);
    $rows = $sqlCart->fetchAll();

} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;


// 抓到最後一張訂單
$ordersql = "SELECT * FROM `order`";
$order = $pdo->prepare($ordersql);

try {
    $order->execute();
    $orderID = $order->rowCount();

} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;


?>

<?php require '../header.php'; ?>
<body>
<!-- 登入以及登入彈跳視窗 -->
<?php require '../login/login.php'; ?>
<!-- 導覽列 -->
<?php require '../navbar.php'; ?>

<?php if (isset($_SESSION['id'])): ?>   
    <div class="container mb-5">   
                <h2>購物車資訊</h2>
                <div class="row headerCart">
                <div class="col-1"><input type="checkbox" id="checkAll"></div>
                <div class="col-5">名稱</div>
                <div class="col-1">價格</div>
                <div class="col-3 text-center">數量</div>
                <div class="col-1">小計</div>
                <div class="col-1">操作</div>
                </div>
            
                <form method="POST" action="/phpProject/docs/CRUD/OrderInsert.php" id="checkCart" class="d-flex flex-column justify-content-end">
                <div id="orderDetail">
                    <?php foreach ($rows as $key => $value): ?>
                    <div class="row colCart font3 fm">
                        <div class="col-1">
                            <input type="hidden" name="oid"  value="<?php echo $orderID + 1 ?>">
                            <input type="hidden" name="pid" class="pid"  value="<?php echo $value['book_id'] ?>">
                            <input type="checkbox"  name="cartid[]" value="<?php echo $value['cart_id'] ?>">
                        </div>
                        <div class="col-5">
                            <?php echo $value['b_title'] ?>
                        </div>
                        <div class="col-1 pricebox">
                            <input name="pprice" type="hidden" value="<?php echo $value['book_price'] ?>" >
                            <?php echo $value['book_price'] ?>
                        </div>
                        <div class="col-3 countbox">
                            <div class="input-group changecount d-flex justify-content-center">
                                <input type="text" name="pcount" class="pcount cartinput font1 fl" value="<?php echo $value['book_count'] ?>">
                                <div class="d-flex flex-column cartbtn">
                                    <button type="button"><i class="fa-solid fa-caret-up fa-2xs"></i></button>
                                    <button type="button"><i class="fa-solid fa-caret-down fa-2xs"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-1 amountbox">
                        </div>
                        <div class="col-1"><a class="delete"><i class="fa-solid fa-trash-can"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <div class="row colCheckBlock font3 fl">
                        <div class="col-9"></div>
                        <div class="col-1">總計</div>
                        <div class="col-2 totalbox"></div>
                    </div>
                </div>
                <button type="button" class="okBtn-y" id="gocheckout">結帳</button>
                </form>
                </div>
<?php endif; ?>
<?php require '../script/cartUse.php'; ?>
</body>
</html>