<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keyword Trend</title>

    <?php
    include "config.html";
    ?>

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            font-size: 14px;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #252a2e;
            overflow-x: hidden;
            /* padding-top: 60px; */
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            color: #818181;
            display: block;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .topbar {
            height: 60px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 250px;
            right: 0;
            background-color: #15181a;
            color: white;
            padding: 15px;
        }
    </style>
</head>

<!-- <body data-bs-theme="dark"> -->

<body data-bs-theme="dark">
    <div class="sidebar shadow">
        <div class="text-white text-center py-3" >
            <span class="h4">Business555 co.,ltd.</span>
            <hr class="shadow">
        </div>
        <a href="#" id="home"><i class="fas fa-home"></i> Home</a>
        <a href="#" id="keywordTrend"><i class="fas fa-chart-line"></i> Keyword Trend</a>
        <a href="#"><i class="fas fa-briefcase"></i> Services</a>
        <a href="#"><i class="fas fa-users"></i> Clients</a>
        <a href="#"><i class="fas fa-envelope"></i> Contact</a>
    </div>


    <div class="topbar shadow">
        
    </div>

    <div style="margin-left: 250px; margin-top: 60px; padding: 10px;">
        <div id="content">
        </div>
    </div>

</body>

</html>

<script>
    $(document).ready(function () {

        $("#home").click(function (e) {
            e.preventDefault(); // prevent the default action of the link
            $.ajax({
                url: "sidebar/home.php", // path to your home page or any other content
                type: "GET",
                dataType: "html",
                success: function (response) {
                    $("#content").html(response); // load the response to the content div
                }
            });
        });

        $("#keywordTrend").click(function (e) {
            e.preventDefault(); // prevent the default action of the link
            $.ajax({
                url: "sidebar/keyword_main.php", // path to your home page or any other content
                type: "GET",
                dataType: "html",
                success: function (response) {
                    $("#content").html(response); // load the response to the content div
                }
            });
        });

    });
</script>