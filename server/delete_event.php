<?php
session_start();
require('./conector.php');

if (isset($_SESSION['username'])) {
  $con = new ConectorBD();
  $con->buildMe();

  $response['conexion'] = $con->initConexion('provbas_agendaJMAN');

  if ($response['conexion']=='OK') {

    $condicion = "id = '" . $_POST["id"] . "'";

    if($con->eliminarRegistro('eventos', $condicion)){
   
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
