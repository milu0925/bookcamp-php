<script>
        $(function () {
            $('#checkAll').on('change', function () {
                let checkall = $(this).is(':checked');
                let input = $('#orderDetail').find('input');
                if (checkall) {
                    input.prop('checked', true);
                } else {
                    input.prop('checked', false);
                }
            })
            // 轉換
            let flag = true;
            $('.changeOrderDetailIcon').on('click', function () {
                if (flag) {
                    $(this).html(`<i class="mt-3 fa-solid fa-angles-down fa-lg fa-rotate-180" style="color: #cabf44;"></i>`);
                    flag = false;
                } else {
                    $(this).html(`<i class="mt-3 fa-solid fa-angles-down fa-lg" style="color: #cabf44;"></i>`);
                    flag = true;
                }
            })

            $('#statusbatch').on('submit', function (event) {
                let opval = $(this).find('select[name=batch_status]').val();  //要改的狀態
               let checked = $('#orderDetail .row').find('input[type=checkbox]:checked');
                if (opval === null) {
                    event.preventDefault();
                    alert("請選擇");

                }
                else if (checked.length === 0) {
                    event.preventDefault();
                    alert("請選擇選項");
                }

            });





        });
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
        });
    </script>

