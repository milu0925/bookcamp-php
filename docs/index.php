<?php
require "./order/connSQL.php";
require "./order/CDN.php";
require "./allCDN.php";

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta name="description" content="NES.css is a NES-style CSS Framework." />
  <meta name="keywords" content="html5,css,framework,sass,NES,8bit" />
  <meta name="author" content="© 2018 B.C.Rikko" />
  <meta name="theme-color" content="#212529" />
  <link rel="shortcut icon" type="image/png" href="./favicon.png">
  <link rel="shortcut icon" sizes="196x196" href="./favicon.png">
  <link rel="apple-touch-icon" href="../favicon.png">

  <title>書營 Book Camp</title>

  <link href="https://unpkg.com/nes.css@latest/css/nes.min.css" rel="stylesheet" />
  <link href="./style.css" rel="stylesheet" />
  <script src="./lib/vue.min.js"></script>

  <script src="./lib/dialog-polyfill.js"></script>
  <script src="./lib/highlight.js"></script>

  <meta property="og:type" content="website" />
  <meta property="og:title" content="NES.css" />
  <meta property="og:url" content="https://nostalgic-css.github.io/NES.css/" />
  <meta property="og:description" content="NES-style CSS Framework | ファミコン風CSSフレームワーク" />
  <meta property="og:image"
    content="https://user-images.githubusercontent.com/5305599/49061716-da649680-f254-11e8-9a89-d95a7407ec6a.png" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="@bc_rikko" />
  <meta name="twitter:creator" content="@bc_rikko" />
  <meta name="twitter:image"
    content="https://user-images.githubusercontent.com/5305599/49061716-da649680-f254-11e8-9a89-d95a7407ec6a.png" />

  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-41640153-4"></script>
  <script>window.dataLayer = window.dataLayer || []; function gtag() { dataLayer.push(arguments); } gtag("js", new Date()); gtag("config", "UA-41640153-4");</script>
  <style>
    @font-face {
      font-family: 'CustomFont';
      src: url('../font/Cubic_11_1.010_R.ttf') format('truetype');
    }
  </style>
  <title>書營 Book Camp</title>
</head>

<body>
  <!-- 登入彈跳視窗 -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">模擬登入系統</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="./order/login/login.php">
            <?php if (isset($_SESSION['id'])): ?>
              <a class="nav-link" href="logout.php?logout=true">
                <i class="fa-regular fa-heart me-2"></i>登出系統
              </a>
            <?php else: ?>
              帳號：<input type="email" name="email">
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



  <div id="nescss">
    <!-- header : 顯示LOGO和登入者 -->
    <header :class="{ sticky: scrollPos > 10 }" class="d-flex justify-content-between">
      <div class="container">
        <div class="nav-brand row">
          <!-- LOGO跟標題 -->
          <a href="#" class="d-flex p-3">
            <div style="width: 50px;">
              <img src="../img/LOGO.png" alt="" class="w-100">
            </div>
            <h1 style="font-family: 'CustomFont'">書營</h1>
          </a>
          <!-- 這裡放導覽列 -->
          <div class="col-10">
            <div class="d-flex nes-text hiddenme" style="font-family: 'CustomFont';">
              <a href="../client/admin.php" class="nes-badge m-2"><span><i
                    class="fa-solid fa-users me-2"></i>會員管理</span></a>
              <a href="../order/order.php" class="nes-badge m-2"><span><i class="fa-solid fa-clipboard-list me-2"></i>訂單管理</span></a>
              <a href="#" class="nes-badge m-2"><span><i class="fa-solid fa-book-tanakh me-2"></i>新書管理</span></a>
              <a href="../secondhand_books/TTT.php" class="nes-badge m-2"><span><i
                    class="fa-solid fa-book-open me-2"></i>舊書管理</span></a>
              <a href="../Topic-Archives/coupon_index.php" class="nes-badge m-2"><span><i
                    class="fa-solid fa-ticket me-2"></i>優惠管理</span></a>
              <a href="../forum/page_Select.php" class="nes-badge m-2"><span><i
                    class="fa-solid fa-ghost me-2"></i>留言管理</span></a>
            </div>
          </div>
        </div>
      </div>
      <!-- 使用者顯示 -->
      <div class="d-flex social-buttons" style="font-family: 'CustomFont';">
        <?php if (isset($_SESSION['id'])): ?>
          <p>登入者：
            <?= $_SESSION['name'] ?>
          </p>
          <a class="ps-2 nes-text" href="./login/logout.php?logout=true">登出</a>
        <?php elseif (!isset($_SESSION['id'])): ?>
          <p>
            <buttom class="ps-5 nes-text" data-bs-toggle="modal" data-bs-target="#exampleModal">登入</buttom>
          </p>
        <?php endif; ?>
      </div>
    </header>

    <div class="container">
      <main class="main-content">
        <!-- 右側小人物 -->
        <a class="github-link" :class="{ active:  scrollPos < 200 }" target="_blank" rel="noopener"
          @mouseover="startAnimate" @mouseout="stopAnimate">
          <p class="nes-balloon from-right">Come in<br>BookCamp</p>
          <i class="nes-octocat" :class="animateOctocat ? 'animate' : ''"></i>
        </a>

        <!-- 大家的資料放這裡 -->




      </main>

      <!-- Copied balloon -->
      <div class="nes-balloon from-right copied-balloon" :style="copiedBalloon">
        <p>copied!!</p>
      </div>

      <!-- FAB Button -->
      <button type="button" class="nes-btn is-error scroll-btn" :class="{ active: scrollPos > 500 }"
        @click="window.scrollTo({ top:0, behavior: 'smooth' })"><span>&lt;</span></button>
    </div>
  </div>
</body>
<script src="./script.js"></script>
<script>
  const h = document.querySelector('head');
  ['./lib/dialog-polyfill.css', './lib/highlight-theme.css'].forEach(a => {
    const l = document.createElement('link');
    l.href = a;
    l.rel = 'stylesheet';
    h.appendChild(l);
  })
</script>
<script>
        $(function () {
            // 導覽列變色
            $('span').addClass('is-warning');
            $('span').on('mouseenter', function () {
                $(this).removeClass('is-warning');
                $(this).addClass('is-primary');
            });
            $('span').on('mouseleave', function () {
                $(this).removeClass('is-primary');
                $(this).addClass('is-warning');

            });
        })
    </script>
</html>