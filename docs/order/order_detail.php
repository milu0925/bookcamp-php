<?php
require "./connSQL.php";
require "./CDN.php";
require "../allCDN.php";


/* 抓到的變數檢查區 */
$id = isset($_GET['id']) ? $_GET['id'] : null;


/*  讀取-這裡抓到想要的訂單明細資訊    */
$orderdetail = "SELECT *,(o.book_count * o.book_price) AS amount 
FROM `bookorder_detail` o
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
JOIN coupon cou ON  o.coupon_id = cou.coupon_id
JOIN delivery dev ON  o.delivery_id = dev.delivery_id
JOIN order_status os ON  o.order_status_id = os.order_status_id
JOIN pay p ON  o.pay_id = p.pay_id
JOIN receipt rec ON  o.receipt_id = rec.receipt_id
WHERE detail.order_id =?";

$sqlOrder = $pdo->prepare($order); //準備
try {
    $sqlOrder->execute([$id]); //執行
    $roworder = $sqlOrder->fetchAll(); //取得結果
    $ordercount = $sqlOrder->rowcount(); //總和
} catch (PDOException $e) {
    echo $e->getMessage();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .imgdiv {
            width: 100px;
            height:100px;
        }

        .row{
            font-family: 'CustomFont';
        }

    </style>
    <title>訂單明細</title>
</head>

<body>

<div class="row">
<!-- 左側訂單明細 -->
        <div class="col-8">
        <a href="./order.php" class="nes-btn">返回</a>
            <div class="p-4 d-flex flex-column">
            <div> 共買了 <?= $ordercount ?> 個商品 </div>
                <table>
                    <thead>
                        <tr class="bg-light">
                            <th class="p-2">商品</th>
                            <th>圖片</th>
                            <th class="text-center">單價</th>
                            <th class="text-center">數量</th>
                            <th class="text-end">小計</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($roworderdetail as $value) { ?>
                            <tr>
                                <td class="w-25">
                                    <?= $value['book_name'] ?>
                                </td>
                                <td>
                                    <div class="imgdiv">
                                        <img src="./product/img/<?= $value['book_img'] ?>" alt="Product Image"
                                            class="w-100">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?= $value['product_price'] ?>
                                </td>
                                <td class="text-center">
                                    <?= $value['product_count'] ?>
                                </td>
                                <td class="text-end">
                                    <?= $value['amount'] ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">運費</td>
                            <td class="text-end">
                                <?= $roworder[0]['delivery_fee'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <hr />
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <!-- <?= $roworder[0]['coupon_name'] ?> -->
                            </td>
                            <td></td>
                            <td></td>
                            <td class="text-center">總計</td>
                            <td class="text-end">
                                <?= number_format($roworder[0]['total']) ?>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- 右側訂單資訊 -->
        <div class="col-4">
            <div class="mt-5 bg-light p-5">
                <div>訂單編號：
                    <span>
                        <?= $roworder[0]['order_id'] ?>
                    </span>
                </div>
                <div>訂單時間：
                    <span>
                        <?= $roworder[0]['order_date'] ?>
                    </span>
                </div>
                <div>出貨狀態：
                    <span>
                        <?= $roworder[0]['order_d_status'] ?>
                    </span>
                </div>
                <div>出貨狀態：
                    <span>
                        <?= $roworder[0]['receipt_status'] ?>
                    </span>
                </div>
                <div>
                    <hr>
                </div>
                <div>會員編號：
                    <span>
                        <?= $roworder[0]['client_id'] ?>
                    </span>
                </div>
                <div>會員姓名：
                    <span>
                        <?= $roworder[0]['client_name'] ?>
                    </span>
                </div>
                <div>會員信箱：
                    <span>
                        <?= $roworder[0]['email'] ?>
                    </span>
                </div>
                <div>付款方式：
                    <span>
                        <?= $roworder[0]['pay_status'] ?>
                    </span>
                </div>
                <div>
                    <hr>
                </div>
                <div>送貨方式：
                    <span>
                        <?= $roworder[0]['delivery_status'] ?>
                    </span>
                </div>
                <div>收貨人姓名：
                    <span>
                        <?= $roworder[0]['consignee'] ?>
                    </span>
                </div>
                <div>收貨人電話：
                    <span>
                        <?= $roworder[0]['consignee_phone'] ?>
                    </span>
                </div>
                <div>收貨人地址：
                    <span>
                        <?= $roworder[0]['consignee_address'] ?>
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