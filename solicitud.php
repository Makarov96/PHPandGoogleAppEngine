<!DOCTYPE html>
<html lang="es">


<?php
 
        session_start();
        if (!isset($_SESSION['USUARIO_LOGUEADO'])){
           echo'<script type="text/javascript">  alert("usted no esta logueado"); window.location.href="index.html";   </script>';
        }

       $USUARIO = $_SESSION['LOGIN'];
       $NOMBRE = $_SESSION['NOMBRE'];
?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="./node_modules/materialize-css/dist/css/materialize.css">
        <link rel="stylesheet" href="./css/solicitud.css">
        <script src="./node_modules/materialize-css/dist/js/materialize.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

        <title>Document</title>
    </head>

    <body>

        <div class="container padre">

            <div class="container hijo">
                <div><i class="material-icons">assignment</i></div>
                <h5>Informacion de usuario</h5>

                <form action="registra.php" method="POST" enctype="multipart/form-data">


                    <div class="row">
                        <label for="" class="active"><strong>Correo</strong></label>
                        <input type="text" name="LOGIN" class="validate" value=" <?php echo $_SESSION['LOGIN']; ?>" readonly>
                    </div>

                    <div class="row">
                        <label for="" class="active">Nombre Completo</label>
                        <input type="text" class="validate" name="NOMBRECOMPLETO" value=" <?php echo $_SESSION['NOMBRE']; ?>" readonly>
                    </div>

                    <div class="row">
                        <label for="" class="active">Motivo de solicitud</label>
                        <textarea class="materialize-textarea" name="MOTIVO" cols="30" rows="10" maxlength="500" placeholder="No mas de 500 caracteres" required></textarea>
                    </div>

                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Seleccione el archivo</span>
                            <input type="hidden" name="MAX_FILE_SIZE" id="" value="9000000">
                            <input name="userfile" type="file" required/>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload file">
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col s12 ">
                            <input name="submit" type="submit" class="btn btn-block waves-effect waves-light" value="Enviar Datos del usuarios">
                        </div>
                    </div>


                </form>

            </div>

        </div>
    </body>

</html>