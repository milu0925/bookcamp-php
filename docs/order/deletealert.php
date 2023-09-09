<?php
require './connSQL.php';

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
    
}

?>