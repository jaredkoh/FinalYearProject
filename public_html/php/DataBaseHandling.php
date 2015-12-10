<?php
function openConnection(){
  //USERNAMES AND PASSWORDS FOR DATABASE
  $servername = "mysql.hostinger.co.uk";
  $username = "u854725684_user";
  $password = "iloveshyen";
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

?>
