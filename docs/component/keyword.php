<div class="col-3">
<div>Keyword</div>
<form method="get">
<div class="d-flex">
<div>
<select name="keyword" id="keyword">
<option selected disabled>select</option>
<optgroup label="付款方式" id="col1">
<?php foreach ($rowPay as $value): ?>
<option value="<?php echo $value['pay_name'] ?>">
<?php echo $value['pay_name'] ?>
</option>
<?php endforeach; ?>
</optgroup>
<optgroup label="配送方式" id="col2">
<?php foreach ($rowDelivery as $value): ?>
<option value="<?php echo $value['delivery_name'] ?>">
<?php echo $value['delivery_name'] ?>
</option>
<?php endforeach; ?>
</optgroup>
<optgroup label="發票方式" id="col3">
<?php foreach ($rowReceipt as $value): ?>
<option value="<?php echo $value['receipt_name'] ?>">
<?php echo $value['receipt_name'] ?>
</option>
<?php endforeach; ?>
</optgroup>
<optgroup label="顯示狀態">
<?php foreach ($roworderStatus as $value): ?>
<option value="<?php echo $value['order_status_name'] ?>">
<?php echo $value['order_status_name'] ?>
</option>
<?php endforeach; ?>
</optgroup>
</select>
</div>
<button type="submit">
<i class="fas fa-search fa-fw"></i>
</button>
</div>
</form>
</div>
</div>