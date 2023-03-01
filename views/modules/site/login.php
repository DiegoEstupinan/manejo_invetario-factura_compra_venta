<?php
require_once("../../../app/Controllers/PersonasController.php");
require_once("../../partials/routes.php");

?>

<!DOCTYPE html>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Login</title>
    <?php require("../../partials/head_imports.php"); ?>
</head>
<body class="hold-transition login-page">

<div class="login-box">

    <!-- /.login-logo -->
    <div class="card">
        <div class="login-logo">
            <a href="login.php"><b>FERROGÁMEZA</b></a>
        </div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Ingrese sus datos para iniciar sesión</p>
            <form action="../../../app/Controllers/MainController.php?controller=Personas&action=login" method="post">
                <div class="input-group mb-3">
                    <input type="text" id="documento" name="documento" class="form-control" placeholder="No Documento">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Contraseña">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block" onclick="return ConfirmacionPoliticas()">Ingresar</button>
                    </div>
                    <!-- /.col -->
                </div>

                <br>
                <?php if (!empty($_GET['respuesta'])) { ?>
                    <?php if ( !empty($_GET['respuesta']) && $_GET['respuesta'] != "correcto" ) { ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-ban"></i> Error al Ingresar: </h5> <?= $_GET['mensaje'] ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </form>

        </div>

    </div>
</div>
<script type="text/javascript">
  /*  function ConfirmacionPoliticas()
    {
        swal({
            icon: 'info',
            title: 'Alerta',
            text: 'Aceptas las políticas de seguridad  ?',
            buttons:"si",
            footer: '<a href="">Why do I have this issue?</a>'

            }).then(respuesta=>{
                if(respuesta=="si"){
                    swal ('gffdfdbfdbfd')
                }else {
               swal("fvhkfdyukhlo;fbohfdkjvlc gfhj")
                }
        })


      /*  if (respuesta == true){
            return true;
        }else
        {
            return false
        }*/
    }*/
</script>
<?php require('../../partials/scripts.php'); ?>

</body>
</html>