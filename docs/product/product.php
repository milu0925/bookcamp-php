<?php
require "../connSQL.php";
require "../CDN.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="description" content="NES.css is a NES-style CSS Framework." />
    <meta name="keywords" content="html5,css,framework,sass,NES,8bit" />
    <meta name="author" content="© 2018 B.C.Rikko" />
    <meta name="theme-color" content="#212529" />


    <title>書營 Book Camp</title>

    <link href="https://unpkg.com/nes.css@latest/css/nes.min.css" rel="stylesheet" />
    <link href="../../style.css" rel="stylesheet" />
    <script src="../../lib/vue.min.js"></script>

    <script src="../../lib/dialog-polyfill.js"></script>
    <script src="../../lib/highlight.js"></script>

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

    <title>模擬商品</title>
    <style>
        @font-face {
            font-family: 'CustomFont';
            src: url('../../../font/Cubic_11_1.010_R.ttf') format('truetype');
        }

        body {
            font-family: 'CustomFont';
        }
    </style>
</head>

<body>

    <!-- 模擬登入系統 -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="font-family: 'CustomFont';">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">模擬登入系統</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="../login/login.php">
                        <?php if (isset($_SESSION['id'])): ?>
                            <a class="nav-link" href="../login/logout.php?logout=true">
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




    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- 使用者顯示 -->
                <div class="d-flex justify-content-end social-buttons" style="font-family: 'CustomFont';">
                    <?php if (isset($_SESSION['id'])): ?>
                        <p>登入者：
                            <?= $_SESSION['name'] ?>
                        </p>
                        <a class="ps-2 nes-text" href="../login/logout.php?logout=true">登出</a>
                    <?php elseif (!isset($_SESSION['id'])): ?>
                        <p>
                            <buttom class="ps-5 nes-text" data-bs-toggle="modal" data-bs-target="#exampleModal">登入</buttom>
                        </p>
                    <?php endif; ?>
                </div>
                <h2>產品列表模擬展示</h2>
                <div class="d-flex justify-content-between">
                    <button id="buttonAdd" class="nes-btn is-primary" data-bs-toggle="modal"
                        data-bs-target="#addproduct">新增</button>
                    <div>
                        <a href="../Cart.php" class="nes-btn is-success mb-3">購物車</a>
                        <a class="nes-btn is-primary mb-3" href="../order.php">訂單管理</a>
                    </div>
                </div>
                <?php if (isset($_SESSION['id'])): ?>
                <table id="userTable" class="table">
                    <thead>
                        <tr>
                            <th>商品編號</th>
                            <th>商品名稱</th>
                            <th>商品價格</th>
                            <th>商品庫存</th>
                            <th>商品描述</th>
                            <th>管理介面</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <?php endif; ?>
            </div>

        </div>

        <div class="modal fade" id="addproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">商品</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="id">編號</label>
                                <input type="text" class="nes-input" id="idvalue" disabled>
                                <input type="hidden" class="nes-input" id="id">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="col-form-label">商品:</label>
                                <input type="text" class="nes-input" id="name">
                            </div>
                            <div class="mb-3">
                                <label for="price" class="col-form-label">價格:</label>
                                <input type="text" class="nes-input" id="price">
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="col-form-label">庫存:</label>
                                <input type="text" class="nes-input" id="quantity">
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="col-form-label">簡述:</label>
                                <input type="text" class="nes-input" id="comment">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="nes-btn" data-bs-dismiss="modal">關閉</button>
                        <button id="buttonUpdate" type="button" class="nes-btn is-primary">修改</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="joinproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">加入購物車</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- 加入購物車 -->
                        <form id="joinPForm" action="joinApi.php" method="POST">
                            <div class="mb-3">
                                <input type="hidden" class="nes-input" name="product_id" id="joinid">
                            </div>
                            <div class="mb-3">
                                <label for="joinname" class="col-form-label">商品:</label>
                                <input type="text" class="nes-input" disabled>
                                <input type="hidden" class="nes-input" name="product_name" id="joinname">
                            </div>
                            <div class="mb-3">
                                <label for="joinprice" class="col-form-label">價格:</label>
                                <input type="text" class="nes-input" style="font-family: 'Press Start 2P' ;" disabled>
                                <input type="hidden" class="nes-input" name="product_price" id="joinprice"=>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">選購數:</label>
                                <div class="input-group " id="buycount">
                                    <button type="button" class="nes-btn is-primary form-control">&lt;</button>
                                    <input type="text" class="text-center nes-input form-control" id="joincount"
                                        name="product_count" value="1" style="font-family: 'Press Start 2P' ;">
                                    <button type="button" class="nes-btn is-primary form-control">&gt;</button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" class="nes-input" name="client_id" id="userid"
                                    value="<?= $_SESSION['id'] ?>">
                                <button id="okjoin" type="button" class="nes-btn is-primary">確認</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




        </div>
        <script>
            $(function () {
                //編輯資料
                $('#userTable>tbody').on('click', 'button:nth-child(1)', function () {
                    const row = ($(this).parents('tr'));
                    const id = row.children('td:nth-child(1)').text(); //id
                    const name = row.children('td:nth-child(2)').text(); //product_name
                    const price = row.children('td:nth-child(3)').text(); //product_price
                    const quantity = row.children('td:nth-child(4)').text(); //product_quantity
                    const comment = row.children('td:nth-child(5)').text();//product_comment
                    //把修改的資料帶入到Modal中
                    $('#id').val(id);  //HTML的#id抓值，值是上面抓到的位置
                    $('#price').val(price);
                    $('#name').val(name);
                    $('#quantity').val(quantity);
                    $('#comment').val(comment);
                });

                //修改或新增資料
                $('#buttonUpdate').on('click', function () {
                    let id = $('#id').val();

                    const product = { "product_id": $('#id').val(),"product_img": $('#img').val(), "product_name": $('#name').val(), "product_price": $('#price').val(), "product_quantity": $('#quantity').val(), "product_comment": $('#comment').val() };

                    //新增
                    if (id === "") {

                        $.ajax({
                            url: 'insertApi.php',
                            type: 'POST',
                            data: product, //傳送回Server的資料
                            dataType: 'json'
                        }).done(function (data) {
                            if (data.success) {
                                alert("新增成功");
                                ShowProduct();
                            } else {
                                alert(data.errorMessage);
                            }
                        })
                        //修改
                    } else {
                        $.ajax({
                            url: 'editApi.php',
                            type: 'POST',
                            data: product,
                            dataType: 'json'
                        }).done(function (data) {
                            if (data.success) {
                                alert("修改成功");
                                ShowProduct();
                            } else {
                                alert(data.errorMessage);
                            }
                        })
                    };
                    $('#addprduct').modal('hide');
                });


                //刪除資料
                $('#userTable>tbody').on('click', 'button:nth-child(2)', function () {
                    const idx = $(this).parents('tr').children('td:nth-child(1)').text();
                    Swal.fire({
                        title: '你要刪除嗎',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '刪除'
                    }).then(function (result) {
                        if (result.value) {
                            $.ajax({
                                url: 'deleteApi.php',
                                type: 'GET',
                                data: { "pid": idx },  //選擇要刪除的ID就可以指定那欄位刪除
                                dataType: 'json'
                            }).done(function (data) {
                                if (data.success) {
                                    alert("刪除成功");
                                    ShowProduct();
                                } else {
                                    alert("刪除失敗");
                                }
                            })
                        }
                    })
                });


                //對加入按鈕 (加入時自動帶入資料)
                $('#userTable>tbody').on('click', 'button:nth-child(3)', function () {

                    const row = ($(this).parents('tr'));
                    const id = row.children('td:nth-child(1)').text(); //id
                    const name = row.children('td:nth-child(2)').text(); //product_name
                    const price = row.children('td:nth-child(3)').text(); //product_price

                    //把原本的資料 帶入到JoinModal中
                    $('#joinPForm').children('div:nth-child(1)').children('input').val(id);
                    $('#joinPForm').children('div:nth-child(2)').children('input').val(name);
                    $('#joinPForm').children('div:nth-child(3)').children('input').val(price);




                    //加入購物車-確認按鈕-回傳資料
                    $('#okjoin').off().on('click', function () {
                        const product = {
                            "client_id": $('#userid').val(),
                            "product_id": $('#joinid').val(),
                            "product_name": $('#joinname').val(),
                            "product_price": $('#joinprice').val(),
                            "product_count": $('#joincount').val()
                        };
                        $.ajax({
                            url: 'joinApi.php',
                            type: 'POST',
                            data: product,
                            dataType: 'json'
                        }).done(function (data) {
                            if (data.success) {
                                Swal.fire("加入購物車");
                            } else {
                                Swal.fire("加入失敗！");
                            }
                        })
                        $('#joinproduct').modal('hide');

                    });
                })


                // 抓到產品的庫存數
                let inventory;
                $("#userTable>tbody").on('click', 'tr>td>button:nth-child(3)', function () {
                    const number = $(this).parent().parent().find('td:nth-child(1)').text();
                    inventory = ($(`#userTable>tbody>tr:nth-child(${number})>td`).eq(3).text());
                })
                let nsd = 1;

                // 選擇數量 計算最高可購買數量 和最小只能1
                $('#buycount').on('click', 'button', function () {
                    let maxcount = inventory;
                    const xxx = $(this).text();
                    if (xxx == "<") {
                        if (nsd > 1) {
                            nsd--;
                            $('#joincount').val(nsd);
                        } else {
                            nsd = 1;
                            $('#joincount').val(nsd);
                        };
                    } else {
                        if (nsd < maxcount) {
                            nsd++;
                            $('#joincount').val(nsd);
                        } else {
                            nsd = maxcount;
                        }
                    };
                });







                //顯示資料
                function ShowProduct() {
                    $.ajax({
                        url: 'readApi.php',
                        type: 'POST',
                        dataType: 'json'
                    }).done(function (datas) {
                        const docFrag = $(document.createDocumentFragment());
                        $.each(datas, function (idx, product) {
                            const { product_id, product_name, product_price, product_quantity, product_comment } = product;
                            // 這裡抓到與資料庫位置相同的欄位
                            //用{}的意義是因為一整行是一個物件
                            const data = `
                       <tr>
                            <td style="font-family: 'Press Start 2P' ;">${product_id}</td>
                            <td>${product_name}</td>
                            <td style="font-family: 'Press Start 2P' ;">${product_price}</td>
                            <td style="font-family: 'Press Start 2P' ;">${product_quantity}</td>
                            <td>${product_comment}</td>
                            <td> 
                                <button class="nes-btn" data-bs-toggle="modal" data-bs-target="#addproduct">編輯</button>
                                <button class="nes-btn is-error">刪除</button>
                                <button type="button" class="nes-btn is-primary" data-bs-toggle="modal" data-bs-target="#joinproduct">加入</button>
                            </td>
                        </tr>
                    `
                            docFrag.append(data);
                            $number = product_id + 1;

                        })

                        $('#userTable>tbody').html(docFrag);
                    })

                };
                ShowProduct();




                //Modal隱藏
                $('#addproduct').on('hide.bs.modal', function () {
                    //清除所有input中的資料
                    $(this).find('input').val("");
                });
                $('#joinproduct').on('hide.bs.modal', function () {
                    nsd = 1
                    $('#joincount').val(nsd);
                });
                //Modal顯示
                $('#addproduct').on('shown.bs.modal', function () {
                    let idx = $('#id').val();
                    if (idx === "") {
                        $('#buttonUpdate').text("新增");
                        $('#idvalue').val($number);
                    } else {
                        $('#buttonUpdate').text("修改");
                        $('#idvalue').val(idx);

                    }
                });

            });








        </script>
        <script>
            if (window.navigator.userAgent.toLocaleLowerCase().indexOf('trident') !== -1) {
                window.alert('IE is not supported on this page.')
            }
        </script>

        <script>
            const h = document.querySelector('head');
            ['../../lib/dialog-polyfill.css', '../../lib/highlight-theme.css'].forEach(a => {
                const l = document.createElement('link');
                l.href = a;
                l.rel = 'stylesheet';
                h.appendChild(l);
            })
        </script>
</body>

</html>