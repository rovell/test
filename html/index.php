<?php
require_once 'connect.php';
//require 'func.php';
require_once 'func.php';
require_once 'login.php';
require_once 'device.php';

/*
 * /login
 * /device/
 * /device/<id>
 *
 * POST
 * GET
 */

/*
 * request -> parser -> $type -> $method -> $response
 */

const TypeLogin = "login";
const TypeDevice = "device";

const MethodPost = 'POST';
const MethodGet = 'GET';
const MethodPatch = 'PATCH';
const MethodDel = 'DELETE';

$q = $_GET['q'];
$params = explode('/',$q);
$type = $params[0];
$id = $params[1];

$method = $_SERVER['REQUEST_METHOD'];

$response = [];

switch ($type) {
  case TypeLogin:
    $response = handleLogin2($method, $mysqli, $username, $password);
    break;
  case TypeDevice:
    $response = handleDevice2($method, $mysqli, $id);
    break;
  default:
    $response = [
      'code' => 404,
      'body' => 'Not found'
    ];
    break;
}

http_response_code($response['code']);

$body = json_encode($response['body']);
echo $body;
?>
