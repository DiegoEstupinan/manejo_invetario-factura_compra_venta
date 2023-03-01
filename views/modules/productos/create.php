<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");

if($_SESSION['UserInSession']['rol'] != 'administrador'){
    header("Location: ../../index.php?respuesta=error&mensaje=No tienes privilegios para acceder a esta zona");
}

use App\Controllers\CategoriasController;
use App\Controllers\ClasificacionesController;
use App\Controllers\MarcasController;
use App\Controllers\MaterialesController;
use App\Controllers\MedidasController;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "Producto";
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
                        <h1>Crear un Nuevo <?= $nameModel ?></h1>
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
                                <h3 class="card-title"><i class="fas fa-box"></i> &nbsp; Información del <?= $nameModel ?></h3>
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
                            <!-- form start -->
                            <div class="card-body">
                                <form class="form-horizontal" method="post" id="<?= $nameForm ?>" name="<?= $nameForm ?>"
                                      action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=create">
                                    <div class="form-group row">
                                        <label for="nombre" class="col-sm-2 col-form-label">Nombre <font color='red'>*</font></label>
                                        <div class="col-sm-10">
                                            <input required minlength="2" maxlength="70" type="text" class="form-control" id="nombre" name="nombre"
                                                   placeholder="Ingrese el nombre" value="<?= $frmSession['nombre'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="precio" class="col-sm-2 col-form-label">Precio <font color='red'>*</font></label>
                                        <div class="col-sm-10">
                                            <input required type="number" min="50" class="form-control" id="precio" name="precio"
                                                   placeholder="Ingrese el precio" value="<?= $frmSession['precio'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="porcentaje_ganancia" class="col-sm-2 col-form-label">Porcentaje de Ganancia <font color='red'>*</font></label>
                                        <div class="col-sm-10">
                                            <input required type="number" min="0" max="100" step="0.1" class="form-control" id="porcentaje_ganancia" name="porcentaje_ganancia"
                                                   placeholder="Ingrese el porcentaje de ganancia" value="<?= $frmSession['porcentaje_ganancia'] ?? '' ?>">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="stock" class="col-sm-2 col-form-label">Stock <font color='red'>*</font></label>
                                        <div class="col-sm-10">
                                            <input required type="number" min="0" minlength="6" class="form-control" id="stock"
                                                   name="stock" placeholder="Ingrese el stock" value="<?= $frmSession['stock'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="clasificacion_id" class="col-sm-2 col-form-label">Clasificación <font color='red'>*</font></label>
                                        <div class="col-sm-10 ">
                                            <?= ClasificacionesController::selectClasificacion(
                                                array(
                                                    'id' => 'clasificacion_id',
                                                    'name' => 'clasificacion_id',
                                                    'defaultValue' => (!empty($frmSession['clasificacion_id'])) ? $frmSession['clasificacion_id'] : '',
                                                    'class' => 'form-control select2bs4 select2-info',

                                                )
                                            );
                                            ?>
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
                                    <div class="form-group row">
                                        <label for="medida_id" class="col-sm-2 col-form-label">Medida</label>
                                        <div class="col-sm-10 ">
                                            <?= MedidasController::selectMedida(
                                                array(
                                                    'id' => 'medida_id',
                                                    'name' => 'medida_id',
                                                    'defaultValue' => (!empty($frmSession['medida_id'])) ? $frmSession['medida_id'] : '',
                                                    'class' => 'form-control select2bs4 select2-info',
                                                    'isRequired' => false
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label for="material_id" class="col-sm-2 col-form-label">Material</label>
                                        <div class="col-sm-10 ">
                                            <?= MaterialesController::selectMaterial(
                                                array(
                                                    'id' => 'material_id',
                                                    'name' => 'material_id',
                                                    'defaultValue' => (!empty($frmSession['material_id'])) ? $frmSession['material_id'] : '',
                                                    'class' => 'form-control select2bs4 select2-info',
                                                    'isRequired' => false
                                                )

                                            );
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label  for="marca_id" class="col-sm-2 col-form-label">Marca <font color='red'>*</font></label>
                                        <div class="col-sm-10 ">
                                            <?= MarcasController::selectMarca(
                                                array(
                                                    'id' => 'marca_id',
                                                    'name' => 'marca_id',
                                                    'defaultValue' => (!empty($frmSession['marca_id'])) ? $frmSession['marca_id'] : '',
                                                    'class' => 'form-control select2bs4 select2-info',

                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-info">Enviar</button>
                                    <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
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
</body>
</html>