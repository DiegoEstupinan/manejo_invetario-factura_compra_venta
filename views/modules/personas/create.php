 <?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\PersonasController;

use App\Models\GeneralFunctions;


$nameModel = "Persona";
$nameForm = 'frmCreate'.$nameModel;
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Crear <?= $nameModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("../../partials/navbar_customization.php"); ?>

    <?php require("../../partials/sliderbar_main_menu.php"); ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Crear</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensaje de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-info">
                            <div class="card-header">
                                <p style="background-color: black"></p>
                                <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Información  <?= $nameModel ?> </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                            data-source="create.php" data-source-selector="#card-refresh-content"
                                            data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                class="fas fa-expand"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- form start -->
                                <form class="form-horizontal" enctype="multipart/form-data" method="post" id="<?= $nameForm ?>"
                                      name="<?= $nameForm ?>"
                                      action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=create">
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <div class="form-group row">
                                                <label for="rol" class="col-sm-2 col-form-label">Rol <font color='red'>*</font></label>
                                                <div class="col-sm-10">
                                                    <select required id="rol" name="rol" class="custom-select">


                                                        <?php if($_SESSION['UserInSession']['rol'] == 'administrador') {?>
                                                            <option <?= (!empty($frmSession['rol']) && $frmSession['rol'] == "administrador") ? "selected" : ""; ?> value="administrador">Administrador</option>
                                                            <option <?= (!empty($frmSession['rol']) && $frmSession['rol'] == "empleado") ? "selected" : ""; ?> value="empleado">Empleado</option>
                                                            <option <?= (!empty($frmSession['rol']) && $frmSession['rol'] == "proveedor") ? "selected" : ""; ?> value="proveedor">Proveedor</option>
                                                        <?php } ?>

                                                        <option <?= (!empty($frmSession['rol']) && $frmSession['rol'] == "cliente") ? "selected" : ""; ?> value="cliente">Cliente</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="nombres"  class="col-sm-2 col-form-label">Nombres <font color='red'>*</font></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="1" maxlength="50" type="text" class="form-control" id="nombre" name="nombre"
                                                           placeholder="Ingrese sus nombres" value="<?= $frmSession['nombre'] ?? '' ?>"  >
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label for="apellido" class="col-sm-2 col-form-label">Apellidos <font color='red'>*</font></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="1" maxlength="50" type="text" class="form-control" id="apellido"
                                                           name="apellido" placeholder="Ingrese sus apellidos"
                                                           value="<?= $frmSession['apellido'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div id="div-hides"class="form-group row">
                                                <label for="tipodocumento" class="col-sm-2 col-form-label">
                                                    Tipo Documento<font color='red'>*</font></label>
                                                <div class="col-sm-10">
                                                    <select id="tipodocumento" name="tipodocumento" class="custom-select">

                                                        <option <?= (!empty($frmSession['tipodocumento']) && $frmSession['tipodocumento'] == "Cedula") ? "selected" : ""; ?> value="Cedula">Cedula de Ciudadania</option>
                                                        <option <?= (!empty($frmSession['tipodocumento']) && $frmSession['tipodocumento'] == "Nit") ? "selected" : ""; ?> value="Nit">Nit</option>


                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="documento" class="col-sm-2 col-form-label">Documento <font color='red'>*</font></label>
                                                <div class="col-sm-10">
                                                    <input  required minlength="6" type="text"class="form-control"
                                                           id="documento" name="documento" placeholder="Ingrese su documento"
                                                           value="<?= $frmSession['documento'] ?? '' ?>"  >
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label for="telefono" class="col-sm-2 col-form-label">Telefono <font color='red'>*</font></label>

                                                <div class="col-sm-10">
                                                    <input  required minlength="9"  maxlength="11" type="text" class="form-control"


                                                           id="telefono" name="telefono" placeholder="Ingrese su telefono"
                                                            value=" <?= $frmSession['telefono'] ?? '' ?>">
                                                </div>
                                            </div>

                                                <div class="form-group row">
                                                    <label for="correo" class="col-sm-2 col-form-label">Correo <font color='red'>*</font></label>
                                                    <div class="col-sm-10">
                                                        <input  minlength="5" maxlength="50" type="email" class="form-control" id="correo" name="correo"
                                                               placeholder="Ingrese su Correo" value="<?= $frmSession['correo'] ?? '' ?>">
                                                    </div>
                                                </div>


                                            <div id="div-hide" class="form-group row">
                                                <label for="contrasena" class="col-sm-2 col-form-label">Contraseña <font color='red'>*</font></label>
                                                <div class="col-sm-10">
                                                    <input type="password" class="form-control" id="contrasena" name="contrasena"
                                                           placeholder="Ingrese su contraseña"minlength="6"  maxlength="50" >
                                                </div>
                                            </div>
                                                <div class="form-group row">
                                                    <label for="estado" class="col-sm-2 col-form-label">Estado <font color='red'>*</font></label>
                                                    <div class="col-sm-10">
                                                        <select required id="estado" name="estado" class="custom-select">
                                                            <option <?= ( !empty($frmSession['estado']) && $frmSession['estado'] == "activo") ? "selected" : ""; ?> value="activo">Activo</option>
                                                            <?php if($_SESSION['UserInSession']['rol'] == 'cliente') {?>
                                                            <option <?= ( !empty($frmSession['estado']) && $frmSession['estado'] == "inactivo") ? "selected" : ""; ?> value="inactivo">Inactivo</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php  ?>
                                        </div>

                                    </div>

                                    <hr>
                                    <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-info">Enviar</button>
                                    <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                    <!-- /.card-footer -->
                                </form>
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php require('../../partials/footer.php'); ?>
</div>
<!-- ./wrapper -->
<?php require('../../partials/scripts.php'); ?>

<script>
    $('#documento').attr('type', 'text');
    $('#documento').attr('minlength', '6');
    $('#documento').attr('maxlength', '15');
    $('#rol').on('change' , function () {
       if($('#rol').val() != ' '){
           if($('#rol').val() == 'cliente' || $('#rol').val() == 'proveedor'){
                $('#div-hide').hide();
           }else{
               $('#div-hide').show();
           }
       }
    });

    $('#tipodocumento').on('change' , function () {
        if($('#tipodocumento').val() != ' '){
            if($('#tipodocumento').val() == 'Nit' ){
                $('#documento').attr('type', 'text');
                $('#documento').attr('minlength', '6');
                $('#documento').attr('maxlength', '15');
            }else{
                $('#documento').attr('type', 'text');
                $('#documento').attr('minlength', '6');
                $('#documento').attr('maxlength', '11');
            }
        }

    });

</script>

</body>

</html>
