<?php

 require_once 'meekrodb.2.3.class.php';
 require 'conf.php';

  session_start();

  if (!isset($_SESSION['USUARIO_LOGUEADO'])){
    echo'<script type="text/javascript">  alert("usted no está logueado"); window.location.href="index.html";   </script>';
  }

   $mysqli = DB::get();


   if(isset($_POST['submit'])){
    $CORREO=strtoupper($_POST ['LOGIN']);
    $NOMBRECOMPLETO =strtoupper($_POST ['NOMBRECOMPLETO']);
    $MOTIVO = $_POST ['MOTIVO'];
    echo '<br>';
    $PATH = $_FILES['userfile']['tmp_name'];
    $Archivo = $_FILES['userfile']['name'];

    DB::query("INSERT INTO tb_solicitud (nombreingresa,correoingresa,rutapdf,nombrearchivo,motivo) 
     VALUES ('".$NOMBRECOMPLETO."' , '".$CORREO."' , '".$PATH."' , '".$Archivo."' , '".$MOTIVO."' )
    ");

    echo'<script type="text/javascript">  alert("Su informacion se grabo con exito"); 
      window.location.href="solicitud.php";   </script>';
   }

?>