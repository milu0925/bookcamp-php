<?php
if (isset($_GET["logout"]) || ($_GET["logout"] == "true")) {
    unset($_SESSION["id"]);
    $previous_page = $_SERVER['HTTP_REFERER'];
    header("Location: $previous_page");
}
?>