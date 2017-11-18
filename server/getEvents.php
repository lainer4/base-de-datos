<?php
session_start();

require('./conector.php');

$con = new ConectorBD();
$con->buildMe();

$response['conexion'] = $con->initConexion('provbas_agendaJMAN');

if ($response['conexion']=='OK') {

  $resultado_consulta = $con->consultar(['eventos'],
  ['id', 'email', 'titulo', 'fechainicio', 'horainicio',
   'fechafinal', 'horafinal', 'todoeldia'], 'WHERE email="'.$_SESSION['username'].'"');

   $events = array();

     $i=0;
     while ($fila = $resultado_consulta->fetch_assoc()) {

       $e = array();
            $e['id'] = $fila['id'];
            $e['title'] = $fila['titulo'];
            $e['start'] = $fila['fechainicio'] . 'T' . $fila['horainicio'];
            $e['end'] = $fila['fechafinal'] . 'T' . $fila['horafinal'];
            $e['allDay'] = ($fila['fechafinal'] == 0 ? false : true );
            array_push($events, $e);

       $i++;
     }
     $response["eventos"] = $events;

}


$response['msg']= 'OK';
echo json_encode($response);

  $con->cerrarConexion();
 ?>
