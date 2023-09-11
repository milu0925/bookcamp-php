<?php
require '../connSQL.php';

// 分頁
$page = isset($_GET["page"]) ? $_GET["page"] : 1; //代表第幾頁
$showpage = 12; //每頁的數量
$startpage = ($page - 1) * $showpage;
$totalrec;
$totalpage;
$pageView = 5 ; //最多可見頁數

$booksqlp = "SELECT * FROM `book`";
$bookp = $pdo->prepare($booksqlp);

$booksql = "SELECT * FROM `book` ORDER BY book_id ASC LIMIT $startpage, $showpage ";
$book = $pdo->prepare($booksql); //準備
try {
    $book->execute(); //執行
    $bookp->execute(); //執行
    $rows = $book->fetchAll(); //取得結果

    //分頁
    $totalrec = $bookp->rowCount(); 
    $totalpage = ceil($totalrec / $showpage);

} catch (PDOException $e) { //例外
    die("Error!: " . $e->getMessage() . "<br/>"); //例外執行
}

?>