<?php
require "../conn/CDN.php";
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <h2>jQuery 練習</h2>
                    <div class="ui-widget">
                        <label for="tags">Tags: </label>
                        <input id="tags">
                    </div>
                </div>
                <div class="col-2">
                </div>
            </div>
        </div>

        <script>
            $(function () {
                $("#tags").autocomplete({
                    source: 'search.php'});
            })
        </script>
    </body>

</html>