<?php

  require_once 'meekrodb.2.3.class.php';
  require 'conf.php';



  session_start();

   function validarUsuario(){

         $CORREO=strtoupper($_POST ['email']);
         $CLAVE =strtoupper($_POST ['pwd']);
         $mysqli = DB::get();
         $results = DB::query( "SELECT * FROM tb_usuarios where correoe='".$CORREO."' and clave =MD5('".$CLAVE."')");
         $count = DB::count();

            if($count  == 0){

              session_unset();
              echo'<script type="text/javascript">  alert("Usuario Incorrecto"); 
               window.location.href="index.html";   </script>';
            }else{

                foreach ($results as $row) {
                      echo " <br>". $row["CORREOE"] . " es valido </br>";
                      $_SESSION['USUARIO_LOGUEADO']  = true;
                      $_SESSION['LOGIN']  = $row["CORREOE"];
                      $_SESSION['NOMBRE']  = $row["NOMBRECOMPLETO"];

                      echo $row["NOMBRECOMPLETO"].'infoooooooo';
                      echo'<script type="text/javascript">  window.location.href="solicitud.php";   </script>';
                }
            }

       DB::disconnect();
  }

  validarUsuario();
?>