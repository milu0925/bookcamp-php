<?php
require "../connSQL.php";
require '../header.php';
?>
<body>
<!-- 登入以及登入彈跳視窗 -->
<?php require '../login/login.php'; ?>
<!-- 導覽列 -->
<?php require '../navbar.php'; ?>
            <?php if (isset($_SESSION['id'])): ?>
                <div class="row">
                    <div class="col-1">編號</div>
                    <div class="col-4">名稱</div>
                    <div class="col-1">價格</div>
                    <div class="col-4">描述</div>
                    <div class="col-2"></div>
                </div>
                <div id="list">
                </div>
            <?php endif; ?>

        <!-- 加入購物車 -->
        <div class="modal fade" id="cart" tabindex="-1" aria-labelledby="cartModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="cartModal">選購產品</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="carryItemData" action="../CRUD/CartInsert.php" method="POST">
                            <div>
                                <input type="hidden" name="book_id" id="itemID">
                            </div>
                            <div>
                                <label>商品:</label>
                                <input type="text" disabled>
                            </div>
                            <div>
                                <label for="itemPrice">價格:</label>
                                <input type="text" disabled>
                                <input type="hidden" name="book_price" id="itemPrice">
                            </div>
                            <div>
                                <label for="itemCount">選購數:</label>
                                <div id="buycount">
                                    <button type="button">&lt;</button>
                                    <input type="text" id="itemCount" name="book_count" value="1">
                                    <button type="button">&gt;</button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="client_id" id="userID" value="<?= $_SESSION['id'] ?>">
                                <button id="join" type="button">確認</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




        </div>

<?php require '../script/productUse.php'; ?>
</body>
</html>
