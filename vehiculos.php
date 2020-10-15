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

        $response = null;
        $enter = false;

        if( isset($_GET['id']) and !empty($_GET['id']) ){
            $enter = true;

            $query="";
            $query= $query."SELECT v.* ";
            $query= $query."FROM vehiculos v ";
            $query= $query."WHERE id=:id ";

            $stmt  = $conn->prepare($query);
            $stmt->bindValue(':id', $_GET['id']);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $response = $stmt->fetchAll();
        }

        if( !isset($_GET['id']) or empty($_GET) ) {
            $enter = true;
            $query="";
            $query= $query."SELECT v.* ";
            $query= $query."FROM vehiculos v ";

            $stmt  = $conn->prepare($query);
            $stmt ->execute();
            $stmt ->setFetchMode(PDO::FETCH_ASSOC);

            $response = $stmt->fetchAll();
        }

        if ( isset($_GET['categorias']) and $enter ) {
            foreach ($response as &$vehiculos) {
                $query="";
                $query= $query."SELECT categorias.id, categorias.nombre ";
                $query= $query."FROM categorias ";
                $query= $query."WHERE categorias.id = :idcategoria ";

                $stmt  = $conn->prepare($query);
                $stmt->bindValue(':idcategoria', $vehiculos['id']);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $categorias = $stmt->fetchAll();

                $vehiculos['Info Categoría'] = $categorias;
            }
        }

        if ( $enter ) {
            headerSuccess(200);
            exit(json_encode($response));
        }
        
        exit(createException(404));

    }

    

?>