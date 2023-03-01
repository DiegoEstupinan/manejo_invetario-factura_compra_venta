<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');

use App\Models\FacturaVentas;
use App\Models\GeneralFunctions;
use App\Models\FacturaCompras  ;
use Carbon\Carbon;

class FacturaComprasController
{
    private array $dataFacturaCompra;

    public function __construct(array $_FORM)
    {
        $this->dataFacturaCompra = array();
        $this->dataFacturaCompra['id'] = $_FORM['id'] ?? NULL;
        $this->dataFacturaCompra['fecha'] = !empty($_FORM['fecha']) ? Carbon::parse($_FORM['fecha']) : new Carbon();
        $this->dataFacturaCompra['monto'] = $_FORM['monto'] ?? 0;
        $this->dataFacturaCompra['proveedor_id'] = $_FORM['proveedor_id'] ?? 0;
        $this->dataFacturaCompra['estado'] = $_FORM['estado'] ?? 'Proceso';

    }
    public function create() {
        try {
            $Compra = new FacturaCompras($this->dataFacturaCompra);
            if ($Compra->insert()) {
                unset($_SESSION['frmFacturaCompras']);
                $Compra->Connect();
                $id = $Compra->getLastId('facturacompra');
                $Compra->Disconnect();
                header("Location: ../../views/modules/facturaCompras/create.php?id=" . $id . "");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/facturaCompras/create.php?respuesta=error");
        }
    }
    public function edit()
    {
        try {
            $Compra = new FacturaCompras($this->dataFacturaCompra);
            if($Compra->update()){
                unset($_SESSION['frmFacturaCompra']);
            }
            header("Location: ../../views/modules/facturaCompras/show.php?id=" . $Compra->getId() . "&respuesta=success&mensaje=Compra Actualizada");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/facturaCompras/edit.php?respuesta=error");
        }
    }
    static public function searchForID (array $data){
        try {
            $result = FacturaCompras::searchForId($data['id']);
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
            $result = FacturaCompras::getAll();
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
            /* @var $ObjCompra FacturaCompras */
            $ObjCompra = FacturaCompras::searchForId($_GET['Id']);
            $ObjCompra->setEstado("Anulada");
            if($ObjCompra->update()){
                $ObjCompra->cancelFactura();
                header("Location: ../../views/modules/facturaCompras/index.php");
            }else{
                header("Location: ../../views/modules/facturaCompras/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/facturaCompras/index.php?respuesta=error");
        }
    }
    static public function selectCompras (array $params = [] ){

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "facturacompras_id";
        $params['name'] = $params['name'] ?? "facturacompras_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrCompras = array();
        if($params['where'] != ""){
            $base = "SELECT * FROM facturacompra WHERE ";
            $arrCompras = FacturaCompras::search($base.$params['where']);
        }else{
            $arrCompras = FacturaCompras::getAll();
        }

        $htmlSelect = "<select ".(($params['isMultiple']) ? "multiple" : "")." ".(($params['isRequired']) ? "required" : "")." id= '".$params['id']."' name='".$params['name']."' class='".$params['class']."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(is_array($arrCompras) && count($arrCompras) > 0){
            /* @var $arrCompras FacturaCompras[] */
            foreach ($arrCompras as $compras)
                if (!FacturaComprasController::FacturacompraIsInArray($compras->getId(),$params['arrExcluir']))
                    $htmlSelect .= "<option ".(($compras != "") ? (($params['defaultValue'] == $compras->getId()) ? "selected" : "" ) : "")." value='".$compras->getId()."'>".$compras->getFecha()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }
    public static function FacturacompraIsInArray($idCompra, $ArrCompras){
        if(count($ArrCompras) > 0){
            foreach ($ArrCompras as $Compra){
                if($Compra->getId() == $idCompra){
                    return true;
                }
            }
        }
        return false;
    }
    static public function finalize(){
        try {
            $ObjCompra = FacturaCompras::searchForId($_GET['Id']);
            $ObjCompra->setEstado("Finalizada");
            if($ObjCompra->update()){
                header("Location: ../../views/modules/facturaCompras/index.php?respuesta=success&mensaje=Factura Finalizada Correctamente");
            }else{
                header("Location: ../../views/modules/facturaCompras/index.php?respuesta=error&mensaje=Error al Finalizar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/facturaCompras/index.php?respuesta=error");
        }
    }


}