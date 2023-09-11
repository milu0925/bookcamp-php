<script>
$(function () {

// 點選購按鈕
$('#list').on('click','button',function(){
    const row = ($(this).parents('div div .colProduct'));
    const img = row.children('img').attr("src");
    const itemid = row.children('.badge').text();
    const name = row.children('h5').text(); 
    const price = row.children('.d-flex').children('.fxl').text(); 
    //把資料帶入Modal中
    $('#carryItemData').children('div').children('img').attr("src",img);
    $('#carryItemData').children('div').find('#itemID').val(itemid);
    $('#carryItemData').children('div').find('.thisname').val(name);
    $('#carryItemData').children('div').find('.thisprice').val(price);
})


//傳送購買資料進自己的購物車
$('#join').off().on('click', function () {
    const product = {
        "client_id": $('#userID').val(),
        "book_id": $('#itemID').val(),
        "book_price": $('#itemPrice').val(),
        "book_count": $('#itemCount').val()
    };
    $.ajax({
        url: '../CRUD/CartInsert.php',
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
    $('#cart').modal('hide');
});

// 設定購買預設值
let nsd = 1;

// 選擇數量 計算最高可購買數量 和最小只能1
$('#buycount').on('click','button', function () {
    const xxx = $(this).attr('class');
    if (xxx == 'subcount' ) {
        if (nsd > 1) {
            nsd--;
        } else {
            nsd = 1;
        };
    } else {
        nsd++;
    }
    $('#itemCount').val(nsd);
});

// 關閉選購時，把數量清回預設值
$('#cart').on('hide.bs.modal', function () {
    nsd = 1
    $('#itemCount').val(nsd);
});


});
</script>
