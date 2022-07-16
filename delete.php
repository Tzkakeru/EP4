<?php
    include('conn.php');

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

    //Get from json payload request
    $json = file_get_contents('php://input');
    //Transformamos el json a objeto PHP
    $credentials = json_decode($json, true);
    //$credentials['id'] = 2;

    $query = 'DELETE FROM `tarea` WHERE id = ?';

    //preparo la consulta, aun no se ejecuta!
    $stmt = mysqli_prepare($conn, $query);

    //Asigno variables para ser reemplazadas por ?
    mysqli_stmt_bind_param($stmt, 'i', $credentials['id']);

    try{
        /* ejecuta sentencias preparadas */
        mysqli_stmt_execute($stmt);  
        
        $rows = mysqli_affected_rows($conn);

        if($rows>0){
            http_response_code(200);
            echo json_encode(
                array(
                    'status' => true,
                    'message' => 'Usuario con id '.$credentials['id'].' fue eliminado',
                    'code' => 200
                )
            );
        } else {
            http_response_code(404);
            echo json_encode(
                array(
                    'status' => false,
                    'message' => $rows.' afectados',
                    'code' => 404
                )
            );
        }

    } catch(mysqli_sql_exception $e){
        http_response_code(500);
        echo json_encode(
            array(
                'status' => false,
                'message' => $e -> getMessage(),
                'code' => $e -> getCode()
            )
        );
    }

    /* cierra sentencia y conexión */   
    mysqli_stmt_close($stmt);

    /* cierra la conexión */
    mysqli_close($conn);
?>
