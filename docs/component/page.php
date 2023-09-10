       <!-- 分頁包 -->
    <div>
        <!-- 分頁+選擇金額 -->
        <?php if (isset($page) && isset($_GET['minmoney']) || isset($_GET['maxmoney'])): ?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link"
                            href="order.php?minmoney=<?php echo $min ?>&maxmoney=<?php echo $max ?>&page=<?php echo ($page > 1) ? $page - 1 : 1; ?>"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                        <li class="page-item"> <a
                        href="order.php?minmoney=<?php echo $min ?>&maxmoney=<?php echo $max ?>&page=<?php echo $i ?>"
                        class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>

                <li class="page-item">
                <a class="page-link"
                href="order.php?minmoney=<?php echo $min ?>&maxmoney=<?php echo $max ?>&page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
                </ul>
                </nav>
                <!-- 分頁+日期 -->
        <?php elseif (isset($page) && isset($_GET['createDate'])): ?>
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                <li class="page-item">
                <a class="page-link"
                href="order.php?createDate=<?php echo $date ?>&page=<?php echo ($page > 1) ? $page - 1 : 1; ?>"
                aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                </a>
                </li>

                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                        <li class="page-item"> <a href="order.php?createDate=<?php echo $date ?>&page=<?php echo $i ?>"
                        class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>

                <li class="page-item">
                <a class="page-link"
                href="order.php?createDate=<?php echo $date ?>&page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
                </ul>
                </nav>
                <!-- 分頁+訂單 -->
        <?php elseif (isset($page) && isset($_GET['idSort'])): ?>
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                <li class="page-item" <?php if ($page == 1)
                    echo "disabled" ?>>
                        <a class="page-link" href="order.php?idSort=
<?php echo (isset($_GET['idSort'])) ? "idDESC" : "idASC" ?>
&page=<?php echo ($page > 1) ? $page - 1 : 1; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                </a>
                </li>

                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                        <li class="page-item"> <a
                        href="order.php?idSort=<?php echo (isset($_GET['idSort'])) ? "idDESC" : "idASC" ?>&page=<?php echo $i ?>"
                        class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>

                <li class="page-item" <?php if ($page == $totalpage)
                    echo "disabled" ?>>
                        <a class="page-link"
                        href="order.php?idSort=<?php echo (isset($_GET['idSort'])) ? "idDESC" : "idASC" ?>&page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
                </ul>
                </nav>
                <!-- 分頁+關鍵字 -->
        <?php elseif (isset($page) && isset($_GET['keyword'])): ?>
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                <li class="page-item" <?php if ($page == 1)
                    echo "disabled" ?>>
                        <a class="page-link"
                        href="order.php?keyword=<?php echo $stmt ?>&page=<?php echo ($page > 1) ? $page - 1 : 1; ?>"
                aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                </a>
                </li>

                <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                        <li class="page-item"> <a href="order.php?keyword=<?php echo $stmt ?>&page=<?php echo $i ?>"
                        class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>

                <li class="page-item" <?php if ($page == $totalpage)
                    echo "disabled" ?>>
                        <a class="page-link"
                        href="order.php?keyword=<?php echo $stmt ?>&page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
                </ul>
                </nav>
        <?php else: ?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item" <?php if ($page == 1) echo "disabled" ?>>
                            <a class="page-link" href="order.php?page=<?php echo ($page > 1) ? $page - 1 : 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalpage; $i++): ?>
                            <li class="page-item">
                                <a href="order.php?page=<?php echo $i ?>" class="page-link <?php echo ($i == $page) ? 'active' : '' ?>"><?php echo $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item" <?php if ($page == $totalpage)
                            echo "disabled" ?>>
                                <a class="page-link"
                                href="order.php?page=<?php echo ($page < $totalpage) ? $page + 1 : $totalpage ?>"
                        aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        </a>
                        </li>
                    </ul>
                </nav>
        <?php endif; ?>
    </div>