<?php

// 變更訂單狀態
if (isset($_POST["batch_id"]) && isset($_POST["batch_status"])) {

    $batch_status = array($_POST["batch_status"]);
    $batch_id = $_POST["batch_id"];

    var_dump($batch_id); //表示多個選擇
    $batch_params = str_repeat('?,', count($batch_id) - 1) . '?';
    $sql = "UPDATE `order` SET order_status_id = ? WHERE order_id IN ($batch_params)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute(array_merge($batch_status, $batch_id)); //合併陣列(這邊用array_unshift會失敗)
        $rows = $stmt->fetchAll();
        // header("location: /phpProject/docs/page/order.php");
        echo "<script>window.location.href='/phpProject/docs/page/order.php';</script>";
    } catch (PDOException $e) {
        echo $e->getMessage();
        echo "something went wrong";
    }
}
?>

<!-- 修改-訂單狀態 -->
<div class="col-3">
<div>Status</div>
<form method="post" id="statusbatch">
<div class="d-flex align-items-center">
    <select name="batch_status" id="default_select" class="select-key mx-2">
    <option disabled selected>請選擇更改狀態</option>
    <?php foreach ($roworderStatus as $status): ?>
        <option value="<?php echo $status["order_status_id"]; ?>"><?php echo $status["order_status_name"] ?>
        </option>
    <?php endforeach; ?>
    </select>
<button type="submit" class="btn btn-outline-light">OK</button>
</div>
</form>
</div>