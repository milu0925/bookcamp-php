<?php
require '../connSQL.php';

if (isset($_GET["remove"])) {
    echo $_GET["remove"];
    $removeid = $_GET['remove'];
    $userid = $_SESSION['id'];
    $CartId = "DELETE FROM `cart` WHERE book_id = ? AND client_id = ?";
    $sqldel = $pdo->prepare($CartId);
    try {
        $sqldel->execute([$removeid,$userid]);
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "<br/>");
    }
    ;
}
?>
