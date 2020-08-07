<?php
/*
require_once 'connect.php';

function handleLogin($mysqli,$username,$password){
  $userID = login($mysqli,$username,$password);
  if($userID == 0) {
    $body = "unauthorized";
    return [
      'code' => 403,
      'body' => $body,
    ];
  }
   $token = generateToken($mysqli,$userID);
   return [
     'code' => 200,
     'body' => $token,

   ];
}



function generateToken($mysqli,$userID) {
  $token = md5(time());
  $sql = "INSERT INTO session SET hash = '$token', userid = '$userID'";
  $request = $mysqli->query($sql);
  return json_encode($token);
}

function login($mysqli,$username,$password) {
  $password = md5($password);
  $sql = "SELECT * FROM username WHERE username = '$username' and password = '$password'";
  $request = $mysqli->query($sql);
  $obj = $request->fetch_object();
  if($obj == null) {
  return 0;
  }

    var_dump($obj);
  return $obj->id;
  }


function basicAuth($mysqli,$get_headers) {
  $get_headers = getallheaders();
  $basicAuth = $get_headers['Authorization'];
  $parse_auth = explode(" ",$basicAuth);
  $parse_auth =  $parse_auth[1];
  $base64_decode = base64_decode($parse_auth);
  $parse_base64_decode = explode(":" ,$base64_decode);
  $name = $parse_base64_decode[0];
  $pass = $parse_base64_decode[1];
  $pass = md5($pass);
  $sql = "SELECT * FROM username WHERE username = '$name' and password = '$pass'";
  $result = $mysqli->query($sql);
  $obj = $result->fetch_object();
  if($obj == null) {
    return 0;
  }
  return [
       'id' => $obj->id,
       'code' => 200,

  ];
}


function checkToken($mysqli,$userID,$get_headers) {
$get_headers = getallheaders();
$bearer = $get_headers['Authorization'];
$pars_header = explode(" ", $bearer);
$tokenbear = $pars_header[0];
$tokenn = $pars_header[1];
$sql_token = "SELECT `userid` from session WHERE hash = '$tokenn'";
$result = $mysqli->query($sql_token);
$obj = $result->fetch_object();
if($obj != null) {
  return [
    'userid' => $obj->userid,
    'code' => 200,
    'message' => 'Successfully',
  ];
}
return [

  'code' => 401,
  'message' => 'Undefined your token',

];
}




function getPostsbyid($mysqli,$id,$basicAuth,$response){
  if(isset($basicAuth['id'])) {
    $userid = $basicAuth['id'];
  }
  else {
    $userid = $response['userid'];
  }
  $sql = "SELECT * FROM cameras WHERE id = '$id' and userid = '$userid'";
  $request = $mysqli->query($sql);
  while($row = mysqli_fetch_assoc($request)){
  $array[] = $row;
}
   if(!empty($array)) {
     return json_encode($array);
   }
   else {
     $res = [
          'code' => 404,
          'message' => 'Posts by id not found',
     ];

     return json_encode($res);
   }

  }


function getPostall($mysqli,$basicAuth,$response){
  if(isset($basicAuth['id'])) {
    $userid = $basicAuth['id'];
  }
  else {
    $userid = $response['userid'];
  }
  $sql = "SELECT * FROM cameras WHERE userid = '$userid'";
  $request = $mysqli->query($sql);
  while($row = mysqli_fetch_assoc($request)){
  $array[] = $row;
  }
  if(!empty($array)) {
        return json_encode($array);
  }
  else {
    $res = [
         'code' => 404,
         'message' => 'Posts not found',
    ];

    return json_encode($res);
  }

}

function putDevice($mysqli,$id){
  $data = file_get_contents('php://input');
  //die(print_r($data));
  $data = json_decode($data, true);
  //die(print_r($data));
  $name = $data['name'];
  $ip = $data['ip'];
  $sql = "UPDATE cameras SET name = '$name' , ip = '$ip' WHERE id = '$id'";
  $request = $mysqli->query($sql);
  $res = [
   "status" => 201,
   "message" => "Updated"
 ];

   return json_encode($res);

}


function delDevice($mysqli,$id) {
  $sql6 = "DELETE FROM cameras WHERE id = '$id'";
  $request = $mysqli->query($sql6);
  $res = [
   "status" => true,
   "message" => "Deleted"
 ];
   return $res;
}
*/

 ?>
