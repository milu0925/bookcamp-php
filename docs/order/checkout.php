<?php
require './connSQL.php';
require './CDN.php';
require '../allCDN.php';


// 顯示購物車資訊
$cart = "SELECT * FROM cart WHERE client_id = ? AND product_id IN (" . implode(',', $_POST['cartid']) . ")";
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
// $coupon = "SELECT * FROM `coupon` WHERE client_id = ?";
// $sqlCoupon = $pdo->prepare($coupon);
// try {
//     $sqlCoupon->execute();
//     $rowCoupon = $sqlCoupon->fetchAll();
// } catch (PDOException $e) {
//     die("Error!: " . $e->getMessage() . "<br/>");
// }
// ;


// 移除購物車內容
if (isset($_GET["remove"])) {
    $removeid = $_GET['remove'];
    $CartId = "DELETE FROM `cart` WHERE product_id=?";
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


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>購物車</title>
        <style>
            .imgdiv {
                width: 100px;
                height: auto;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            body{
                font-family: 'CustomFont';
            }
        </style>
    </head>

    <body>
        <h2 class="text-end">
            User:
            <?= $_SESSION['name'] ?>
        </h2>
        <h2>結帳畫面</h2>
        <form action="InsertOrder.php" method="POST">
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
                                        <input type="hidden" name="pid[]" class="pid" value="<?= $value['product_id'] ?>">
                                        <?= $key + 1 ?>
                                    </td>
                                    <td>
                                        <?= $value['product_name'] ?>
                                    </td>
                                    <td>
                                        <input class="pprice" name="pprice[]" type="hidden"
                                            value="<?= $value['product_price'] ?>">
                                        <?= $value['product_price'] ?>
                                    </td>
                                    <td>
                                        <div class="input-group changecount">
                                            <input type="hidden" name="pcount[]" value="<?= $value['product_count'] ?>">
                                            <?= $value['product_count'] ?>
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
                                        <img src="./img/<?= $rowPay[0]['pay_img'] ?>" alt="" class="w-100">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="radio" class="pe-1" name="pay" value="<?= $rowPay[1]['pay_id'] ?>">
                                    <div class="imgdiv">
                                        <img src="./img/<?= $rowPay[1]['pay_img'] ?>" alt="" class="w-100">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="radio" class="pe-1" name="pay" value="<?= $rowPay[2]['pay_id'] ?>">
                                    <div class="imgdiv">
                                        <img src="./img/<?= $rowPay[2]['pay_img'] ?>" alt="" class="w-100">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="radio" class="pe-1" name="pay" value="<?= $rowPay[3]['pay_id'] ?>">
                                    <div class="imgdiv">
                                        <img src="./img/<?= $rowPay[3]['pay_img'] ?>" alt="" class="w-100">
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
                                    <?= $rowDelivery[0]['delivery_status'] ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="pe-1" name="delivery"
                                    value="<?= $rowDelivery[1]['delivery_id'] ?>">
                                <span>
                                    <?= $rowDelivery[1]['delivery_status'] ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="pe-1" name="delivery"
                                    value="<?= $rowDelivery[2]['delivery_id'] ?>">
                                <span>
                                    <?= $rowDelivery[2]['delivery_status'] ?>
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
                                    <?= $rowReceipt[0]['receipt_status'] ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="pe-1" name="receipt"
                                    value="<?= $rowReceipt[1]['receipt_id'] ?>">
                                <span>
                                    <?= $rowReceipt[1]['receipt_status'] ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="pe-1" name="receipt"
                                    value="<?= $rowReceipt[2]['receipt_id'] ?>">
                                <span>
                                    <?= $rowReceipt[2]['receipt_status'] ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-4">
                            <!-- <h6 class="border-bottom">優惠卷</h6>
                        <div class="d-flex align-items-center">
                            <input type="radio" class="pe-1" name="coupon[]"
                                value="">
                            <span>
                            </span>
                        </div> -->
                            <div class="d-flex align-items-end">
                                <input type="submit" value="送出訂單" class="nes-btn is-primary">
                            </div>
                        </div>
                        <div class="col-2"></div>
                    </div>

                </div>
            </div>
        </form>


        <script>
            $(function () {

                amount();

                // 產品數量的++
                $('.table').on('click', 'button:nth-child(1)', function () {
                    let productcount = $(this).parents('.changecount').find('input').val();
                    productcount++;
                    $(this).parents('.changecount').find('input').val(productcount);
                    amount();
                    updateCartItem($(this).parents('tr'));
                    // 產品數量的--
                });
                $('.table').on('click', 'button:nth-child(2)', function () {
                    let productcount = $(this).parents('.changecount').find('input').val();
                    if (productcount > 1) {
                        productcount--;
                        $(this).parents('.changecount').find('input').val(productcount);
                        amount();
                        updateCartItem($(this).parents('tr'));

                    }
                });

                $('#gocheckout').on('click', function () {
                    // 將購物車資料送到伺服器端進行處理
                    $.ajax({
                        url: 'InsertOrder.php',
                        method: 'POST',
                        data: {
                            cartData: JSON.stringify(getCartData()) // 將購物車資料轉為 JSON 字串
                        }
                    });
                });

                // 取得購物車資料的函式
                function getCartData() {
                    var cartData = [];
                    $('.table>tbody>tr').each(function () {
                        var product = {
                            orderId: $(this).find('.oid').val(),
                            productId: $(this).find('.pid').val(),
                            productPrice: $(this).find('.pprice').val(),
                            productCount: $(this).find('.pcount').val()
                        };
                        cartData.push(product);
                    });
                    return cartData;
                }

                //傳到Cart更新資料庫
                function updateCartItem(row) {
                    var productId = row.children('td:nth-child(1)').find('input:nth-child(2)').val();
                    var count = parseInt(row.children('td:nth-child(4)').find('input').val());
                    $.ajax({
                        url: 'UpdateCart.php',
                        method: 'POST',
                        data: {
                            pid: productId,
                            pcount: count,
                        }
                    });
                };


    



                // 計算小計
                function amount() {
                    var total = 0;
                    $('.table>tbody>tr').each(function () {
                        let input = $(this).find('td div input');

                        let allcount = parseInt(input.val());
                        let allprice = parseInt($(this).find('td:nth-child(3)').text());
                        let subtotal = allcount * allprice;
                        $(this).find('.new').text(subtotal);
                    });
                    $('.table>tbody>tr').each(function () {
                        let sum = parseInt($(this).find('.new').text());
                        if (!isNaN(sum)) {
                            total = total + sum;
                        }
                        // 計算運費
                        function fee(value) {
                            if (total > 3000) {
                                return value = $('.table>tbody>tr').find('#fee').text('0');
                            } else {
                                return value = $('.table>tbody>tr').find('#fee').text('60');
                            }
                        }
                        fee(total);
                        let feee =  $('.table>tbody>tr').find('#fee').text();
                        let result = parseInt(feee)+total;
                        $(this).find('.total').text(result);
                    })

          

                };
            });
        </script>
    </body>

</html>