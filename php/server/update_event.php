<?php
session_start();
require('./conector.php');

if (isset($_SESSION['username'])) {
  $con = new ConectorBD();
  $con->buildMe();

  $response['conexion'] = $con->initConexion('agenda');

  if ($response['conexion']=='OK') {

    $datos['fechainicio'] = "'" . $_POST["start_date"] . "'";
    $datos['horainicio'] = "'" . $_POST["start_hour"] . "'";
    $datos['fechafinal'] = "'" . $_POST["end_date"] . "'";
    $datos['horafinal'] = "'" . $_POST["end_hour"] . "'";
    $datos['todoeldia'] = "'" . $_POST["allDay"] . "'";

    $condicion = "id = '" . $_POST["id"] . "'";

    if($con->actualizarRegistro('eventos', $datos, $condicion)){
    
      $response['msg']= 'OK';
    } else {
     
      $response['msg']= 'Error';

    }

  } else {
    $response['msg']= 'ErrorConexion';
  }
  echo json_encode($response);
} else {
  $response['msg']= 'Error, Debe iniciar Sesion';
  echo json_encode($response);

}

?>
