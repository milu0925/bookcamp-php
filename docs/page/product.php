<?php
require "../connSQL.php";
require '../CRUD/ProductRead.php';
require '../header.php';
?>
<body>
<!-- 登入以及登入彈跳視窗 -->
<?php require '../login/login.php'; ?>
<!-- 導覽列 -->
<?php require '../navbar.php'; ?>
<?php if (isset($_SESSION['id'])): ?>
    <div class="mx-4">
        <div id="list" class="row">
        <?php foreach ($rows as $value): ?>
            <div class="col-xl-2 col-md-3 col-sm-4 col-6">
                    <div class="position-relative colProduct d-flex flex-column justify-content-between">
                        <div class="position-absolute top-0 start-0 badge rounded-pill text-bg-success">
                            <?php echo $value["book_id"]; ?>
                        </div>
                        <img src="/phpProject/img/book/<?php echo $value["book_img_id"]; ?>" class="py-4 w-100" alt="book">
                        <h5 class="titleLimit"><b><?php echo $value["b_title"]; ?></b></h5>
                        <p class="textLimit"><?php echo $value["blurb"]; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="font1 fxl"><?php echo $value["book_price"]; ?><span class="fs">元</span></p>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#cart" class="okBtn-b">選購</button>
                        </div>
                    </div>
                </div>
        <?php endforeach; ?>
        </div>
    </div>
    <nav class="">
    <ul class="pagination justify-content-center">
        <li class="page-item PageNext" <?php if ($page == 1)
            echo "disabled" ?>>
            <a class="page-link" href="order.php?page=<?php echo ($page > 1) ? $page - 1 : 1; ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        </a>
        </li>

        <?php for ($i = ($page == $totalpage ? max(1, $page - 2) : max(1, $page - 1)); $i <= ($page == 1 ? min($totalpage, $page + 2) : min($totalpage, $page + 1)); $i++): ?>
            <li class="page-item PageNext">
            <a href="product.php?page=<?php echo $i ?>" class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <li class="page-item PageNext" <?php if ($page == $totalpage)
            echo "disabled" ?>>
            <a class="page-link"
            href="product.php?page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
        aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        </a>
        </li>
    </ul>
    </nav>
<?php endif; ?>

<!-- 加入購物車 -->
<div class="modal fade" id="cart" tabindex="-1" aria-labelledby="cartModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modalheader">
                <h4 class="modal-title font3" id="cartModal">選購產品</h4>
            </div>
            <div class="modal-body modalbody rounded-bottom-3">
                <form id="carryItemData" action="../CRUD/CartInsert.php" method="POST" class="row"> 
                    <div class="col-6 d-flex align-items-center">
                        <img src="" alt="img" class="w-100 p-3">
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="book_id" id="itemID">
                        <input type="hidden" name="book_price" id="itemPrice" class="thisprice">
                        <div class="productH">書名</div>
                        <input type="text" class="thisname productC font3" disabled>
                        <div class="productH">價格</div>
                        <input type="text" class="thisprice productC font3" disabled>
                        <div class="productH">數量</div>
                        <div id="buycount" class="d-flex align-items-center justify-content-around productC">
                            <button type='button' class="subcount">
                            <img src='/phpProject/img/left-arrow.png' class='iconWidth' />
                            </button>
                            <input type="text" id="itemCount" class="addinput font1 fl" name="book_count" value="1">
                            <button type='button' class="addcount">
                                <img src='/phpProject/img/right-arrow.png' class='iconWidth' />
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-around ">
                        <button id="join" class="okBtn-y" type="button">確認</button>
                        <input type="hidden" name="client_id" id="userID" value="<?= $_SESSION['id'] ?>">
                        <button type="button" class="okBtn-r" data-bs-dismiss="modal">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require '../script/productUse.php'; ?>
</body>
</html>
