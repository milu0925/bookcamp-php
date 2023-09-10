<script>
$(function () {

//顯示產品資料
function ShowProduct() {
$.ajax({
    url: '/phpProject/docs/CRUD/ProductRead.php',
    type: 'POST',
    dataType: 'json'
}).done((datas) => {
    const docFrag = $(document.createDocumentFragment());
    $.each(datas, function (idx, product) {
        const { book_id, b_title, book_price, blurb } = product;
        // 這裡抓到與資料庫位置相同的欄位
        //用{}的意義是因為一整行是一個物件
        const data = `
        <div class="row">
            <div class="col-1">${book_id}</div>
            <div class="col-4">${b_title}</div>
            <div class="col-1">${book_price}</div>
            <div class="col-4">${blurb}</div>
            <div class="col-2"> 
                <button type="button" data-bs-toggle="modal" data-bs-target="#cart" >選購</button>
            </div>
        </div>`;
        docFrag.append(data);
        $number = book_id + 1;
    })
    $('#list').html(docFrag);
    })
};
ShowProduct();

// 點選購按鈕
$('#list').on('click','.row .col-2 button',function(){
    const row = ($(this).parents('.row'));
    const itemid = row.children('div:nth-child(1)').text();
    const name = row.children('div:nth-child(2)').text(); 
    const price = row.children('div:nth-child(3)').text(); 
    //把資料帶入Modal中
    $('#carryItemData').children('div:nth-child(1)').children('input').val(itemid);
    $('#carryItemData').children('div:nth-child(2)').children('input').val(name);
    $('#carryItemData').children('div:nth-child(3)').children('input').val(price);
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
$('#buycount').on('click', 'button', function () {
    const xxx = $(this).text();
    if (xxx == "<") {
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
