            <div class="container">
                <div class="row">
                    <!-- LOGO跟標題 -->
                    <a href="#" class="d-flex p-3">
                        <div>
                            <img src="/img/LOGO.png" alt="" class="w-100">
                        </div>
                        <h1>書營</h1>
                    </a>
                    <!-- 這裡放導覽列 -->
                    <div class="">
                        <div class="d-flex hiddenme" >
                            <a href="/phpProject/docs/page/order.php" class="m-2"><span><i
                                        class="fa-solid fa-clipboard-list me-2"></i>訂單管理</span></a>
                            <a href="/phpProject/docs/product/product.php" class="m-2"><span><i
                                        class="fa-solid fa-book-tanakh me-2"></i>產品管理</span></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 使用者顯示 -->
            <div class="d-flex">
                <?php if (isset($_SESSION['id'])): ?>
                        <p>登入者：
                            <?php echo $_SESSION['name'] ?>
                        </p>
                        <a class="ps-2" href="./login/logout.php?logout=true">登出</a>
                <?php elseif (!isset($_SESSION['id'])): ?>
                        <p>
                            <buttom class="ps-5" data-bs-toggle="modal" data-bs-target="#exampleModal">登入</buttom>
                        </p>
                <?php endif; ?>
            </div>
