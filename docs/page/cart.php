<?php
require '../connSQL.php';
require '../header.php';

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
<body>
    <?php require '../login/login.php'; ?>
        <a class="btn btn-warning ms-2 mb-3" href="../product/product.php">產品模擬畫面</a>


        
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

        <?php require './cartUse.php'; ?>