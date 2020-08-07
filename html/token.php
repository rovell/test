<?php
require_once 'connect.php';

function generateToken($mysqli,$userID) {
    $token = md5(time());
    $sql = "INSERT INTO session SET hash = '$token', userid = '$userID'";
    $request = $mysqli->query($sql);
    return $token;
}

function basicAuth($mysqli) {
    $get_headers = getallheaders();
    $basicAuth = $get_headers['Authorization'];
    $parse_auth = explode(" ",$basicAuth);
    $parse_auth =  $parse_auth[1];
    $base64_decode = base64_decode($parse_auth);
    $parse_base64_decode = explode(":" ,$base64_decode);
    $username = $parse_base64_decode[0];
    $password = $parse_base64_decode[1];
    $md5 = md5($password);
    $sql = "SELECT * FROM username WHERE username = '$username' and password = '$md5'";
    $result = $mysqli->query($sql);
    $obj = $result->fetch_object();
    if($obj == null) {
        return 0;
    }
    return array($username,$password);
}

function checkToken($mysqli) {
    $get_headers = getallheaders();
    $bearer = $get_headers['Authorization'];
    $pars_header = explode(" ", $bearer);
    $tokenbear = $pars_header[0];
    $tokenn = $pars_header[1];
    $sql_token = "SELECT * from session WHERE hash = '$tokenn'";
    $result = $mysqli->query($sql_token);
    $obj = $result->fetch_object();
    if($obj == null) {
        return [
            'code' => 500,
            'message' => 'Internal error',
        ];
    }
  return $obj->hash;
}

function putDevice($mysqli,$userID,$id){
    $data = file_get_contents('php://input');
    //die(print_r($data));
    $data = json_decode($data, true);
    //die(print_r($data));
    $name = $data['name'];
    $ip = $data['ip'];
    $sql = "UPDATE cameras SET name = '$name' , ip = '$ip' WHERE id = '$id' and userid = '$userID'";
    $request = $mysqli->query($sql);
    if(mysqli_affected_rows($mysqli) === 0){
     return [
         'code' => 404,
         'body' => 'Device is not found or already updated'
     ];
    }
    $res = [
        "code" => 201,
        "body" => "Updated"
    ];

    return $res;

}

function delDevice($mysqli,$userID,$id) {
    $sql = "DELETE  FROM cameras WHERE id = '$id' and userid = '$userID'";
    $request = $mysqli->query($sql);
    if(mysqli_affected_rows($mysqli) ===0) {
        return [
            'code' => 404,
            'body' => 'Device is not found or already deleted'
        ];
    }
    return [
        'code' => 200,
        'body' => 'Device removed'
    ];
}

function checkDevice($mysqli,$name,$ip) {
    $sql = "SELECT * FROM cameras WHERE name = '$name' and ip = '$ip'";
    $request = $mysqli->query($sql);
    $obj = $request->fetch_object();
    if($obj == null) {
        return false;
    }
    return true;
}

function TokenGet($mysqli,$userid){
$sql = "SELECT * FROM session WHERE userid = '$userid'";
$request = $mysqli->query($sql);
$obj = $request->fetch_object();
return $obj->hash;
}

?>