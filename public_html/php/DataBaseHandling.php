<?php
function openConnection(){
  //USERNAMES AND PASSWORDS FOR DATABASE
  $servername = "mysql.hostinger.co.uk";
  $username = "u854725684_user";
  $password = "rhydh2ZM3k";
  $dbname = "u854725684_data";
  //CONNECTING TO DATABASE
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

function selectDataFromDatabase($key ,$conn){
  $sql = "SELECT longlink FROM DATA WHERE shortlink='$key'";
  $result = $conn->query($sql);
  $arrayOfResults = mysqli_fetch_row($result);
  $link = $arrayOfResults[0];
  mysqli_free_result($result);

  return $link;
}

function selectArrayFromDatabase($key ,$conn){
  $arrayOfResult = array();
  $sql = "SELECT longlink FROM DATA WHERE shortlink='$key'";
  $result = $conn->query($sql);
  while ($row = mysqli_fetch_array($result)){
    $arrayOfResult[] = $row["longlink"];
  }
  mysqli_free_result($result);


  return $arrayOfResult;
}

function addDataToDatabase($key , $field , $conn){
  $sql = "INSERT INTO DATA (shortlink,longlink)
  VALUES ( '$key' , '$field')";
  if ($conn->query($sql) === TRUE) {
    //echo 'UPDATE ALL GOOD. Your new url is http://kclproject.esy.es/php/' .$key ;

  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

}

function addCoordinatesToDatabase($lat , $long , $conn){
  $sql = "INSERT INTO POSITION (lat,longi)
  VALUES ( '$lat' , '$long')";
  if ($conn->query($sql) === TRUE) {
    //echo 'UPDATE ALL GOOD. Your new url is http://kclproject.esy.es/php/' .$key ;

  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

}

function getMostRecentCoordinates($conn){
  $latLng = "";
  $sql="SELECT lat , longi FROM POSITION WHERE ID = (SELECT MAX(ID) FROM POSITION)";
  $result = $conn->query($sql);
  while ($row = mysqli_fetch_array($result)){
    $a = (string)$row['lat'];
    $b = (string)$row['longi'];
    $latLng = $a.','.$b;
  }

  mysqli_free_result($result);
  echo $latLng === "" ? "no suggestion" : $latLng;

}

function deleteAllFromDataBase(){
  $sql="DELETE FROM POSITION";
  if ($conn->query($sql) === TRUE) {
    //echo 'UPDATE ALL GOOD. Your new url is http://kclproject.esy.es/php/' .$key ;

  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

}

function closeConnection($conn){
  $conn->close();
}

function checkForDuplicateLinks($longlink , $conn){
  $check = "SELECT longlink FROM DATA";
  $result = $conn->query($check);
  $arrayOfResults = Array();
  //STORE IN PHP ARRAY
  while ($row = mysqli_fetch_assoc($result)){
    $arrayOfResults[] =  $row['longlink'];
  }
  //FREE RESULT SET
  mysqli_free_result($result);
  if(in_array($longlink, $arrayOfResults)){
    $num = array_search($longlink , $arrayOfResults);

    $key = getShortLinkFromRow($num,$conn);
    return $key;
  }
}
function getShortLinkFromRow($num,$conn){
  $check = "SELECT shortlink FROM DATA";
  $result = $conn->query($check);
  $arrayOfResults = Array();
  while ($row = mysqli_fetch_assoc($result)){
    $arrayOfResults[] =  $row['shortlink'];

  }

  $key = $arrayOfResults[$num];
  mysqli_free_result($result);

  return $key;
}
function checkForDuplicateKeys($key ,$conn){

  $check = "SELECT * FROM DATA WHERE shortlink='$key'";
  $result = $conn->query($check);
  if($result->numrows > 0){
    $key = generateKey(6 , $key);
    checkForDuplicateKeys($key , $conn);
  }
  else{
    return $key;
  }
}

function generateKey($length_of_key ,$key){
  $char = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $key = "";
  for ($i = 0 ; $i < $length_of_key-1 ; $i ++){
    $temp = rand(0, 61);
    $key .= $char[$temp];
  }
  return $key;
}

function generateCrypotgraphyKeys($bits){
  $ssl = openssl_pkey_new(array('private_key_bits' => $bits));
  return $ssl;

}

function addtoNewsDatabase($conn , $title , $imgsrc , $news, $header){
      $sql = "INSERT INTO NEWS (title,imgsrc,news,header)
  VALUES ( '$title' , '$imgsrc' , '$news', '$header')";
  if ($conn->query($sql) === TRUE) {
    //echo 'UPDATE ALL GOOD. Your new url is http://kclproject.esy.es/php/' .$key ;

  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

}

function addSessionToDatabase($conn,$ip,$city,$country,$countrycode,$link){
    $sql = ("INSERT INTO SESSIONS (ip,city,country,countrycode,link)
					VALUES('$ip','$city','$country','$countrycode','$link')");
      if ($conn->query($sql) === TRUE) {
  //  echo 'UPDATE ALL GOOD';
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
    
}

function updateSessionFromDatabase($conn,$ip , $link){
    $check ="UPDATE SESSIONS SET dt=NOW() WHERE ip='$ip'AND link='$link'";
    if ($conn->query($check) === TRUE) {
    //echo 'UPDATE ALL GOOD. Your new url is http://kclproject.esy.es/php/' .$key ;

    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
    
}

function deleteSessionFromDatabase($conn){
    $sql = ("DELETE FROM SESSIONS WHERE dt<SUBTIME(NOW(),'0 0:10:0')");
     if ($conn->query($sql) === TRUE) {
    //echo 'UPDATE ALL GOOD. Your new url is http://kclproject.esy.es/php/' .$key ;

    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

function checkForDuplicateSessions($conn,$ip,$link){
    $check = "SELECT 1 FROM SESSIONS WHERE ip='$ip'AND link='$link'";
    
    $inDB = $conn->query($check);
    if(!mysqli_num_rows($inDB)>0){
        
        return false;
          mysqli_free_result($inDB);

    }
    else{
        return true;
          mysqli_free_result($inDB);

    }
    
}

function countNumOfUsersPerCountryFromSessions($conn , $link){
    $userPerCountry = "SELECT countryCode,country, COUNT(*) AS total
						FROM SESSIONS WHERE link='$link'
						GROUP BY countryCode
						ORDER BY total DESC
						LIMIT 15";
    
    
    $resultForPerCountry = $conn->query($userPerCountry);
    
    if ($resultForPerCountry){
       
        while($row=mysqli_fetch_assoc($resultForPerCountry))
        {
        echo '  
                <div class="col-md-4">
                        <h1 class="text-center">'.$row['total'].'</h1>
                        <h3 class="text-center">'.htmlspecialchars($row['country']).'</h3>
                    </div>
            ';
        }
    }
}

function countTotalUsersFromSessions($conn,$link){
    $totalNumOfUsers = "SELECT COUNT(*) FROM SESSIONS WHERE link='$link'";
       $resultForTotal = $conn->query($totalNumOfUsers);
    if($resultForTotal){
        while($row=mysqli_fetch_array($resultForTotal)){
             return $row[0];
        }
    }

}
					

function getNewsFromDatabase($conn){
   
  $fakenews = "";
  $sql="SELECT title , imgsrc , news , header FROM NEWS
ORDER BY RAND()
LIMIT 1";
  $result = $conn->query($sql);
  $row = mysqli_fetch_array($result);
    $title = (string)$row['title'];
    $imgsrc = (string)$row['imgsrc'];
    $news = (string)$row['news']; 
    $header = (string)$row['header'];  

    $fakenews = $title.','.$imgsrc.','.$news.','.$header;
  
    return $fakenews;

  mysqli_free_result($result);

}

?>