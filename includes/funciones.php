<?php

    function createException($code = 404, $array = null) {
        if ($code == 404) {
            header("HTTP/1.1 404 Not Found");
            header("Content-type: application/json; charset=utf-8");
            return json_encode([
                "code" => "404",
                "message" => "Not Found"
            ]);
        }

        if ($code == 400) {
            if (empty($array)) {
                header("HTTP/1.1 400 Bad Request");
                header("Content-type: application/json; charset=utf-8");
                return json_encode([
                    "code" => "400",
                    "message" => "Bad Request"
                ]);
            } else {
                $message = "Non-existent or empty columns: ";
                foreach ($array as $key => $value) {
                    if (count($array) - 1 == $key) {
                        $message = $message."{$value}.";
                    } else if (count($array) > 1) {
                        $message = $message."{$value}, ";
                    } else {
                        $message = $message."{$value}";
                    }
                }
                header("HTTP/1.1 400 Bad Request");
                header("Content-type: application/json; charset=utf-8");
                return json_encode([
                    "code" => "400",
                    "message" => $message,
                ]);
            }
        }
    }

    function headerSuccess($code = 200, $object = null){
        if ($code == 200) {
            if (!empty($object)) {
                header("HTTP/1.1 200 OK");
                header("Content-type: application/json; charset=utf-8");
                return json_encode($object);
            } else {
                header("HTTP/1.1 200 OK");
                header("Content-type: application/json; charset=utf-8");
            }
        }
    }

    function checkRowData($array, $array_errors, $array_errors_count, $data){
        /*foreach ($array as $key => $value) {
            if ( isset($data->$value) and !empty(trim($data->$value)) ) {
                $array_errors_count++;
            } else {
                $i = count($array_errors);
                $array_errors[$i] = $value;
            }
        }
    
        if ( $array_errors_count < count($array) ) {
            exit(createException(400, $array_errors));
        }*/
    }

?>