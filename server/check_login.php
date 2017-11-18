<?php
session_start();
require('./conector.php');
$con = new ConectorBD();
$con->buildMe();

$response['conexion'] = $con->initConexion('provbas_agendaJMAN');

  if ($response['conexion']=='OK') {
    $resultado_consulta = $con->consultar(['usuarios'],
    ['email', 'password'], 'WHERE email="'.$_POST['username'].'"');

    if ($resultado_consulta->num_rows != 0) {
      $fila = $resultado_consulta->fetch_assoc();
      if (password_verify($_POST['password'], $fila['password'])) {
        $response['acceso'] = 'concedido';
        $response['email'] = $_POST['username'];

        $_SESSION["username"]=$_POST['username'];
      }else {
        $response['motivo'] = 'Contraseña incorrecta';
        $response['acceso'] = 'rechazado';
      }
    }else{
      $response['motivo'] = 'Email incorrecto';
      $response['acceso'] = 'rechazado';
    }
  }
  //$response['msg']= $response['motivo'] + ', Acceso:' + $response['acceso'];
  //echo $response;
  echo json_encode($response);

  $con->cerrarConexion();

 ?>
