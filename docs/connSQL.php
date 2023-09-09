<?php

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookcamp";

// PDO 連線設定
$pdoconn = [
    PDO::ATTR_PERSISTENT => false, //設定為長連接
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //錯誤訊息 => 拋出例外
    PDO::ATTR_EMULATE_PREPARES => false, //啟用或禁用預處理語句的類比。 有些驅動不支援或有限度地支援本地預處理。
    PDO::ATTR_STRINGIFY_FETCHES => false, //提取的時候將數值轉換為字串。
    PDO::ATTR_DEFAULT_FETCH_MODE=> PDO::FETCH_ASSOC //索引維結果集(原本是索引號)
];

//資料庫連線
try {
    $pdo = new PDO("mysql:host={$servername};dbname={$dbname};charset=utf8" , $username, $password, $pdoconn);

} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}

?>