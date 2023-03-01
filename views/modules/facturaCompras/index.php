<?php
require_once("../../../app/Controllers/FacturaComprasController.php");
require_once("../../partials/routes.php");
require_once("../../partials/check_login.php");
if($_SESSION['UserInSession']['rol'] != 'administrador'){
header("Location: ../../index.php?respuesta=error&mensaje=No tienes privilegios para acceder a esta zona");}

use App\Controllers\FacturaComprasController;
use App\Models\FacturaCompras;
use App\Models\GeneralFunctions;
use App\Enums\EstadoGeneral;


$nameModel = "FacturaCompra";
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION['frm'.$pluralModel] ?? NULL;

?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Gestión de <?= $pluralModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-responsive/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-buttons/css/buttons.bootstrap4.css">
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require_once("../../partials/navbar_customization.php"); ?>

    <?php require_once("../../partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Registro de Compras</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item active"><?= $pluralModel ?></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensajes de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-shopping-cart"></i> &nbsp; Gestionar <?= $pluralModel ?></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                            data-source="index.php" data-source-selector="#card-refresh-content"
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
                                <div class="row">
                                    <div class="col-auto mr-auto"></div>
                                    <div class="col-auto">
                                        <a role="button" href="create.php" class="btn btn-primary float-right"
                                           style="margin-right: 5px;">
                                            <i class="fas fa-plus"></i> Crear <?= $nameModel ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table id="tbl<?= $nameModel ?>" class="datatable table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Fecha </th>
                                                <th>Monto</th>
                                                <th>Proveedor</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $arrFacturaCompras = FacturaComprasController::getAll();
                                            /* @var $arrFacturaCompras FacturaCompras[] */
                                            foreach ($arrFacturaCompras as $facturaCompra) {
                                                ?>
                                                <tr>
                                                    <td><?= $facturaCompra->getId(); ?></td>
                                                    <td><?= $facturaCompra->getFecha()->format('d-m-y'); ?></td>
                                                    <td><?= GeneralFunctions::formatCurrency($facturaCompra->getMonto()); ?></td>
                                                    <td><?= $facturaCompra->getProveedor()->getNombre(); ?> </td>
                                                    <td><?= $facturaCompra->getEstado(); ?></td>
                                                    <td>
                                                        <a href="show.php?id=<?php echo $facturaCompra->getId(); ?>"
                                                           type="button" data-toggle="tooltip" title="Ver"
                                                           class="btn docs-tooltip btn-warning btn-xs"><i
                                                                    class="fa fa-eye"></i></a>

                                                        <a href="ShowDetalleCompra.php?id=<?php echo $facturaCompra->getId(); ?>"
                                                           style="background-color: blue"
                                                           type="button" data-toggle="tooltip" title="Detalle Compra"
                                                           class="btn docs-tooltip btn-success btn-xs">
                                                            <i class="fas fa-binoculars"></i></a>

                                                        <?php if ($facturaCompra->getEstado() == "Proceso") { ?>
                                                            <a href="create.php?id=<?php echo $facturaCompra->getId(); ?>"
                                                               type="button" data-toggle="tooltip" title="Retomar"
                                                               class="btn docs-tooltip btn-success btn-xs"><i
                                                                        class="fa fa-undo-alt"></i></a>
                                                            <a type="button"
                                                               href="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=cancel&Id=<?= $facturaCompra->getId(); ?>"
                                                               data-toggle="tooltip" title="Cancelar"
                                                               class="btn docs-tooltip btn-danger btn-xs"><i
                                                                        class="fa fa-times-circle"></i></a>

                                                            <a type="button"
                                                               onclick="return ConfirmacionFinalizar()"
                                                               href="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=finalize&Id=<?= $facturaCompra->getId(); ?>"
                                                               data-toggle="tooltip" title="Finalizar"
                                                               class="btn docs-tooltip btn-success btn-xs"><i
                                                                        class="fa fa-shopping-cart"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Fecha </th>
                                                <th>Monto</th>
                                                <th>Proveedor</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                Pie de Página.
                            </div>
                            <!-- /.card-footer-->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>


        </section>
        <script type="text/javascript">
            function ConfirmacionFinalizar()
            {
                var respuesta = confirm("Si finaliza factura no podrá volver hacer ningún cambio",)


                if (respuesta == true){
                    return true;
                }else
                {
                    return false
                }
            }
        </script>
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
