<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');

use App\Enums\EstadoFactura;
use App\Models\Factura;
use App\Models\GeneralFunctions;
use App\Models\FacturaVentas;
use Carbon\Carbon;

class FacturaVentasController
{
    private array $dataFacturaVenta;

    public function __construct(array $_FORM)
    {
        $this->dataFacturaVenta = array();
        $this->dataFacturaVenta['id'] = $_FORM['id'] ?? NULL;
        $this->dataFacturaVenta['fecha'] = !empty($_FORM['fecha']) ? Carbon::parse($_FORM['fecha']) : new Carbon();
        $this->dataFacturaVenta['monto'] = $_FORM['monto'] ?? 0;
        $this->dataFacturaVenta['cliente_id'] = $_FORM['cliente_id'] ?? 0;
        $this->dataFacturaVenta['estado'] = $_FORM['estado'] ?? EstadoFactura::PROCESO;
    }

    public function create() {
        try {
            $Venta = new FacturaVentas($this->dataFacturaVenta);
            if ($Venta->insert()) {
                unset($_SESSION['frmFacturaVentas']);
                $Venta->Connect();
                $id = $Venta->getLastId('facturaventa');
                $Venta->Disconnect();
                header("Location: ../../views/modules/facturaVentas/create.php?id=" . $id . "");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/facturaVentas/create.php?respuesta=error");
        }
    }


    public function edit()
    {
        try {
            $Venta = new FacturaVentas($this->dataFacturaVenta);
            if($Venta->update()){
                unset($_SESSION['frmFacturaVentas']);
            }
            header("Location: ../../views/modules/facturaVentas/show                                                 .php?id=" . $Venta->getId() . "&respuesta=success&mensaje=Venta Actualizada");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/facturaVentas/edit.php?respuesta=error");
        }
    }


    static public function searchForID (array $data){
        try {
            $result = FacturaVentas::searchForId($data['id']);
            if (!empty($data['request']) and $data['request'] === 'ajax' and !empty($result)) {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result->jsonSerialize());
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function getAll (array $data = null){
        try {
            $result = FacturaVentas::getAll();
            if (!empty($data['request']) and $data['request'] === 'ajax') {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result);
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function cancel(){
        try {
            /* @var $ObjVenta FacturaVentas */
            $ObjVenta = FacturaVentas::searchForId($_GET['Id']);
            $ObjVenta->setEstado("Anulada");
            if($ObjVenta->update()){
                $ObjVenta->cancelFactura();
                header("Location: ../../views/modules/facturaVentas/index.php");
            }else{
                header("Location: ../../views/modules/facturaVentas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/facturaVentas/index.php?respuesta=error");
        }
    }

    static public function selectVentas (array $params = [] ){

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "facturaventas_id";
        $params['name'] = $params['name'] ?? "facturaventas_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrVentas = array();
        if($params['where'] != ""){
            $base = "SELECT * FROM facturaventa WHERE ";
            $arrVentas = FacturaVentas::search($base.$params['where']);
        }else{
            $arrVentas = FacturaVentas::getAll();
        }

        $htmlSelect = "<select ".(($params['isMultiple']) ? "multiple" : "")." ".(($params['isRequired']) ? "required" : "")." id= '".$params['id']."' name='".$params['name']."' class='".$params['class']."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(is_array($arrVentas) && count($arrVentas) > 0){
            /* @var $arrVentas FacturaVentas[] */
            foreach ($arrVentas as $ventas)
                if (!FacturaVentasController::FacturaventaIsInArray($ventas->getId(),$params['arrExcluir']))
                    $htmlSelect .= "<option ".(($ventas != "") ? (($params['defaultValue'] == $ventas->getId()) ? "selected" : "" ) : "")." value='".$ventas->getId()."'>".$ventas->getFecha()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function FacturaventaIsInArray($idVenta, $ArrVentas){
        if(count($ArrVentas) > 0){
            foreach ($ArrVentas as $Venta){
                if($Venta->getId() == $idVenta){
                    return true;
                }
            }
        }
        return false;
    }

    static public function finalize(){
        try {

            $ObjVenta = FacturaVentas::searchForId($_GET['Id']);
            $ObjVenta->setEstado("Finalizada");
            if($ObjVenta->update()){
                header("Location: ../../views/modules/facturaVentas/index.php?respuesta=success&mensaje=Factura Finalizada Correctamente");
            }else{
                header("Location: ../../views/modules/facturaVentas/index.php?respuesta=error&mensaje=Error al Finalizar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/facturaVentas/index.php?respuesta=error");
        }
    }



}