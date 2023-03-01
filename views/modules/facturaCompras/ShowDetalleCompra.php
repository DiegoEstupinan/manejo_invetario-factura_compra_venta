<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");
if($_SESSION['UserInSession']['rol'] != 'administrador'){
    header("Location: ../../index.php?respuesta=error&mensaje=No tienes privilegios para acceder a esta zona");}

use App\Controllers\ProductosController;
use App\Controllers\PersonasController;
use App\Controllers\FacturaComprasController;
use App\Models\DetalleCompras;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "FacturaCompra";
$nameForm = 'frmCreate'.$nameModel;
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>


<?php
$datacompra = null;
if (!empty($_GET['id'])) {
    $datacompra = FacturaComprasController::searchForID(["id" => $_GET['id']]);
    if ($datacompra->getEstado() != "Proceso" && $datacompra->getEstado() != "Finalizada"){
        header('Location: index.php?respuesta=warning&mensaje=La compra ya ha finalizado');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Crear <?= $nameModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-responsive/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-buttons/css/buttons.bootstrap4.css">
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("../../partials/navbar_customization.php"); ?>

    <?php require("../../partials/sliderbar_main_menu.php"); ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Información de la <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Ver</li>
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
                        <div class="card card-green">
                            <?php if (!empty($_GET["id"]) && isset($_GET["id"])) {
                                $DataCompras = FacturaComprasController::searchForID(["id" => $_GET["id"]]);
                                /* @var $DataCompras Compras */
                                if (!empty($DataCompras)) {
                                    ?>
                                    <div class="card-header">

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                                    data-source="show.php" data-source-selector="#card-refresh-content"
                                                    data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                        class="fas fa-expand"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                    data-toggle="tooltip" title="Collapse">
                                                <i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove"
                                                    data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p>


                                        <hr>
                                        <strong><i class="fas fa-user-ninja mr-1"></i> Cliente</strong>
                                        <p class="text-muted"><?= $DataCompras->getProveedor()->getNombre() . " " . $DataCompras->getProveedor()->getApellido() ?></p>

                                        <strong><i class="fas fa-money-bill mr-1"></i> Monto</strong>
                                        <p class="text-muted"><?= GeneralFunctions::formatCurrency($DataCompras->getMonto()); ?></p>
                                        <hr>
                                        <strong><i class="fas fa-cog mr-1"></i> Estado</strong>
                                        <p class="text-muted"><?= $DataCompras->getEstado(); ?></p>
                                        </p>


                                <?php } else { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        No se encontró ningún registro con estos parámetros de búsqueda <?= ($_GET['mensaje']) ?? "" ?>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Generar Mensaje de alerta -->
        <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">

                    <div class="col-md-8">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-parachute-box"></i> &nbsp; Detalle Compra</h3>
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

                            <div class="card-body">
                                <?php if (!empty($_GET['id'])) { ?>
                                    <div class="row">
                                        <div class="col-auto mr-auto"></div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col">
                                        <table id="tblDetalleProducto"
                                               class="datatable table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Total</th>
                                                <th>Monto = <p><?= GeneralFunctions::formatCurrency($datacompra->getMonto()); ?></p></th>




                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (!empty($datacompra) and !empty($datacompra->getId())) {
                                                $arrDetalleCompras = DetalleCompras::search("SELECT * FROM ferreteria.detallecompra WHERE compra_id = ".$datacompra->getId());
                                                if(count($arrDetalleCompras) > 0) {
                                                    /* @var $arrDetalleCompras DetalleCompras[] */
                                                    foreach ($arrDetalleCompras as $detalleCompra) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $detalleCompra->getId(); ?></td>
                                                            <td><?= $datacompra->getFecha()->format('d-m-y'); ?></td>
                                                            <td><?= $datacompra->getProveedor()->getNombre(); ?> </td> </td>
                                                            <td><?= $detalleCompra->getProductoCompra()->getNombre(); ?></td>
                                                            <td><?= $detalleCompra->getCantidad(); ?></td>
                                                            <td><?= GeneralFunctions::formatCurrency($detalleCompra->getTotalProducto()); ?></td>
                                                            <td></td>

                                                        </tr>
                                                    <?php }
                                                }
                                            }?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Total</th>
                                                <th>Monto</th>


                                            </tr>
                                            </tfoot>
                                        </table>



                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->



    <?php require('../../partials/footer.php'); ?>
</div>
<!-- ./wrapper -->
<?php require('../../partials/scripts.php'); ?>
<!-- Scripts requeridos para las datatables -->
<?php require('../../partials/datatables_scripts.php'); ?>

</body>
</html>