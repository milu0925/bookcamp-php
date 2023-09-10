<?php
require '../connSQL.php';

// 分頁
$page = isset($_GET["page"]) ? $_GET["page"] : 1; //代表第幾頁
$showpage = 10; //每頁的數量
$startpage = ($page - 1) * $showpage;

$booktotalsql = "SELECT count(*) as totalpage FROM `book`";
$stmt = $pdo->prepare($booktotalsql);
$stmt->execute();
$totalrec = $stmt->fetchColumn(); //訂單總筆數
$totalpage = ceil($totalrec / $showpage); //計算分頁

$booksql = "SELECT * FROM `book` ORDER BY book_id ASC LIMIT $startpage, $showpage ";
$book = $pdo->prepare($booksql); //準備
try {
    $book->execute(); //執行
    $rows = $book->fetchAll(); //取得結果

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) { //例外
    die("Error!: " . $e->getMessage() . "<br/>"); //例外執行
}

?>