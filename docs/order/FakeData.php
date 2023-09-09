<?php
require_once "Sql_order.php";
$pdo->exec("TRUNCATE TABLE `bookorder`");
$pdo->exec("TRUNCATE TABLE `bookorder_detail`");
$client_id = rand(1, 20); // 使用者的數量
$order_date = getdate(); // 訂單時間
$order_status_id = rand(1, 3); // 訂單狀態
$coupon_id = '1'; // 優惠卷
$delivery_id = rand(1, 3); // 配送方式
$receipt_id = rand(1, 3); // 發票
$pay_id = rand(1, 4); // 付款方式
$delivery_fee = 0;
$consignee_address_list = [
    // 地址的數量
    '436 臺中市清水區民有路25號',
    '414 臺中市烏日區三榮五路3號',
    '407 臺中市西屯區國安二路9號',
    '401 臺中市東區十甲東二街9號',
    '231 新北市新店區自強路28號',
    '221 新北市汐止區小坑路25號',
    '242 新北市新莊區壽山路26號',
    '224 新北市瑞芳區楓子瀨路22號',
    '722 臺南市佳里區祥和五街10號',
    '726 臺南市學甲區宅子港31號',
    '946 屏東縣恆春鎮梹榔路9號',
    '940 屏東縣枋寮鄉新開六路15號',
    '931 屏東縣佳冬鄉官埔31號',
    '324 桃園市平鎮區新貴北街13號',
    '320 桃園市中壢區月眉二路26號',
    '847 高雄市甲仙區中園路19號',
    '812 高雄市小港區永順街7號'
];
$product_count = 10; // 訂單中的產品數量
$maxOrderIdStmt = $pdo->query("SELECT MAX(`order_id`) FROM `bookorder`");
$maxOrderId = $maxOrderIdStmt->fetchColumn();
for ($order_id = $maxOrderId + 1; $order_id <= $maxOrderId + 50; $order_id++) {
    // 生成訂單資料
    $consignee = getchar(3);
    $consignee_phone = generateRandomPhoneNumber();
    $consignee_address = $consignee_address_list[rand(0, count($consignee_address_list) - 1)];
    $order_date = date('Y-m-d H:i:s');
    $order_status_id = rand(1, 3);
    $client_id = rand(1, 20);
    $coupon_id = 1;
    $delivery_id = rand(1, 3);
    $receipt_id = rand(1, 3);
    $pay_id = rand(1, 4);
    $total = 0; // 訂單總金額
    $delivery_fee = 0; // 運費

    // 插入訂單資料
    $insertOrderStmt = $pdo->prepare("INSERT INTO `bookorder`(`order_id`, `consignee`, `consignee_phone`, `consignee_address`, `order_date`, `order_status_id`, `client_id`, `coupon_id`, `delivery_id`, `receipt_id`, `pay_id`, `total`, `delivery_fee`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $insertOrderStmt->execute([$order_id, $consignee, $consignee_phone, $consignee_address, $order_date, $order_status_id, $client_id, $coupon_id, $delivery_id, $receipt_id, $pay_id, $total, $delivery_fee]);
    $insertOrderDetailStmt = $pdo->prepare("INSERT INTO `bookorder_detail`(`order_id`, `product_id`, `product_count`, `product_price`, `orderdetail_id`, `client_id`) VALUES (?, ?, ?, ?, ?, ?)");

    // 生成訂單明細資料
    for ($i = 0; $i < $product_count; $i++) {
        $orderdetail_id = (($order_id - 1) * $product_count) + $i + 1 + ($maxOrderId * $product_count) + 1;
        $product_id = rand(1, 16);
        $product_price = 1;
        $product_quantity = rand(1, 10); // 將原先的 $product_count 更改為 $product_quantity

        $productPriceStmt = $pdo->prepare("SELECT `product_price` FROM `product` WHERE `product_id` = ?");
        $productPriceStmt->execute([$product_id]);
        $product_price = $productPriceStmt->fetchColumn();

        // 插入訂單明細資料
        $insertOrderDetailStmt->execute([$order_id, $product_id, $product_count, $product_price, $orderdetail_id, $client_id]);

        // 計算訂單總金額
        $total += $product_price * $product_count;
    }

    // 更新訂單總金額
    $updateTotalStmt = $pdo->prepare("UPDATE `bookorder` SET `total` = ? WHERE `order_id` = ?");
    $updateTotalStmt->execute([$total, $order_id]);
}
echo "假資料生成完成！";

function getchar($num) //隨機名稱
{
    $b = '';
    for ($i = 0; $i < $num; $i++) {
        $a = chr(mt_rand(0xB0, 0xD0)) . chr(mt_rand(0xA1, 0xF0));
        $b .= iconv('GB2312', 'UTF-8', $a);
    }
    return $b;
}

function generateRandomPhoneNumber() //隨機電話
{
    $prefix = '09';
    $length = 8; // 長度

    $randomNumber = '';
    for ($i = 0; $i < $length; $i++) {
        $randomNumber .= mt_rand(0, 9);
    }

    return $prefix . $randomNumber;
}
?>