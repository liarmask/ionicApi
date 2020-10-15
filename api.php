<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Request-Headers: Origin, X-Requested-With, Content-Type, Accept');

include "includes/config.php";
include "includes/utilitarios.php";
include "includes/funciones.php";



$dbConn = connect($db);

if($_SERVER['REQUEST_METHOD']==='GET'){
    if( isset($_GET['id']) and !empty($_GET['id']) ){
        $cadenaSql="";
        $cadenaSql= $cadenaSql."select c.* ";
        $cadenaSql= $cadenaSql."from comentarios c ";
        $cadenaSql= $cadenaSql."where id=:id ";
        $respuestaSql = $dbConn->prepare($cadenaSql);
        $respuestaSql->bindValue(':id', $_GET['id']);
        $respuestaSql->execute();
        $respuestaSql->setFetchMode(PDO::FETCH_ASSOC);
        headerSuccess(200);
        exit(json_encode($respuestaSql->fetchAll()));
    }

    if( empty($_GET) ){
        $cadenaSql="select * from comentarios";
        $respuestaSql = $dbConn->prepare($cadenaSql);
        $respuestaSql->execute();
        $respuestaSql->setFetchMode(PDO::FETCH_ASSOC);
        headerSuccess(200);
        exit(json_encode($respuestaSql->fetchAll()));
    }
    
    exit(createException(404));
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    //se utiliza para registrar 

    $data = json_decode(file_get_contents("php://input"));
    //data = [{"per_id":"1","per_nombres":"N1"}]
    $cadenaSql = "";
    $cadenaSql = $cadenaSql." INSERT INTO COMENTARIOS(comentario) ";
    $cadenaSql = $cadenaSql."values('" . $data->comentario."') ";
    $respuestaSql = $dbConn->query($cadenaSql);
    if($respuestaSql){
        //$data->id=$dbConn->insert_id;
        exit(json_encode($data));
    }
    else{
        exit(json_encode(array('status' => 'error'))); 
    }
    
}

if($_SERVER['REQUEST_METHOD']==='PUT'){
    //para modificar(update)
    if(isset($_GET['id'])){
        //capturar el id (ejemplo=1)
        $id = $_GET['id'];
        $data = json_decode(file_get_contents("php://input"));
        $cadenaSql = "";
        $cadenaSql = $cadenaSql." UPDATE COMENTARIOS SET  ";
        $cadenaSql = $cadenaSql." comentario='". $data->comentario."'";
        $cadenaSql = $cadenaSql." where id=$id ";
        $respuestaSql = $dbConn->query($cadenaSql);
        if($respuestaSql){
            //$data->id=$dbConn->insert_id;
            exit(json_encode($data));
        }
        else{
            exit(json_encode(array('status' => 'error'))); 
        }
    }
    
}

if($_SERVER['REQUEST_METHOD']==='DELETE'){
    //delete
    if( isset($_GET['id']) and !empty($_GET['id']) ){
        $id=$_GET['id'];
        $cadenaSql="";
        $cadenaSql=$cadenaSql."delete from comentarios ";
        $cadenaSql=$cadenaSql."where id=$id";
        $respuestaSql=$dbConn->query($cadenaSql);
        if($respuestaSql){
            exit(headerSuccess(200, [ "status" => "OK" ]));
        }else{
            exit(createException(400));
        }
    }

    exit(createException(400));
}


?>