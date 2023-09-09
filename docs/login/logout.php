<?php
session_start(); // 確保啟用 SESSION 功能
if (isset($_GET["logout"]) || ($_GET["logout"] == "true")) {
    unset($_SESSION["id"]);
    $previous_page = $_SERVER['HTTP_REFERER'];
    header("Location: $previous_page");
    // echo "登出成功";
}
?>