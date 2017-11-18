<?php

  class ConectorBD
  {
    private $host;
    private $user;
    private $password ;
    private $dbname ;
    public $conexion;
    function buildMe(){

      $ip = getenv("REMOTE_ADDR"); // get the ip number of the user
      //echo $ip;
      //printf("Versión de la biblioteca cliente: %d\n", mysqli_get_client_version());
      if ($ip == '127.0.0.1') {
          $this->host = "127.0.0.1";
          $this->user = "agendaNavidad";
          $this->password = "MerryChristmas2017?";
          $this->dbname = "agendaJMAN";
      } else {
          //$this->host = "mysql1206.opentransfer.com";
          $this->host = "mysql1206.ixwebhosting.com";
          $this->user = "provbas_agenda";
          $this->password = "MaryXmas2017";
          $this->dbname = "provbas_agendaJMAN";
      }

    }

    function initConexion($nombre_db){
      if(!is_null($nombre_db)){
          $this->dbname = $nombre_db;
      }
      $this->conexion = new mysqli($this->host, $this->user, $this->password, $this->dbname);
      if ($this->conexion->connect_error) {
        return "Error:" . $this->conexion->connect_error;
      }else {
        return "OK";
      }
    }

    function ejecutarQuery($query){
      return $this->conexion->query($query);
    }

    function cerrarConexion(){
      $this->conexion->close();
    }

    function newTable($nombre_tbl, $campos){
      $sql = 'CREATE TABLE '.$nombre_tbl.' (';
      $length_array = count($campos);
      $i = 1;
      foreach ($campos as $key => $value) {
        $sql .= $key.' '.$value;
        if ($i!= $length_array) {
          $sql .= ', ';
        }else {
          $sql .= ');';
        }
        $i++;
      }
      return $this->ejecutarQuery($sql);
    }

    function nuevaRestriccion($tabla, $restriccion){
      $sql = 'ALTER TABLE '.$tabla.' '.$restriccion;
      return $this->ejecutarQuery($sql);
    }

    function nuevaRelacion($from_tbl, $to_tbl, $from_field, $to_field){
      $sql = 'ALTER TABLE '.$from_tbl.' ADD FOREIGN KEY ('.$from_field.') REFERENCES '.$to_tbl.'('.$to_field.');';
      return $this->ejecutarQuery($sql);
    }

    function insertData($tabla, $data){
      $sql = 'INSERT INTO '.$tabla.' (';
      $i = 1;
      foreach ($data as $key => $value) {
        $sql .= $key;
        if ($i<count($data)) {
          $sql .= ', ';
        }else $sql .= ')';
        $i++;
      }
      $sql .= ' VALUES (';
      $i = 1;
      foreach ($data as $key => $value) {
        $sql .= $value;
        if ($i<count($data)) {
          $sql .= ', ';
        }else $sql .= ');';
        $i++;
      }
      return $this->ejecutarQuery($sql);

    }

    function getConexion(){
      return $this->conexion;
    }

    function actualizarRegistro($tabla, $data, $condicion){
      $sql = 'UPDATE '.$tabla.' SET ';
      $i=1;
      foreach ($data as $key => $value) {
        $sql .= $key.'='.$value;
        if ($i<sizeof($data)) {
          $sql .= ', ';
        }else $sql .= ' WHERE '.$condicion.';';
        $i++;
      }
      return $this->ejecutarQuery($sql);
    }

    function eliminarRegistro($tabla, $condicion){
      $sql = "DELETE FROM ".$tabla." WHERE ".$condicion.";";
      return $this->ejecutarQuery($sql);
    }

    function consultar($tablas, $campos, $condicion = ""){
      $sql = "SELECT ";
      $ultima_key = end(array_keys($campos));
      foreach ($campos as $key => $value) {
        $sql .= $value;
        if ($key!=$ultima_key) {
          $sql.=", ";
        }else $sql .=" FROM ";
      }

      $ultima_key = end(array_keys($tablas));
      foreach ($tablas as $key => $value) {
        $sql .= $value;
        if ($key!=$ultima_key) {
          $sql.=", ";
        }else $sql .= " ";
      }

      if ($condicion == "") {
        $sql .= ";";
      }else {
        $sql .= $condicion.";";
      }

      return $this->ejecutarQuery($sql);
    }

    function consultaCompuesta($tablas, $campos, $relaciones, $condicion = ""){
      $sql = "SELECT ";
      $ultima_key = end(array_keys($campos));
      foreach ($campos as $key => $value) {
        $sql .= $value;
        if ($key!=$ultima_key) {
          $sql.=", ";
        }else $sql .=" FROM ";
      }
      $sql .= $tablas[0]." ";
      $ultima_key = end(array_keys($tablas));
      foreach ($tablas as $key => $value) {
        if ($key != 0) {
          $sql .= "JOIN ".$value." ON ".$relaciones[$key-1]." \n";
        }
      }
      if ($condicion == "") {
        $sql .= ";";
      }else {
        $sql .= $condicion.";";
      }
      return $this->ejecutarQuery($sql);
    }

    function createUsers(){
      $insert = $this->conexion->prepare("INSERT INTO `usuarios` (email, nombre, password, nacimiento) " .
                                      " VALUES (?, ?, ?, ?)");

      $insert->bind_param("ssss", $email, $nombre, $password);
      //first user
      $email = "jperez@gmail.com";
      $nombre = "Juan Perez";
      $password = password_hash("juanperez", PASSWORD_DEFAULT);;
      $nacimiento = "1973-09-13";

      $insert->execute();

      //second user
      $email = "agomez@gmail.com";
      $nombre = "Alejandro Gomez";
      $password = password_hash("agomez", PASSWORD_DEFAULT);;
      $nacimiento = "1968-04-02";

      $insert->execute();

      //third user
      $email = "fduran@gmail.com";
      $nombre = "Fabio Duran";
      $password = password_hash("fduran", PASSWORD_DEFAULT);;
      $nacimiento = "1975-03-03";

      $insert->execute();

      return "OK";

    }

  }
 ?>
