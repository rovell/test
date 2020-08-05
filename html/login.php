<?php
//require_once 'func.php';
require_once 'func.php';
require_once 'connect.php';
/*
 * [
 *  'code',
 *  'body',
 * ]
 */

function handleLogin2($method, $mysqli, $username, $password) {
    switch ($method) {
        case MethodPost:
          $username = $_POST['username'];
          $password = $_POST['password'];
          /*
  if($username == 0 and $password == 0) {
    $rs = basicAuth($mysqli);
    $username = $rs[0];
    $password = $rs[1];
  }
          */

            return login($mysqli, $username, $password);
    }
}

function login($mysqli, $username, $password) {
    $userID = loginByUsername($mysqli, $username, $password);
    if ($userID === 0) {
        return [
            'code' => 403,
            'body' => [
                'message' => 'Unauthorized'
            ]
        ];
    }

    $token = generateToken($mysqli, $userID);

    if ($token === false) {
        return [
            'code' => 500,
            'body' => [
                'message' => 'Internal error'
            ]
        ];
    }

    return [
        'code' => 200,
        'body' => [
            'access_token' => $token,
        ]
    ];
}

function loginByUsername($mysqli, $username, $password) {
    $sql = "SELECT * FROM username WHERE username = '$username' and password = '$password'";
    $result = $mysqli->query($sql);
    $obj = $result->fetch_object();
    if($obj == null) {
      return 0;
    }
    return $obj->id;
}

function loginByToken($mysqli, $bearer_token) {
  $sql = "SELECT `userid` FROM session WHERE hash = '$bearer_token'";
  $result = $mysqli->query($sql);
  $obj = $result->fetch_object();
  if($obj == null) {
    return 0;
  }
  return $obj->userid;
}
?>
