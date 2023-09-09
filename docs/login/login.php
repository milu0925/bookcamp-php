<?php
require "../connSQL.php";

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $pwd = $_POST['password'];
    $usersql = "SELECT * FROM client WHERE account = ? AND `passwd` = ?";
    $stmt = $pdo->prepare($usersql);
    $stmt->execute([$email, $pwd]);
    $row = $stmt->fetch();


    session_start();
    $_SESSION['id'] = $row['client_id'];
    $_SESSION['name'] = $row['client_name'];
    $_SESSION['address'] = $row['address'];
    $_SESSION['phone'] = $row['phone'];
    $_SESSION['email'] = $row['email'];

    $previous_page = $_SERVER['HTTP_REFERER'];
    header("Location: $previous_page");

}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php if (isset($_SESSION['id'])): ?>
        <a class="nav-link" href="logout.php?logout=true">
            <i class="fa-regular fa-heart me-2"></i>登出系統
        </a>
    <?php else: ?>
        <h2>模擬登入系統</h2>
        <form method="post">
            <input type="email" name="email">
            <br>
            <input type="password" name="password">
            <br>
            <input type="submit"></input>
        </form>
    <?php endif; ?>

</body>

</html>