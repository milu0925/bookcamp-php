<?php
require "../connSQL.php";

/* 總價 */
$total = 0;

/* 抓到的變數檢查 */
$id = isset($_GET['id']) ? $_GET['id'] : null;

/*  讀取-訂單明細資訊    */
$orderdetail = "SELECT *,(o.book_count * o.book_price) AS amount 
FROM `order_detail` o
JOIN book pro ON o.book_id = pro.book_id
WHERE o.order_id =?";
$sqlOrderdetail = $pdo->prepare($orderdetail); //準備
try {
    $sqlOrderdetail->execute([$id]); //執行
    $roworderdetail = $sqlOrderdetail->fetchAll(); //取得結果
} catch (PDOException $e) {
    echo $e->getMessage();
}


/*  讀取-這裡抓到想要的訂單資訊    */
$order = "SELECT * FROM `order` o
JOIN order_detail detail ON o.order_id = detail.order_id
JOIN client u ON  o.client_id = u.client_id
LEFT JOIN coupon cou ON  o.coupon_id = cou.coupon_id
JOIN delivery dev ON  o.delivery_id = dev.delivery_id
JOIN order_status os ON  o.order_status_id = os.order_status_id
JOIN pay p ON  o.pay_id = p.pay_id
JOIN receipt rec ON  o.receipt_id = rec.receipt_id
WHERE o.order_id =?";
$sqlOrder = $pdo->prepare($order); //準備
try {
    $sqlOrder->execute([$id]); //執行
    $roworder = $sqlOrder->fetchAll(); //取得結果
    $ordercount = $sqlOrder->rowcount(); //總和
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<?php require '../header.php'; ?>
<body>
    <!-- 登入以及顯示 -->
    <?php require '../login/login.php'; ?>
    <!-- 導覽列 -->
    <?php require '../navbar.php'; ?>

<div class="row">
<!-- 左側訂單明細 -->
        <div class="col-8">
        <a href="./order.php">返回</a>
            <div class="p-4 d-flex flex-column">
            <div> 共買了 <?php echo $ordercount ?> 個商品 </div>
                        <div class="row">
                            <div class="col-5">商品</div>
                            <div class="col-4">圖片</div>
                            <div class="col-1">單價</div>
                            <div class="col-1">數量</div>
                            <div class="col-1">小計</div>
                        </div>
                        <div class="row">
                        <?php foreach ($roworderdetail as $value): ?>
                                <div class="col-5">
                                    <?php echo $value['b_title'] ?>
                                </div>
                                <div class="col-4">
                                    <div class="imgdiv">
                                        <img src="../../img/<?php echo $value['book_img_id'] ?>" alt="book Image"
                                            class="w-100">
                                    </div>
                                </div>
                                <div class="col-1">
                                    <?php echo $value['book_price'] ?>
                                </div>
                                <div class="col-1">
                                    <?php echo $value['book_count'] ?>
                                </div>
                                <div class="col-1 text-end">
                                    <?php echo $value['amount'] ?>
                                </div>
                                <?php $total += $value['amount'] ?>
                        <?php endforeach; ?>
                        </div>
                        <div class="row">
                            <div class="col-10"></div>
                            <div class="col-1">運費</div>
                            <div class="col-1 text-end"><?php echo $roworder[0]['delivery_fee'] ?></div>
                            <div class="col-10"></div>
                            <div class="col-1">優惠卷</div>
                            <div class="col-1 text-end">
                                <?php
                                if ($roworder[0]['discount'] >  0 ){
                                    $couponValue = -$roworder[0]['discount_display'];
                                } else if ($roworder[0]['discount'] <  0 ) {
                                    $couponValue = $roworder[0]['discount_display'].'折';
                                } else {
                                    $couponValue =  '無';
                                };
                                 echo $couponValue;
                                ?></div>
                            <hr /> 
                            <div class="col-10"></div>
                            <div class="col-1">總計</div>
                            <div class="col-1 text-end">
                            <?php 
                            if ($roworder[0]['discount'] >  0 ){
                                $alltotal = ($total-$roworder[0]['discount'])+$roworder[0]['delivery_fee'];
                            } else if ($roworder[0]['discount'] <  0 ) {
                                $alltotal = ($total*$roworder[0]['discount'])+$roworder[0]['delivery_fee'];
                            } else {
                                $alltotal = $total+$roworder[0]['delivery_fee'];
                            };
                            echo $alltotal; 
                            ?></div>
                        </div>
            
            </div>
        </div>

        <!-- 右側訂單資訊 -->
        <div class="col-4">
            <div class="mt-5 bg-light p-5">
                <div>訂單編號：
                    <span>
                        <?php echo $roworder[0]['order_id'] ?>
                    </span>
                </div>
                <div>訂單時間：
                    <span>
                        <?php echo $roworder[0]['order_create_date'] ?>
                    </span>
                </div>
                <div>訂單狀態：
                    <span>
                        <?php echo $roworder[0]['order_status_name'] ?>
                    </span>
                </div>
                <div>發票方式：
                    <span>
                        <?php echo $roworder[0]['receipt_name'] ?>
                    </span>
                </div>
                <div>優惠卷名稱：
                    <span>
                        <?php
                        $coupon = ($roworder[0]['coupon_id'] !== null) ? $roworder[0]['coupon_name'] : '無';
                        echo $coupon
                            ?>
                    </span>
                </div>
                <div>
                    <hr>
                </div>
                <div>會員編號：
                    <span>
                        <?php echo $roworder[0]['client_id'] ?>
                    </span>
                </div>
                <div>會員姓名：
                    <span>
                        <?php echo $roworder[0]['client_name'] ?>
                    </span>
                </div>
                <div>會員信箱：
                    <span>
                        <?php echo $roworder[0]['email'] ?>
                    </span>
                </div>
                <div>付款方式：
                    <span>
                        <?php echo $roworder[0]['pay_name'] ?>
                    </span>
                </div>
                <div>
                    <hr>
                </div>
                <div>送貨方式：
                    <span>
                        <?php echo $roworder[0]['delivery_name'] ?>
                    </span>
                </div>
                <div>收貨人姓名：
                    <span>
                        <?php echo $roworder[0]['consignee'] ?>
                    </span>
                </div>
                <div>收貨人電話：
                    <span>
                        <?php echo $roworder[0]['consignee_phone'] ?>
                    </span>
                </div>
                <div>收貨人地址：
                    <span>
                        <?php
                        $address = ($roworder[0]['delivery_address'] !== '') ? $roworder[0]['delivery_address'] : $roworder[0]['consignee_address'];
                        echo $address;
                        ?>
                    </span>
                </div>
                <div>
                    <hr>
                </div>
            </div>
        </div>
</div>
</body>

</html>