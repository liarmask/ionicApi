<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header("Content-Type: application/json");

    include "includes/config2.php";

    $postJSON = json_decode(file_get_contents('php://input'), true);

    if (empty($postJSON)) {
        exit();
    }

    if($postJSON['action'] ==='registration_progress'){

        $fullname = $mysqli->real_escape_string(trim($postJSON['fullname']));
        $genero = $mysqli->real_escape_string(trim($postJSON['genero']));
        $fecha_nacimiento = $mysqli->real_escape_string(trim($postJSON['fecha_nacimiento']));
        $email = $mysqli->real_escape_string(trim($postJSON['email']));
        $password = $mysqli->real_escape_string(trim($postJSON['password']));

        $insert = $mysqli->query("INSERT INTO usuario SET 
                fullname = '$fullname',
                genero = '$genero',
                fecha_nacimiento = '$fecha_nacimiento',
                email = '$email',
                password = '$password'");
        if($insert){
            $result = json_encode(array('success' => true, 'msg' => 'Registro exitoso'));
        }
        else{
            $result = json_encode(array('success' => false, 'msg' => 'Registro cancelado'));
        }

        exit($result);

    }
    elseif($postJSON["action"] ==='login_progress'){

        $email = $mysqli->real_escape_string($postJSON['email']);
        $password = $mysqli->real_escape_string($postJSON['password']);

        $sql = "SELECT userId, email, genero, fecha_nacimiento from usuario WHERE email = '{$email}' AND password = '{$password}'";

        $loginData = $mysqli->query($sql);

        $data = $loginData->fetch_array(MYSQLI_ASSOC);

        if(!empty($data)){
            $result = json_encode(array('success' => true, 'msg' => 'Acceso exitoso', 'user' => $data));
        }
        else{
            $result = json_encode(array('success' => false, 'msg' => 'Acceso cancelado'));

        }
        exit($result);
    }


    ?>