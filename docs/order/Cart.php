<?php
require './connSQL.php';
require './CDN.php';
require '../allCDN.php';


// 顯示購物車資訊
$cart = "SELECT * FROM `cart` WHERE client_id = ?";
$sqlCart = $pdo->prepare($cart);

try {
    $sqlCart->execute([$_SESSION['id']]);
    $rows = $sqlCart->fetchAll();

} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
;


?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>購物車</title>
        <script>
            //幫每個刪除按鈕加上data-id
            $(document).ready(function () {
                $('.delete').each(function (index) {
                    $(this).attr('data-id', index + 1); // 加1是为了使id从1开始，而不是从0开始
                });
            });
        </script>
        <style>
            body{
                font-family: 'CustomFont';
            }
        </style>
    </head>

    <body>

        <div class="d-flex justify-content-end">
            <p class="">User:
                <?= $_SESSION['name'] ?>
            </p>
            <a class="nav-link px-3 btn" href="./login/logout.php?logout=true">
                登出
            </a>
        </div>
        <a class="btn btn-warning ms-2 mb-3" href="./product/product.php">產品模擬畫面</a>


        <h2>購物車資訊</h2>
        <table class="table">
            <thead>
                <tr class="text-center">
                    <th >序
                        <input type="checkbox" id="checkAll" >
                    </th>
                    <th>產品名稱</th>
                    <th>產品價格</th>
                    <th>產品數量</th>
                    <th>小計</th>
                    <th class="text-center">操作</th>
                </tr>
            </thead>
            <tbody id="orderDetail">
                <form method="POST" action="./checkout.php">
                    <?php foreach ($rows as $key => $value): ?>
                        <tr class="text-center">
                            <td >
                                <input type="hidden" class="oid" value="<?= $rowdetail + 1 ?>">
                                <input type="hidden" class="pid" value="<?= $value['product_id'] ?>">
                                <input type="checkbox"  name="cartid[]" value="<?= $value['product_id'] ?>">
                            </td>
                            <td>
                                <?= $value['product_name'] ?>
                            </td>
                            <td style="font-family: 'Press Start 2P';">
                                <input class="pprice" type="hidden" value="<?= $value['product_price'] ?>" >
                                <?= $value['product_price'] ?>
                            </td>
                            <td style="font-family: 'Press Start 2P';">
                                <div class="input-group changecount d-flex justify-content-center ">
                                    <input type="text" class="pcount nes-input text-center"
                                        value="<?= $value['product_count'] ?>" style="width:100px;height:50px;">
                                    <div class="d-flex flex-column ps-2">
                                        <button type="button" class="nes-btn is-success"><i
                                                class="fa-solid fa-caret-up fa-2xs"></i></button>
                                        <button type="button" class="nes-btn is-success"><i
                                                class="fa-solid fa-caret-down fa-2xs"></i></button>
                                    </div>
                                </div>
                            </td>
                            <td class="new" style="font-family: 'Press Start 2P';">
                            </td>

                            <td class="text-center"><a class="delete">
                                    <i class="fa-solid fa-trash-can"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="text-center">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>總計</td>
                        <td class="total" style="font-family: 'Press Start 2P';"></td>
                        <td>
                            <button type="button" class="btn btn-primary" id="gocheckout">結帳</button>
                        </td>
                    </tr>
                </form>

            </tbody>
        </table>

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
                });

                // 產品數量的--
                $('.table').on('click', 'button:nth-child(2)', function () {
                    let productcount = $(this).parents('.changecount').find('input').val();
                    if (productcount > 1) {
                        productcount--;
                        $(this).parents('.changecount').find('input').val(productcount);
                        amount();
                        updateCartItem($(this).parents('tr'));

                    }
                });


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

                //全選
                $('#checkAll').on('change', function () {
                    let checkall = $(this).is(':checked');
                    let input = $('#orderDetail').find('input');
                    if (checkall) {
                        input.prop('checked', true);
                    } else {
                        input.prop('checked', false);
                    }
                })


                //計算小計
                function amount() {
                    let total = 0
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
                    }).find('.total').text(total);
                };

                //是否勾選結帳項目
                $('#gocheckout').on('click', function () {
                    let isChecked = false;
                    $('input[type="checkbox"]').each(function () {
                        if ($(this).is(":checked")) {
                            isChecked = true;
                            return false; // 提前結束 each 迴圈
                        }
                    });

                    if (!isChecked) {
                        Swal.fire({
                            title: '你未選擇結帳項目!!',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                        }).then(function (event) {
                            event.preventDefault();
                        })
                    } else {
                        Swal.fire({
                            title: '確定前往結帳嗎?',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '確定'
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                document.querySelector('form').submit();
                            } else {
                                event.preventDefault();
                            }
                        })
                    }
                });

                // 刪除提示
                $('.delete').on('click', function () {
                    let delid = $(this).parents('tr').children('td:nth-child(1)').find('input:nth-child(2)').val();
                    Swal.fire({
                        title: '你要刪除嗎',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '刪除'
                    })
                        .then(function (result) {
                            if (result.value) {
                                Swal.fire("已刪除項目!!").then(function () {
                                    $.ajax({
                                        url: 'deletealert.php',
                                        method: 'GET',
                                        data: { 'remove': delid }
                                    }).done(function () {
                                        setTimeout(function () { location.reload(); }, 500);
                                    });
                                })
                            }
                        })
                });
            })
        </script>
    </body>

</html>