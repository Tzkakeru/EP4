<?php
    include('conn.php');

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

    //Get from json payload request
    $json = file_get_contents('php://input');
    //Transformamos el json a objeto PHP
    //$tarea = json_decode($json, true);
    $tarea['id'] = 11;
    $tarea['descripcion'] = 'Preparar la comida';
    $tarea['elaborado'] = 0;

    $query = 'UPDATE `tarea` SET descripcion = ?, elaborado = ?, creado = ? WHERE id = ?';

    //preparo la consulta, aun no se ejecuta!
    $stmt = mysqli_prepare($conn, $query);

    //Asigno variables para ser reemplazadas por ?
    mysqli_stmt_bind_param($stmt, 'siii', $tarea['descripcion'], $tarea['elaborado'], $tarea['creado'], $tarea['id']);

    try{
        /* ejecuta sentencias preparadas */
        mysqli_stmt_execute($stmt);     
        
        //personalizo el status code en el response
        http_response_code(201);

        //personalizo el objeto adjunto en el response
        echo json_encode(
            array(
                'status' => true,
                'message' => mysqli_stmt_affected_rows($stmt)."Fila actualizada",
                'code' => 201
            )
        );
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