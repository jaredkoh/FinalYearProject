<?php
    require "php/DataBaseHandling.php";
    session_start();

    $link = "";
    
    if (isset($_POST["data"]) && !empty($_POST["data"])) {
        $link = $_POST['data'];
        $_SESSION["data"] = $link;
    }
    else{
        $link = $_SESSION["data"];
    }
     $conn = openConnection();
     $users = countTotalUsersFromSessions($conn,$link);
?>
<HTML>
    <head>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
            <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
            <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css" />
            <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js"></script> 
            <link rel="stylesheet" type="text/css" href="css/dashboard.css">
          <script type="text/javascript">$( document ).ready(function() {
                $('.count').each(function () {
            $(this).prop('Counter',0).animate({
                Counter: $(this).text()
            }, {
                duration: 4000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                    }
                });
            });
    
            });
        </script>

    </head>
    <body>
        <div class="jumbotron">
            <div class="container">
                  <div class="row" id="total">
                      <div class="col-md-6 text-center">
                          <h1 class="text-center count"><?php echo $users ?></h1>
                          <h3 class="text-center">Online</h3>
                      </div>
               
                      <div class="col-md-6 text-center">
                          <h1 class="text-center count"><?php echo $users * 1000; ?></h1>
                          <h3 class="text-center">attacks per minute</h3>
                      </div>
                    </div>
            </div>
        </div>
        <div class="container">
            <div class="row" id="users">
                
                <?php
                        countNumOfUsersPerCountryFromSessions($conn,$link);
                ?>
               
            </div>
        </div>
        <footer class="footer">
                <h3 class="text-center text-muted">Refresh the page to get the most updated analytics</h3>
        </footer>
      
    </body>
</HTML>