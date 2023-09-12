            <div class="container mb-5">
                <!-- LOGO跟標題 -->
                <a href="#" class="d-flex text-white font1">
                        <img src="/phpProject/img/LOGO.png" alt="LOGO" class="logoBox">
                    <h5 class="ps-2 fxl">BookCamp</h5>
                </a>
                <div class="d-flex justify-content-between">
                <!-- 這裡放導覽列 -->
                    <div class="d-flex text-white font3 line-absolute">
                        <a href="/phpProject/docs/page/order.php" class="m-2"><span>
                            <i class="fa-solid fa-clipboard-list me-2"></i>訂單管理</span></a>
                        <a href="/phpProject/docs/page/product.php" class="m-2"><span>
                            <i class="fa-solid fa-book-tanakh me-2"></i>產品管理</span></a>
                        <a href="/phpProject/docs/page/cart.php" class="m-2"><span>
                            <i class="fa-solid fa-cart-shopping me-2"></i>購物車</span></a>
                    </div>
                    <!-- 使用者顯示 -->
                    <div class="d-flex justify-content-center align-items-center text-white">
                        <?php if (isset($_SESSION['id'])): ?>
                        <p>登入者：
                            <?php echo $_SESSION['name'] ?>
                        </p>
                        <a class="ps-2" href="/phpProject/docs/login/logout.php?logout=true">登出</a>
                        <?php elseif (!isset($_SESSION['id'])): ?>
                        <buttom class="okBtn-g text-center" data-bs-toggle="modal" data-bs-target="#exampleModal">登入</buttom>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
