<?php
    require "php/DataBaseHandling.php";

    if (isset($_POST["data"]) && !empty($_POST["data"])) {
        $link = $_POST['data'];
        $conn = openConnection();
        $users = countTotalUsersFromSessions($conn,$link);
    }
?>
<HTML>
    <head>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
            <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
            <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css" />
            <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js"></script> 
            <link rel="stylesheet" type="text/css" href="css/dashboard.css">

    </head>
    <body>
        <div class="jumbotron">
            <div class="container">
                <h1 class="text-center"><?php echo $users ?></h1>
                <h3 class="text-center">Online</h3>
                <h3 class="text-center"><?php echo $users * 1000?> attacks per minute</h3>
            </div>
        </div>
        <div class="container">
            <div class="row" id="row">
                
                <?php
                    if (isset($_POST["data"]) && !empty($_POST["data"])) {
                        $link = $_POST['data'];
                        $conn = openConnection();
                        countNumOfUsersPerCountryFromSessions($conn,$link);
                    }
                    else{  
                    header("Location: http://stme.esy.es/analytics.html");
                    die();
                    }
                ?>
               
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <button type="button" class="btn btn-primary btn-lg btn-block" id="button">Refresh</button>
            </div>
        </footer>
    </body>
</HTML>