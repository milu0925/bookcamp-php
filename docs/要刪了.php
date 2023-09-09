<script>
            //幫每個刪除按鈕加上data-id
            $(document).ready(function () {
                $('.delete').each(function (index) {
                    $(this).attr('data-id', index + 1); // 加1是为了使id从1开始，而不是从0开始
                });
            });
        </script>