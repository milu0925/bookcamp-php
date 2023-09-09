<?php
require "./connSQL.php";
require "./CDN.php";
require "../allCDN.php";

/* 這裡是訂單 */

// 抓到mySQL-order
$order = "SELECT * FROM `order`";
$sqlOrder = $pdo->prepare($order); //準備

try {
    $sqlOrder->execute(); //執行
    $roworder = $sqlOrder->fetchAll(); //取得結果
} catch (PDOException $e) { //例外
    die("Error!: " . $e->getMessage() . "<br/>"); //例外執行
}
;


// 抓到mySQL-product
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


// 分頁
$page = isset($_GET["page"]) ? $_GET["page"] : 1; //代表第幾頁
$showpage = 10; //每頁的數量
$startpage = ($page - 1) * $showpage;

$ordersql1 = "SELECT count(*) as totalpage FROM `order`";
$stmt = $pdo->prepare($ordersql1);
$stmt->execute();
$totalrec = $stmt->fetchColumn(); //訂單總筆數
$totalpage = ceil($totalrec / $showpage); //計算分頁

//依照判斷去印出顯示的資料
//假如有抓到日期選擇的form
if (isset($_GET['createDate'])) {

    $date = $_GET['createDate'];

    $datesearch = "SELECT * FROM `order` WHERE DATE(order_date)=? ORDER BY order_date LIMIT $startpage, $showpage ;";
    $sqldateSearch = $pdo->prepare($datesearch);
    try {
        $sqldateSearch->execute([$date]);
        $row = $sqldateSearch->fetchAll();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    // 抓到設定最大值和最小值
} elseif (isset($_GET['minmoney']) && isset($_GET['maxmoney'])) {

    $min = $_GET['minmoney'];
    $max = $_GET['maxmoney'];

    if ($max == "") {
        $max = 999999999999;
    }
    $money = "SELECT * FROM `order` WHERE total BETWEEN :min AND :max ORDER BY total LIMIT $startpage, $showpage ;";
    $sqlmoney = $pdo->prepare($money);

    try {
        $sqlmoney->bindParam(':min', $min);
        $sqlmoney->bindParam(':max', $max);
        $sqlmoney->execute();
        $row = $sqlmoney->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    // 訂單編號排序
} elseif (isset($_GET['idSort'])) {
    $idSort = $_GET['idSort'];
    switch ($idSort) {
        case "idDESC":
            $sqlsort = "SELECT * FROM `order` ORDER BY order_id DESC LIMIT $startpage, $showpage ;";
            break;
        case "idASC":
            $sqlsort = "SELECT * FROM rder ORDER BY order_id ASC LIMIT $startpage, $showpage ;";
            break;
    }
    $sort = $pdo->prepare($sqlsort);

    try {
        $sort->execute();
        $row = $sort->fetchAll();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }

} elseif (isset($_GET['keyword'])) {
    $stmt = $_GET['keyword'];

    $word =
        "SELECT *
        FROM `order`
        WHERE (
            EXISTS (
                SELECT 1
                FROM pay
                WHERE pay_name = :num1
                AND pay_id = order.pay_id
            )
            OR EXISTS (
                SELECT 1
                FROM delivery
                WHERE delivery_name = :num2
                AND delivery_id = order.delivery_id
            )
            OR EXISTS (
                SELECT 1
                FROM receipt
                WHERE receipt_name = :num3
                AND receipt_id = order.receipt_id
            )
            OR EXISTS (
                SELECT 1
                FROM order_status
                WHERE order_status_name = :num4
                AND order_status_id = order.order_status_id
            )
)
ORDER BY order_id DESC LIMIT $startpage, $showpage;";
    $sqlkeyword = $pdo->prepare($word);
    try {
        $sqlkeyword->bindParam(":num1", $stmt);
        $sqlkeyword->bindParam(":num2", $stmt);
        $sqlkeyword->bindParam(":num3", $stmt);
        $sqlkeyword->bindParam(":num4", $stmt);
        $sqlkeyword->execute();
        $row = $sqlkeyword->fetchAll();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }


} else {
    $order = "SELECT * FROM `order` LIMIT $startpage, $showpage ";
    $sqlOrder = $pdo->prepare($order); //準備
    try {
        $sqlOrder->execute(); //執行
        $row = $sqlOrder->fetchAll(); //取得結果
    } catch (PDOException $e) { //例外
        die("Error!: " . $e->getMessage() . "<br/>"); //例外執行
    }
}
;

?>


<!DOCTYPE html>
<html>

<head>
    <style>
        .select1 {
            position: relative;
        }

        .select2 {
            /* display: none; */
            position: absolute;
            left: 0;
            top: 0;
            z-index: 5;

        }

        table {
            font-family: 'CustomFont';
        }

        button {
            font-family: "Press Start 2P";
        }
    </style>
    <title>訂單資料管理</title>
</head>


<body>
    <!-- 登入彈跳視窗 -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="font-family: 'CustomFont';">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">模擬登入系統</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="./login/login.php">
                        <?php if (isset($_SESSION['id'])): ?>
                            <a class="nav-link" href="./login/logout.php?logout=true">
                                <i class="fa-regular fa-heart me-2"></i>登出系統
                            </a>
                        <?php else: ?>
                            帳號：<input type="text" name="email">
                            <br>
                            密碼：<input type="password" name="password">
                            <br>
                            <input type="submit" class="mt-2 btn btn-outline-primary" value="登入"></input>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div id="nescss">
        <!-- header : 顯示LOGO和登入者 -->
        <header :class="{ sticky: scrollPos > 10 }" class="d-flex justify-content-between">
            <div class="container">
                <div class="nav-brand row">
                    <!-- LOGO跟標題 -->
                    <a href="#" class="d-flex p-3">
                        <div style="width: 50px;">
                            <img src="../../img/LOGO.png" alt="" class="w-100">
                        </div>
                        <h1 style="font-family: 'CustomFont'">書營</h1>
                    </a>
                    <!-- 這裡放導覽列 -->
                    <div class="col-10">
                        <div class="d-flex nes-text hiddenme" style="font-family: 'CustomFont';">
                            <a href="../client/admin.php" class="nes-badge m-2"><span><i
                                        class="fa-solid fa-users me-2"></i>會員管理</span></a>
                            <a href="#" class="nes-badge m-2"><span><i
                                        class="fa-solid fa-clipboard-list me-2"></i>訂單管理</span></a>
                            <a href="../product_book/productRead.php" class="nes-badge m-2"><span><i
                                        class="fa-solid fa-book-tanakh me-2"></i>新書管理</span></a>
                            <a href="../secondhand_books/TTT.php" class="nes-badge m-2"><span><i
                                        class="fa-solid fa-book-open me-2"></i>舊書管理</span></a>
                            <a href="../Topic-Archives/coupon_index.php" class="nes-badge m-2"><span><i
                                        class="fa-solid fa-ticket me-2"></i>優惠管理</span></a>
                            <a href="../forum/page_Select.php" class="nes-badge m-2"><span><i
                                        class="fa-solid fa-ghost me-2"></i>留言管理</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 使用者顯示 -->
            <div class="d-flex social-buttons" style="font-family: 'CustomFont';">
                <?php if (isset($_SESSION['id'])): ?>
                    <p>登入者：
                        <?= $_SESSION['name'] ?>
                    </p>
                    <a class="ps-2 nes-text" href="./login/logout.php?logout=true">登出</a>
                <?php elseif (!isset($_SESSION['id'])): ?>
                    <p>
                        <buttom class="ps-5 nes-text" data-bs-toggle="modal" data-bs-target="#exampleModal">登入</buttom>
                    </p>
                <?php endif; ?>
            </div>

        </header>


        <main class="main-content">
            <!-- 右側小人物 -->
            <a class="github-link" :class="{ active:  scrollPos < 50 }" target="_blank" rel="noopener"
                @mouseover="startAnimate" @mouseout="stopAnimate">
                <p class="nes-balloon from-right">Come in<br>BookCamp</p>
                <i class="nes-octocat" :class="animateOctocat ? 'animate' : ''"></i>
            </a>
            <div style="margin-top:130px;"></div>



            <?php if (isset($_SESSION['id'])): ?>
                <!-- 大家的資料放這裡 -->
                <!-- order start -->
                <!-- 所有功能列 -->
                <div class="d-flex pt-5">
                    <a href="./order.php" class="nes-btn is-normal m-2">HOME</a>
                    <a href="./product/product.php" class="nes-btn is-primary m-2">Product</a>
                </div>

                <!-- 搜尋列 -->
                <div class="row">
                    <!-- 修改-訂單狀態 -->
                    <div class="col-3">
                        <div class="nes-text is-primary">Status</div>
                        <form action="update.php" method="post" id="statusbatch">
                            <div class="d-flex">
                                <div class="nes-select is-warning">
                                    <select name="batch_status" id="default_select" style="font-family: 'CustomFont';">
                                        <option disabled selected>選擇更改訂單狀態</option>
                                        <?php foreach ($roworderStatus as $status): ?>
                                            <option value="<?= $status["order_status_id"]; ?>"><?= $status["order_status_name"] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button class="nes-btn" type="submit">OK</button>
                            </div>
                        </form>
                    </div>
                    <!-- 尋找-消費金額 -->
                    <div class="col-3">
                        <div class="nes-text is-primary">TotalRange</div>
                        <form action="./order.php" method="get">
                            <div class="text-center d-flex nes-field is-inline" style="font-family: 'CustomFont';">
                                <input type="text" name="minmoney" id="inline_field" class="nes-input is-warning"
                                    placeholder="min">
                                <span class="d-flex align-items-center px-2">-</span>
                                <input type="text" name="maxmoney" id="inline_field" class="nes-input is-warning"
                                    placeholder="max">
                                <button class="nes-btn" type="submit">GO</button>
                            </div>
                        </form>
                    </div>
                    <!-- 尋找-訂單日期選擇 -->
                    <div class="col-3">
                        <div class="nes-text is-primary">CreateDate
                        </div>
                        <form action="./order.php" method="get" class="d-flex">
                            <div class="nes-field is-inline">
                                <input type="date" name="createDate" class="nes-input is-warning"
                                    style="font-family: 'CustomFont';">
                            </div>
                            <button class="nes-btn " type="submit">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </form>
                    </div>
                    <!-- 尋找-關鍵字 -->
                    <div class="col-3">
                        <div class="nes-text is-primary">Keyword</div>
                        <form action="order.php" method="get">
                            <div class="d-flex">
                                <div class="nes-select is-warning">
                                    <select name="keyword" id="keyword" style="font-family: 'CustomFont';">
                                        <option selected disabled>select</option>
                                        <optgroup label="付款方式" id="col1">
                                            <?php foreach ($rowPay as $value): ?>
                                                <option value="<?= $value['pay_status'] ?>">
                                                    <?= $value['pay_status'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="配送方式" id="col2">
                                            <?php foreach ($rowDelivery as $value): ?>
                                                <option value="<?= $value['delivery_status'] ?>">
                                                    <?= $value['delivery_status'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="發票方式" id="col3">
                                            <?php foreach ($rowReceipt as $value): ?>
                                                <option value="<?= $value['receipt_status'] ?>">
                                                    <?= $value['receipt_status'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="顯示狀態">
                                            <?php foreach ($roworderStatus as $value): ?>
                                                <option value="<?= $value['order_status_name'] ?>">
                                                    <?= $value['order_status_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    </select>
                                </div>
                                <button class="nes-btn" type="submit">
                                    <i class="fas fa-search fa-fw"></i>
                                </button>
                            </div>
                        </form>
                    </div>


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
                                        <a href="order.php?idSort=idDESC" class="btn btn-sm" role="button">
                                            <i class="fa-solid fa-caret-up"></i>
                                        </a>
                                    <?php elseif ($idSort === "idDESC"): ?>
                                        <a href="order.php?idSort=idASC" class=" btn btn-sm" role="button">
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
                                <td colspan="8" class="text-center" style="font-family: 'CustomFont';">目前沒有資料。</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($row as $key => $value): ?>
                                <tr class="text-center">
                                    <td>
                                        <input type="checkbox" value="<?= $value["order_id"] ?>" name="batch_id[]"
                                            form="statusbatch">
                                    </td>
                                    <td>
                                        <?= $value["order_id"]; ?>
                                    </td>
                                    <td>
                                        <?= $value["client_id"]; ?>
                                    </td>
                                    <td>
                                        <?= $value["total"]; ?>
                                    </td>
                                    <td style="font-family: 'CustomFont';">
                                        <?= $rowPay[$value["pay_id"] - 1]["pay_status"]; ?>
                                    </td>
                                    <td>
                                        <?= $value["order_date"]; ?>
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
                                            data-bs-target="#collapse<?= $key ?>" aria-controls="collapse<?= $key ?>">
                                            <i class="mt-3 fa-solid fa-angles-down fa-lg" style="color: #cabf44;"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8">
                                        <!-- 訂單顯示資料範圍 -->
                                        <div id="collapse<?= $key ?>" class="accordion-collapse collapse"
                                            data-bs-parent="#orderDetail">
                                            <div class="accordion-body">
                                                <!-- 訂單明細 -->
                                                <div class="row">
                                                    <div class="col-5 bg-light rounded-start-3">
                                                        <p>收貨人姓名：
                                                            <?= $value["consignee"]; ?>
                                                        </p>
                                                        <p>收貨人電話：
                                                            <?= $value["consignee_phone"]; ?>
                                                        </p>
                                                        <p>收貨人地址：
                                                            <?= substr($value["consignee_address"], 0, 9); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-5 bg-light rounded-end-3">
                                                        <p>配送方式：
                                                            <?= $rowDelivery[$value["delivery_id"] - 1]["delivery_status"]; ?>
                                                        </p>
                                                        <p>發票方式：
                                                            <?= $rowReceipt[$value["receipt_id"] - 1]["receipt_status"]; ?>
                                                        </p>
                                                        <p>　
                                                            <!-- 優惠卷： -->
                                                            <!-- <?= $rowCoupon[$value["coupon_id"] - 1]["coupon_name"]; ?> -->
                                                        </p>
                                                    </div>
                                                    <div class="col-2">
                                                        <div class="d-flex justify-content-center align-items-center w-100 h-100">
                                                            <a class="btn btn-outline-info"
                                                                href="order_detail.php?id=<?= $value["order_id"] ?>"><i
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

                <!-- 空格包 -->
                <div class="m-5"></div>

                <!-- 分頁包 -->
                <div>
                    <!-- 分頁+選擇金額 -->
                    <?php if (isset($page) && isset($_GET['minmoney']) || isset($_GET['maxmoney'])): ?>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <a class="page-link"
                                        href="order.php?minmoney=<?= $min ?>&maxmoney=<?= $max ?>&page=<?= ($page > 1) ? $page - 1 : 1; ?>"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                                    <li class="page-item"> <a
                                            href="order.php?minmoney=<?= $min ?>&maxmoney=<?= $max ?>&page=<?= $i ?>"
                                            class="page-link <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                                <?php endfor; ?>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="order.php?minmoney=<?= $min ?>&maxmoney=<?= $max ?>&page=<?= ($page < $totalpage) ? $page + 1 : $totalpage ?>"
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
                                        href="order.php?createDate=<?= $date ?>&page=<?= ($page > 1) ? $page - 1 : 1; ?>"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                                    <li class="page-item"> <a href="order.php?createDate=<?= $date ?>&page=<?= $i ?>"
                                            class="page-link <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                                <?php endfor; ?>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="order.php?createDate=<?= $date ?>&page=<?= ($page < $totalpage) ? $page + 1 : $totalpage ?>"
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
                    <?= (isset($_GET['idSort'])) ? "idDESC" : "idASC" ?>
                    &page=<?= ($page > 1) ? $page - 1 : 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                                    <li class="page-item"> <a
                                            href="order.php?idSort=<?= (isset($_GET['idSort'])) ? "idDESC" : "idASC" ?>&page=<?= $i ?>"
                                            class="page-link <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                                <?php endfor; ?>

                                <li class="page-item" <?php if ($page == $totalpage)
                                    echo "disabled" ?>>
                                    <a class="page-link"
                                        href="order.php?idSort=<?= (isset($_GET['idSort'])) ? "idDESC" : "idASC" ?>&page=<?= ($page < $totalpage) ? $page + 1 : $totalpage ?>"
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
                                        href="order.php?keyword=<?= $stmt ?>&page=<?= ($page > 1) ? $page - 1 : 1; ?>"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                                    <li class="page-item"> <a href="order.php?keyword=<?= $stmt ?>&page=<?= $i ?>"
                                            class="page-link <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                                <?php endfor; ?>

                                <li class="page-item" <?php if ($page == $totalpage)
                                    echo "disabled" ?>>
                                    <a class="page-link"
                                        href="order.php?keyword=<?= $stmt ?>&page=<?= ($page < $totalpage) ? $page + 1 : $totalpage ?>"
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
                                    <a class="page-link" href="order.php?page=<?= ($page > 1) ? $page - 1 : 1; ?>"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                                    <li class="page-item"> <a href="order.php?page=<?= $i ?>"
                                            class="page-link <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                                <?php endfor; ?>

                                <li class="page-item" <?php if ($page == $totalpage)
                                    echo "disabled" ?>>
                                    <a class="page-link"
                                        href="order.php?page=<?= ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                                        aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </main>
        <?php endif; ?>
        <!-- Copied balloon -->
        <div class="nes-balloon from-right copied-balloon" :style="copiedBalloon">
            <p>copied!!</p>
        </div>

        <!-- 回首頁 -->
        <button type="button" class="nes-btn is-error scroll-btn" :class="{ active: scrollPos > 200 }"
            @click="window.scrollTo({ top:0, behavior: 'smooth' })"><span>&lt;</span></button>
    </div>

    <script src="../script.js"></script>
    <script>
        $(function () {
            $('#checkAll').on('change', function () {
                let checkall = $(this).is(':checked');
                let input = $('#orderDetail').find('input');
                if (checkall) {
                    input.prop('checked', true);
                } else {
                    input.prop('checked', false);
                }
            })
            // 轉換
            let flag = true;
            $('.changeOrderDetailIcon').on('click', function () {
                if (flag) {
                    $(this).html(`<i class="mt-3 fa-solid fa-angles-down fa-lg fa-rotate-180" style="color: #cabf44;"></i>`);
                    flag = false;
                } else {
                    $(this).html(`<i class="mt-3 fa-solid fa-angles-down fa-lg" style="color: #cabf44;"></i>`);
                    flag = true;
                }
            })

            $('#statusbatch').on('submit', function (event) {
                var opval = $(this).find('select[name=batch_status]').val();
                var checked = $('#orderDetail>tr>td').children('input[type=checkbox]:checked');

                if (opval === null) {
                    event.preventDefault();
                    alert("請選擇");

                }
                else if (checked.length === 0) {
                    event.preventDefault();
                    alert("請選擇選項");
                }

            });





        });
    </script>
    <script>
        $(function () {
            // 導覽列變色
            $('span').addClass('is-warning');
            $('span').on('mouseenter', function () {
                $(this).removeClass('is-warning');
                $(this).addClass('is-primary');
            });
            $('span').on('mouseleave', function () {
                $(this).removeClass('is-primary');
                $(this).addClass('is-warning');

            });
        })
    </script>

</body>

</html>