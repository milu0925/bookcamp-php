<script>
            $(function () {

                amount();

                // 產品數量的++
                $('.table').on('click', 'button:nth-child(1)', function () {
                    let productcount = $(this).parents('.changecount').find('input').val();
                    productcount++;
                    $(this).parents('.changecount').find('input').val(productcount);
                    amount();
                    updateCartItem($(this).parents('tr'));
                });

                // 產品數量的--
                $('.table').on('click', 'button:nth-child(2)', function () {
                    let productcount = $(this).parents('.changecount').find('input').val();
                    if (productcount > 1) {
                        productcount--;
                        $(this).parents('.changecount').find('input').val(productcount);
                        amount();
                        updateCartItem($(this).parents('tr'));

                    }
                });


                //傳到Cart更新資料庫
                function updateCartItem(row) {
                    var productId = row.children('td:nth-child(1)').find('input:nth-child(2)').val();
                    var count = parseInt(row.children('td:nth-child(4)').find('input').val());
                    $.ajax({
                        url: 'UpdateCart.php',
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
                })


                //計算小計
                function amount() {
                    let total = 0
                    $('.table>tbody>tr').each(function () {
                        let input = $(this).find('td div input');

                        let allcount = parseInt(input.val());
                        let allprice = parseInt($(this).find('td:nth-child(3)').text());
                        let subtotal = allcount * allprice;
                        $(this).find('.new').text(subtotal);
                    });
                    $('.table>tbody>tr').each(function () {
                        let sum = parseInt($(this).find('.new').text());
                        if (!isNaN(sum)) {
                            total = total + sum;
                        }
                    }).find('.total').text(total);
                };

                //是否勾選結帳項目
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
                            confirmButtonText: '確定'
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
                    let delid = $(this).parents('tr').children('td:nth-child(1)').find('input:nth-child(2)').val();
                    Swal.fire({
                        title: '你要刪除嗎',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '刪除'
                    })
                        .then(function (result) {
                            if (result.value) {
                                Swal.fire("已刪除項目!!").then(function () {
                                    $.ajax({
                                        url: 'deletealert.php',
                                        method: 'GET',
                                        data: { 'remove': delid }
                                    }).done(function () {
                                        setTimeout(function () { location.reload(); }, 500);
                                    });
                                })
                            }
                        })
                });
            })
        </script>

        </body>

</html>