<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");


use App\Controllers\ProductosController;
use App\Controllers\PersonasController;
use App\Controllers\FacturaVentasController;
use App\Models\Personas;
use App\Models\DetalleVentas;
use App\Models\GeneralFunctions;
use Carbon\Carbon;


$nameModel = "FacturaVenta";
$nameForm = 'frmCreate'.$nameModel;
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>

<?php
$dataFacturaVenta = null;
if (!empty($_GET['id'])) {
    $dataFacturaVenta = FacturaVentasController::searchForID(["id" => $_GET['id']]);
    if ($dataFacturaVenta->getEstado() != "Proceso"){
        header('Location: index.php?respuesta=warning&mensaje=La venta ya ha finalizado');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title><?= $_ENV['TITLE_SITE'] ?> | Crear <?= $nameModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-responsive/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-buttons/css/buttons.bootstrap4.css">
    <script src=”https://unpkg.com/sweetalert/dist/sweetalert.min.js”> </script>
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("../../partials/navbar_customization.php"); ?>

    <?php require("../../partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Generar Mensaje de alerta -->
        <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear Una Nueva <?= $nameModel ?></h1>
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
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-shopping-cart"></i> &nbsp; Información de la
                                    <?= $nameModel ?></h3>
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
                                <form class="form-horizontal" method="post" id="<?= $nameForm ?>" name="<?= $nameForm ?>"
                                      action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=create">
                                    <div class="form-group row">
                                        <label for="cliente_id" class="col-sm-4 col-form-label">Cliente</label>
                                        <div class="col-sm-8">
                                            <?= PersonasController::selectPersona(
                                                array (
                                                    'id' => 'cliente_id',
                                                    'name' => 'cliente_id',
                                                    'defaultValue' => (!empty($dataFacturaVenta)) ? $dataFacturaVenta->getPersonasFacturaVentas()->getId() : '',
                                                    'class' => 'form-control select2bs4 select2-info',
                                                    'where' => "rol = 'cliente' and estado = 'activo'"
                                                )
                                            )
                                            ?>
                                            <span class="text-info"><a href="../personas/create.php">Crear Cliente</a></span>
                                        </div>
                                    </div>
                                    <?php
                                    if (!empty($dataFacturaVenta)) {
                                        ?>
                                        <div class="form-group row">
                                            <label for="id" class="col-sm-4 col-form-label">Código
                                                Factura</label>
                                            <div class="col-sm-8">
                                                <?= $dataFacturaVenta->getId() ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fecha" class="col-sm-4 col-form-label">Fecha
                                                Venta</label>
                                            <div class="col-sm-8">
                                                <?= $dataFacturaVenta->getFecha()->format('d-m-y') ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="numero_serie" class="col-sm-4 col-form-label">Monto</label>
                                            <div class="col-sm-8">
                                                <?= GeneralFunctions::formatCurrency($dataFacturaVenta->getMonto()) ?>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>  <?php
                                    $dataFacturaVenta = null;
                                    if (!empty($_GET['id'])) {
                                        $dataFacturaVenta = FacturaVentasController::searchForID(["id" => $_GET['id']]);
                                        if ($dataFacturaVenta->getEstado() == "Proceso"){
                                            ?>
                                            <a href="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=finalize&Id=<?= $_GET['id']?>" role="button" type="submit" class="btn btn-primary" onclick="return ConfirmacionFinalizar()">Finalizar</a>
                                            <?php
                                        }
                                    }else{
                                        ?>
                                        <button  type="submit"  class="btn btn-info" >Enviar</button>
                                        <?php
                                    }
                                    ?>
                                    <a href="index.php" role="button" class="btn btn-danger">Regresar</a>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <div class="col-md-8">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-parachute-box"></i> &nbsp; Detalle Venta</h3>
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
                                        <div class="col-auto">
                                            <a role="button" href="#" data-toggle="modal" data-target="#modal-add-producto"
                                               class="btn btn-primary float-right"
                                               style="margin-right: 5px;">
                                                <i class="fas fa-plus"></i> Añadir Producto
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col">
                                        <table id="tblDetalleProducto"
                                               class="datatable table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>

                                                <th>Total</th>
                                                <th>Act</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (!empty($dataFacturaVenta) and !empty($dataFacturaVenta->getId())) {
                                                $arrDetalleVentas = DetalleVentas::search("SELECT * FROM ferreteria.detalleventa WHERE facturaventa_id = ".$dataFacturaVenta->getId());
                                                if(count($arrDetalleVentas) > 0) {
                                                    /* @var $arrDetalleVentas DetalleVentas[] */
                                                    foreach ($arrDetalleVentas as $detalleVenta) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $detalleVenta->getId(); ?></td>
                                                            <td><?= $detalleVenta->getProductoVentas()->getNombre(); ?></td>
                                                            <td><?= $detalleVenta->getCantidad(); ?></td>

                                                            <td><?= GeneralFunctions::formatCurrency($detalleVenta->getTotalProducto()); ?></td>
                                                            <td>
                                                                <a type="button"
                                                                   href="../../../app/Controllers/MainController.php?controller=DetalleVentas&action=deleted&id=<?= $detalleVenta->getId(); ?>"
                                                                   data-toggle="tooltip" title="Eliminar"
                                                                   class="btn docs-tooltip btn-danger btn-xs"><i
                                                                            class="fa fa-times-circle"></i></a>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                }
                                            }?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>

                                                <th>Total</th>
                                                <th>Act</th>
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

    <div id="modals">
        <div class="modal fade" id="modal-add-producto">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Agregar Producto a Venta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="../../../app/Controllers/MainController.php?controller=DetalleVentas&action=create" method="post">
                        <div class="modal-body">
                            <input id="facturaventa_id" name="facturaventa_id" value="<?= !empty($dataFacturaVenta) ? $dataFacturaVenta->getId() : ''; ?>" hidden
                                   required="required" type="text">
                            <div class="form-group row">
                                <label for="producto_id" class="col-sm-4 col-form-label">Producto</label>
                                <div class="col-sm-8">
                                    <?= ProductosController::selectProducto(
                                        array (
                                            'id' => 'producto_id',
                                            'name' => 'producto_id',
                                            'defaultValue' => '',
                                            'class' => 'form-control select2bs4 select2-info',
                                            'where' => "estado = 'activo' and stock > 0"
                                        )
                                    )
                                    ?>
                                    <div id="divResultProducto">
                                        <span class="text-muted">Precio Base: </span> <span id="spPrecio"></span>,
                                        <span class="text-muted">Precio Venta: </span> <span id="spPrecioVenta"></span>,
                                        <span class="text-muted">Stock: </span> <span id="spStock"></span>.

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cantidad" class="col-sm-4 col-form-label">Cantidad</label>
                                <div class="col-sm-8">
                                    <input required type="number" min="1" class="form-control" step="1" id="cantidad" name="cantidad"
                                           placeholder="Ingrese la cantidad">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="precio_venta" class="col-sm-4 col-form-label">Precio Unitario</label>
                                <div class="col-sm-8">
                                    <input required readonly type="number" min="1" class="form-control" id="precio_venta" name="precio_venta"
                                           placeholder="0.0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="precio_venta" class="col-sm-4 col-form-label">Total Producto</label>
                                <div class="col-sm-8">
                                    <input required readonly type="number" min="1" class="form-control" id="total" name="total"
                                           placeholder="0.0">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>

    <?php require('../../partials/footer.php'); ?>
</div>
<!-- ./wrapper -->
<?php require('../../partials/scripts.php'); ?>
<!-- Scripts requeridos para las datatables -->
<?php require('../../partials/datatables_scripts.php'); ?>

<script>

    $(function () {

        $("#divResultProducto").hide();

        $('#producto_id').on('select2:select', function (e) {
            var dataSelect = e.params.data;
            var dataProducto = null;
            if(dataSelect.id !== ""){
                $.post("../../../app/Controllers/MainController.php?controller=Productos&action=searchForID",
                    {
                        id: dataSelect.id,
                        request: 'ajax'
                    }, "json"
                )
                .done(function( resultProducto ) {
                    dataProducto = resultProducto;
                })
                .fail(function(err) {
                    console.log( "Error al realizar la consulta"+err );
                })
                .always(function() {
                    updateDataProducto(dataProducto);
                });
            }else{
                updateDataProducto(dataProducto);
            }
        });

        function updateDataProducto(dataProducto){
            if(dataProducto !== null){
                $("#divResultProducto").slideDown();
                $("#spPrecio").html("$"+dataProducto.precio);
                $("#spPrecioVenta").html("$"+dataProducto.precio_venta);
                $("#spStock").html(dataProducto.stock+" Unidad(es)");
                $("#cantidad").attr("max",dataProducto.stock);
                $("#precio_venta").val(dataProducto.precio_venta);
                $("#valor").val(dataProducto.precio);
            }else{
                $("#divResultProducto").slideUp();
                $("#spPrecio").html("");
                $("#spPrecioVenta").html("");
                $("#spStock").html("");
                $("#cantidad").removeAttr("max").val('0');
                $("#precio_venta").val('0.0');
                $("#total").val('0.0');
                $("#valor").val('0.0');
            }
        }

        $( "#cantidad" ).on( "change keyup focusout", function() {
            $("#total").val($( "#cantidad" ).val() *  $("#precio_venta").val());
        });

    });
</script>
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

</body>
</html>
