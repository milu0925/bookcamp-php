<?php
try {
    // 檢查登入者
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $pwd = $_POST['password'];
        $usersql = "SELECT * FROM client WHERE email = ? AND `passwd` = ?";
        $stmt = $pdo->prepare($usersql);
        $stmt->execute([$email, $pwd]);
        $row = $stmt->fetch();
        // 存入session

        $_SESSION['id'] = $row['client_id'];
        $_SESSION['name'] = $row['client_name'];
        $_SESSION['address'] = $row['client_address'];
        $_SESSION['phone'] = $row['phone'];
        $_SESSION['email'] = $row['email'];

        // 導回上一頁
        $previous_page = $_SERVER['HTTP_REFERER'];
        header("Location: $previous_page");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

    <div class="modal fade font3" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">模擬登入系統</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <?php if (isset($_SESSION['id'])): ?>
                                        <a class="nav-link" href="/phpProject/docs/login/logout.php?logout=true">
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
