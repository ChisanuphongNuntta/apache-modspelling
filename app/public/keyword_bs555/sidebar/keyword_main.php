<!DOCTYPE html>
<html lang="en">

<style>
    .select,
    #locale {
        width: 100%;
    }

    .like {
        margin-right: 10px;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <span class="text-mute" id="path_keyword"></span>
    <div id="content_keyword">
    </div>

</body>

</html>

<script>
    $('#path_keyword').html(" > Keyword Trend ");
    $("#content_keyword").load("sidebar/keyword_trend.php");
</script>