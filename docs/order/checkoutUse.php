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
                    // 產品數量的--
                });
                $('.table').on('click', 'button:nth-child(2)', function () {
                    let productcount = $(this).parents('.changecount').find('input').val();
                    if (productcount > 1) {
                        productcount--;
                        $(this).parents('.changecount').find('input').val(productcount);
                        amount();
                        updateCartItem($(this).parents('tr'));

                    }
                });

                $('#gocheckout').on('click', function () {
                    // 將購物車資料送到伺服器端進行處理
                    $.ajax({
                        url: 'InsertOrder.php',
                        method: 'POST',
                        data: {
                            cartData: JSON.stringify(getCartData()) // 將購物車資料轉為 JSON 字串
                        }
                    });
                });

                // 取得購物車資料的函式
                function getCartData() {
                    var cartData = [];
                    $('.table>tbody>tr').each(function () {
                        var product = {
                            orderId: $(this).find('.oid').val(),
                            productId: $(this).find('.pid').val(),
                            productPrice: $(this).find('.pprice').val(),
                            productCount: $(this).find('.pcount').val()
                        };
                        cartData.push(product);
                    });
                    return cartData;
                }

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


    



                // 計算小計
                function amount() {
                    var total = 0;
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
                        // 計算運費
                        function fee(value) {
                            if (total > 3000) {
                                return value = $('.table>tbody>tr').find('#fee').text('0');
                            } else {
                                return value = $('.table>tbody>tr').find('#fee').text('60');
                            }
                        }
                        fee(total);
                        let feee =  $('.table>tbody>tr').find('#fee').text();
                        let result = parseInt(feee)+total;
                        $(this).find('.total').text(result);
                    })

          

                };
            });
        </script>
            </body>

</html>