<?php
require_once 'login.php';

function handleDevice2($method, $mysqli, $id) {
    $bearer_token = checkToken($mysqli);
    $basicauth = basicAuth($mysqli);
    if($basicauth != 0) {
        $userid = loginByUsername($mysqli,$basicauth[0],$basicauth[1]);
        if($userid === false) {
            return [
                'code' => 401,
                'body' => 'Auth error'
            ];
        }
        else
            {
            $tokenget = TokenGet($mysqli,$userid);
            if($tokenget != false) {
                $bearer_token = $tokenget;
            }
        }

    }
    $userID = loginByToken($mysqli, $bearer_token);
    if ($userID === 0) {
        return [
            'code' => 403,
            'body' => [
                'message' => 'Unauthorized'
            ]
        ];
    }

    /*
     * 200 - 299 - ok
     * 400 - 499 - client
     * 404 - not found
     * 500 > server
     */

    switch ($method) {
        case MethodPost:
            $device = createDevice($mysqli, $userID,$id);
            if ($device === false) {
                return [
                    'code' => 500,
                    'body' => [
                        'message' => 'Internal error'
                    ]
                ];
            }
            return [
                'code' => 201,
                'body' => $device,
            ];

        case MethodGet:
            $device = getDevice($mysqli, $userID, $id);
            if ($device === false) {
                return [
                    'code' => 500,
                    'body' => [
                        'message' => 'Internal error'
                    ]
                ];
            }

            if ($device === null) {
                return [
                    'code' => 404,
                    'body' => [
                        'message' => 'Not found'
                    ]
                ];
            }

            return [
                'code' => 200,
                'body' => $device,
            ];

        case MethodPatch:
            $device = putDevice($mysqli,$userID,$id);
            if($device === false) {
                return [
                    'code' => 500,
                    'body' => 'Internal error'
                ];
            }
            return [
                'code' => 200,
                'body' => $device
            ];
        break;

        case MethodDel:
            $device = delDevice($mysqli,$userID,$id);
            if($device === false) {
                return [
                    'code' => 500,
                    'body' => 'Internal error'
                ];
            }
            return [
                'code' => 200,
                'body' => $device
            ];
        default:
            return [
                'code' => 405,
                'body' => 'Unknown method',
            ];
    }
}

/*
 * OK -> array
 * Failed - false
 */
function createDevice($mysqli, $userID,$id) {
    if(isset($id)) {
        return [
            'code' => 404,
            'body' => 'Page is not found'
        ];
    }
    $data = file_get_contents('php://input');
    //die(print_r($data));
    $data = json_decode($data, true);
    //die(print_r($data));
    $name = $data['name'];
    $ip = $data['ip'];
    $checkDevice = checkDevice($mysqli,$name,$ip);
    if($checkDevice === true) {
        return [
            'code' => 403,
            'body' => 'Device already exists'
        ];
    }
    $sql = "INSERT INTO cameras (`name`,`ip`,`userid`) VALUES ('$name','$ip','$userID')";
    $request = $mysqli->query($sql);

    return [
        'code' => 200,
        'body' => 'Device created',
    ];
}

/*
* OK -> array
 * Failed - false
 */
function getDevice($mysqli, $userID, $id) {

    if(isset($id)) {
        $sql = "SELECT * FROM cameras WHERE id = '$id' and userid = '$userID'";
        $result = $mysqli->query($sql);
        $obj = $result->fetch_object();
        if ($obj == null) {
            return [
                'code' => 404,
                'body' => 'Device is not found',
            ];
        } else {
           return $obj;
            }


    }

    $sql = "SELECT * FROM cameras WHERE userid = '$userID'";
    $result = $mysqli->query($sql);
    $array = [];
    while($row = mysqli_fetch_assoc($result)) {
        $array[] = $row;
    }
    return $array;
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
    if(mysqli_affected_rows($mysqli) === 0) {
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

