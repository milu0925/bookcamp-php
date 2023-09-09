<?php
require "./connSQL.php";
require "./CDN.php";


$page = isset($_GET["page"]) ? $_GET["page"] : 1; //代表第幾頁
$showpage = 10; //每頁的數量
$startpage = ($page - 1) * $showpage;

$ordersql1 = "SELECT count(*) as totalpage FROM bookorder";
$stmt = $pdo->prepare($ordersql1);
$stmt->execute();
$totalrec = $stmt->fetchColumn(); //訂單總筆數
$totalpage = ceil($totalrec / $showpage); //計算分頁

$order = "SELECT * FROM `bookorder` LIMIT $startpage, $showpage ";
$sqlOrder = $pdo->prepare($order); //準備
try {
    $sqlOrder->execute(); //執行
    $roworder = $sqlOrder->fetchAll(); //取得結果
} catch (PDOException $e) { //例外
    die("Error!: " . $e->getMessage() . "<br/>"); //例外執行
}
;


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item" <?php if ($page == 1)
                    echo "disabled" ?>>
                    <a class="page-link" href="test.php?page=<?= ($page > 1) ? $page - 1 : 1; ?>"
                        aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                    <li class="page-item"> <a href="test.php?page=<?= $i ?>"
                            class="page-link <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                <?php endfor; ?>

                <li class="page-item" <?php if ($page == $totalpage)
                    echo "disabled" ?>>
                    <a class="page-link" href="test.php?page=<?= ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                        aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <table>
            <thead>
                <tr>
                    <th>訂單編號</th>
                    <th>會員編號</th>
                    <th>消費金額</th>
                    <th>創建日期</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($roworder as $key => $value): ?>
                    <tr class="text-center">
                        <td>
                            <?= $value["order_id"]; ?>
                        </td>
                        <td>
                            <?= $value["client_id"]; ?>
                        </td>
                        <td>
                            <?= $value["total"]; ?>
                        </td>
                        <td>
                            <?= $value["order_date"]; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>

</html>