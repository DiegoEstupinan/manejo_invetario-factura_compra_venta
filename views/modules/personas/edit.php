<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");
require("../../../app/Controllers/PersonasController.php");

if($_SESSION['UserInSession']['rol'] != 'administrador'){
    header("Location: ../../index.php?respuesta=error&mensaje=No tienes privilegios para acceder a esta zona");
}

use App\Controllers\DepartamentosController;
use App\Controllers\MunicipiosController;
use App\Controllers\PersonasController;
use App\Controllers\UsuariosController;
use App\Models\GeneralFunctions;
use App\Models\Usuarios;
use Carbon\Carbon;

$nameModel = "Persona";
$nameForm = 'frmEdit'.$nameModel;
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE']  ?> | Editar <?= $nameModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
                        <h1>Editar <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensajes de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <?= (empty($_GET['id'])) ? GeneralFunctions::getAlertDialog('error', 'Faltan Criterios de Búsqueda') : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i>&nbsp; Información del <?= $nameModel ?></h3>
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
                            <?php if (!empty($_GET["id"]) && isset($_GET["id"])) { ?>
                                <p>
                                <?php

                                $dataPersona = PersonasController::searchForID(["id" => $_GET["id"]]);
                                /* @var $dataPersona Personas */
                                if (!empty($dataPersona)) {
                                    ?>
                                    <!-- form start -->
                                    <div class="card-body">
                                        <form class="form-horizontal" enctype="multipart/form-data" method="post" id="<?= $nameForm ?>"
                                              name="<?= $nameForm ?>"
                                              action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=edit">
                                            <input id="id" name="id" value="<?= $dataPersona->getId(); ?>" hidden
                                                   required="required" type="text">
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <div class="form-group row">
                                                        <label for="rol" class="col-sm-2 col-form-label">Rol</label>
                                                        <div class="col-sm-10">
                                                            <select required id="rol" name="rol" class="custom-select">
                                                                <option <?= ($dataPersona->getRol() == "administrador") ? "selected" : ""; ?> value="administrador">Administrador</option>
                                                                <option <?= ($dataPersona->getRol() == "empleado") ? "selected" : ""; ?> value="empleado">Empleado</option>
                                                                <option <?= ($dataPersona->getRol() == "cliente") ? "selected" : ""; ?> value="cliente">Cliente</option>
                                                                <option <?= ($dataPersona->getRol() == "proveedor") ? "selected" : ""; ?> value="proveedor">Proveedor</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="nombre" class="col-sm-2 col-form-label">Nombres</label>
                                                        <div class="col-sm-10">
                                                            <input required minlength="1" maxlength="50"type="text" class="form-control" id="nombre"
                                                                   name="nombre" value="<?= $dataPersona->getNombre(); ?>"
                                                                   placeholder="Ingrese sus nombres">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="apellido" class="col-sm-2 col-form-label">Apellidos</label>
                                                        <div class="col-sm-10">
                                                            <input required  minlength="1" maxlength="50" type="text" class="form-control" id="apellido"
                                                                   name="apellido" value="<?= $dataPersona->getApellido(); ?>"
                                                                   placeholder="Ingrese sus apellidos">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="tipodocumento" class="col-sm-2 col-form-label">Tipo
                                                            Documento</label>
                                                        <div class="col-sm-10">
                                                            <select id="tipodocumento" name="tipodocumento"
                                                                    class="custom-select">
                                                                <option <?= ($dataPersona->getTipoDocumento() == "Cedula") ? "selected" : ""; ?>
                                                                        value="Cedula">Cedula de Ciudadania
                                                                </option>
                                                                <option <?= ($dataPersona->getTipoDocumento() == "Nit") ? "selected" : ""; ?>
                                                                        value="Nit">Nit
                                                                </option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="documento" class="col-sm-2 col-form-label">Documento</label>
                                                        <div class="col-sm-10">
                                                            <input required minlength="6" maxlength="15" type="text"  class="form-control"
                                                                   id="documento" name="documento"
                                                                   value="<?= $dataPersona->getDocumento(); ?>"
                                                                   placeholder="Ingrese su documento">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="telefono" class="col-sm-2 col-form-label">Teléfono</label>
                                                        <div class="col-sm-10">
                                                            <input required minlength="9"  maxlength="11"  type="text" minlength="6" class="form-control"
                                                                   id="telefono" name="telefono"
                                                                   value="<?= $dataPersona->getTelefono(); ?>"
                                                                   placeholder="Ingrese su télefono">
                                                        </div>
                                                    </div>


                                                    <?php if ($_SESSION['UserInSession']['rol'] == 'administrador'){ ?>
                                                        <div class="form-group row">
                                                            <label for="user" class="col-sm-2 col-form-label">correo</label>
                                                            <div class="col-sm-10">
                                                                <input  minlength="5" maxlength="50" type="text" class="form-control" id="correo" name="correo" value="<?= $dataPersona->getCorreo(); ?>" placeholder="Ingrese su Correo">
                                                            </div>
                                                        </div>

                                                        <div id="div-hide" class="form-group row">
                                                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                                                            <div class="col-sm-10">
                                                                <input type="password" class="form-control" id="contrasena" name="contrasena" value="" placeholder="Ingrese su Contraseña"minlength="6"  maxlength="50">
                                                            </div>
                                                        </div>



                                                        <div class="form-group row">
                                                            <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                                            <div class="col-sm-10">
                                                                <select required id="estado" name="estado" class="custom-select">
                                                                    <option <?= ($dataPersona->getEstado() == "activo") ? "selected" : ""; ?> value="activo">Activo</option>
                                                                    <option <?= ($dataPersona->getEstado() == "inactivo") ? "selected" : ""; ?> value="inactivo">Inactivo</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-info">Enviar</button>
                                                    <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                                </div>
                                                <hr>


                                        </form>
                                    </div>
                                    <!-- /.card-body -->

                                <?php } else { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        No se encontró ningún registro con estos parámetros de búsqueda <?= ($_GET['mensaje']) ?? "" ?>
                                    </div>
                                <?php } ?>
                                </p>
                            <?php } ?>
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
<script>
    if($('#rol').val() == 'cliente' || $('#rol').val() == 'proveedor'){
        $('#div-hide').hide();
    }else{
        $('#div-hide').show();
    }
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
                $('#documento').attr('maxlength', '15');
            }
        }
    });
</script>
<!-- ./wrapper -->

</body>
</html>
