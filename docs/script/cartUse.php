<script>
            $(function () {
                amount();
                // 產品數量的++
                $('#orderDetail').on('click', 'button:nth-child(1)', function () {
                    let productcount = $(this).parents('.changecount').find('input').val();
                    productcount++;
                    $(this).parents('.changecount').find('input').val(productcount);
                    amount();
                    updateCartItem($(this).parents('.newcount'));
                });
                // 產品數量的--
                $('#orderDetail').on('click', 'button:nth-child(2)', function () {
                    let productcount = $(this).parents('.changecount').find('input').val();
                    if (productcount > 1) {
                        productcount--;
                        $(this).parents('.changecount').find('input').val(productcount);
                        amount();
                        updateCartItem($(this).parents('.newcount'));
                    }
                });
                //傳到Cart更新資料庫
                function updateCartItem(row) {
                    var productId = row.children('td:nth-child(1)').find('input:nth-child(2)').val();
                    var count = parseInt(row.children('td:nth-child(4)').find('input').val());
                    $.ajax({
                        url: '/phpProject/docs/CRUD/CartUpdate.php',
                        method: 'POST',
                        data: {
                            pid: productId,
                            pcount: count,
                        }
                    });
                };

                //全選
                $('#checkAll').on('change', function () {
                    let checkall = $(this).is(':checked');
                    let input = $('#orderDetail').find('input');
                    if (checkall) {
                        input.prop('checked', true);
                    } else {
                        input.prop('checked', false);
                    }
                });


                //計算小計&總金額
                function amount() {
                    let total = 0
                    $('#orderDetail .row').each(function () {
                        // 先抓到產品價格和數量(並轉成INT)
                        let price = parseInt($(this).find('.pricebox .pprice').val());
                        let count = parseInt($(this).find('.countbox input').val());
                        let thisAmount = price*count;
                        // 把計算值打印在小計欄位
                        $(this).find('.amountbox').text(thisAmount);
                        // 計算總金額
                        if (!isNaN(thisAmount)) {
                            total +=thisAmount;
                        }
                    })
                    $('.totalbox').text(total);
                };

                // 阻擋未選擇商品
                $('#gocheckout').on('click', function () {
                    let isChecked = false;
                    $('input[type="checkbox"]').each(function () {
                        if ($(this).is(":checked")) {
                            isChecked = true;
                            return false; // 提前結束 each 迴圈
                        }
                    });
                    if (!isChecked) {
                        Swal.fire({
                            title: '你未選擇結帳項目!!',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                        }).then(function (event) {
                            event.preventDefault();
                        })
                    } else {
                        Swal.fire({
                            title: '確定前往結帳嗎?',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '前往',
                            cancelButtonText: '取消'
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                document.querySelector('form').submit();
                            } else {
                                event.preventDefault();
                            }
                        })
                    }
                });

                // 刪除提示
                $('.delete').on('click', function () {
                    let delid =$(this).parents('.row').children().find('.pid').val();
                    Swal.fire({
                        title: '確定不需要了嗎',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '確定',
                        cancelButtonText: '取消'
                    })
                        .then(function (result) {
                            if (result.value) {
                                Swal.fire("已刪除項目!!").then(function () {
                                    $.ajax({
                                        url: '/phpProject/docs/CRUD/CartDelete.php',
                                        method: 'GET',
                                        data: { 'remove': delid }
                                    }).done(()=>{
                                        setTimeout(function () { location.reload(); }, 500);
                                    });
                                })
                            }
                        })
                });
            });
        </script>
