<?php

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    header('Access-Control-Request-Headers: Origin, X-Requested-With, Content-Type, Accept');

    include "includes/config.php";
    include "includes/utilitarios.php";
    include "includes/funciones.php";

    $conn = connect($db);

    if($_SERVER['REQUEST_METHOD']==='GET'){

        if( isset($_GET['id']) and !empty($_GET['id']) ){
            $query="";
            $query= $query."SELECT c.* ";
            $query= $query."FROM categorias c ";
            $query= $query."WHERE id=:id ";

            $stmt  = $conn->prepare($query);
            $stmt ->bindValue(':id', $_GET['id']);
            $stmt ->execute();
            $stmt ->setFetchMode(PDO::FETCH_ASSOC);

            headerSuccess(200);
            exit(json_encode($stmt ->fetchAll()));
        }

        if( empty($_GET) ) {
            $query="";
            $query= $query."SELECT c.* ";
            $query= $query."FROM categorias c ";

            $stmt  = $conn->prepare($query);
            $stmt ->execute();
            $stmt ->setFetchMode(PDO::FETCH_ASSOC);

            headerSuccess(200);
            exit(json_encode($stmt ->fetchAll()));
        }
        
        exit(createException(404));

    }

    if($_SERVER['REQUEST_METHOD']==='POST'){

        $array = ["nombre"];
        $array_errors = [];
        $array_errors_count = 0;

        $data = json_decode(file_get_contents("php://input"));
        if (empty($data)) {
            exit(createException(400));
        }

        

        checkRowData($array, $array_errors, $array_errors_count, $data);

        $query = "";
        $query = $query."INSERT INTO categorias (nombre) ";
        $query = $query."VALUES (:nombre) ";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':nombre', $data->nombre);
        $stmt->execute();
        
        if($stmt){
            $data->id = $conn->lastInsertId();
            exit(headerSuccess(200, $data));
        }else{
            exit(createException(400));
        }

    }

    if($_SERVER['REQUEST_METHOD']==='PUT'){

        $array = ["nombre"];
        $array_errors = [];
        $array_errors_count = 0;

        if( !isset($_GET['id']) or empty($_GET['id']) ){
            exit(createException(400));
        }

        $data = json_decode(file_get_contents("php://input"));
        if (empty($data)) {
            exit(createException(400));
        }

        

        checkRowData($array, $array_errors, $array_errors_count, $data);

        $query = "";
        $query = $query."UPDATE categorias SET ";
        $query = $query."nombre = :nombre ";
        $query = $query."WHERE id = :id ";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':nombre', $data->nombre);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();
        
        if($stmt){
            $data->id = $_GET['id'];
            exit(headerSuccess(200, $data));
        }else{
            exit(createException(400));
        }

    }

    if($_SERVER['REQUEST_METHOD']==='DELETE'){

        if( !isset($_GET['id']) or empty($_GET['id']) ){
            exit(createException(400));
        }

        $id = $_GET['id'];

        $query="";
        $query=$query."DELETE FROM categorias ";
        $query=$query."WHERE id = :id ";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        if($stmt){
            exit(headerSuccess(200, [ "status" => "OK" ]));
        }else{
            exit(createException(400));
        }

    }

?>