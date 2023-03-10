<?php

namespace App\Controllers;
require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Productos;

class ProductosController
{
    private array $dataProducto;

    public function __construct(array $_FORM)
    {
        $this->dataProducto = array();
        $this->dataProducto['id'] = $_FORM['id'] ?? NULL;
        $this->dataProducto['nombre'] = $_FORM['nombre'] ?? '';
        $this->dataProducto['stock'] = $_FORM['stock'] ?? 0.0;
        $this->dataProducto['precio'] = $_FORM['precio'] ?? 0.0;
        $this->dataProducto['porcentaje_ganancia'] = $_FORM['porcentaje_ganancia'] ?? 0.0;
        $this->dataProducto['clasificacion_id'] = $_FORM['clasificacion_id'] ?? 0;
        $this->dataProducto['estado'] = $_FORM['estado'] ?? 'Activo';
        $this->dataProducto['medida_id'] = (!empty($_FORM['medida_id'])) ? $_FORM['medida_id'] : null ;
        $this->dataProducto['marca_id'] = $_FORM['marca_id'] ?? 0;
        $this->dataProducto['material_id'] = (!empty($_FORM['material_id'])) ? $_FORM['material_id'] : null;
    }
    public function create() {
        try {
            if (!empty($this->dataProducto['nombre']) && !Productos::productoRegistrado($this->dataProducto['nombre'])) {
                $Producto = new Productos ($this->dataProducto);
                if ($Producto->insert()) {
                    unset($_SESSION['frmProductos']);
                    header("Location: ../../views/modules/productos/index.php?respuesta=success&mensaje=Producto Registrado!");
                }
            } else {
                header("Location: ../../views/modules/productos/create.php?respuesta=error&mensaje=Producto ya registrado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    public function edit()
    {
        try {
            $producto = new Productos($this->dataProducto);
            if($producto->update()){
                unset($_SESSION['frmProductos']);
            }

            header("Location: ../../views/modules/productos/show.php?id=" . $producto->getId() . "&respuesta=success&mensaje=Producto Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    static public function searchForID (array $data){
        try {
            $result = Productos::searchForId($data['id']);
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
            $result = Productos::getAll();
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
    static public function activate (int $id){
        try {
            $ObjProducto = Productos::searchForId($id);
            $ObjProducto->setEstado("activo");
            if($ObjProducto->update()){
                header("Location: ../../views/modules/productos/index.php");
            }else{
                header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    static public function inactivate (int $id){
        try {
            $ObjProducto = Productos::searchForId($id);
            $ObjProducto->setEstado("inactivo");
            if($ObjProducto->update()){
                header("Location: ../../views/modules/productos/index.php");
            }else{
                header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectProducto (array $params = []){

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "producto_id";
        $params['name'] = $params['name'] ?? "producto_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrProductos = array();
        if($params['where'] != ""){
            $base = "SELECT * FROM producto WHERE ";
            $arrProductos = Productos::search($base.$params['where']);
        }else{
            $arrProductos = Productos::getAll();
        }

        $htmlSelect = "<select ".(($params['isMultiple']) ? "multiple" : "")." ".(($params['isRequired']) ? "required" : "")." id= '".$params['id']."' name='".$params['name']."' class='".$params['class']."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(is_array($arrProductos) && count($arrProductos) > 0){
            /* @var $arrProductos Productos[] */
            foreach ($arrProductos as $producto)
                if (!ProductosController::productoIsInArray($producto->getId(),$params['arrExcluir']))
                    $htmlSelect .= "<option ".(($producto != "") ? (($params['defaultValue'] == $producto->getId()) ? "selected" : "" ) : "")." value='".$producto->getId()."'>".$producto->getNombre()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }
    public static function productoIsInArray($idProducto, $ArrProductos){
        if(count($ArrProductos) > 0){
            foreach ($ArrProductos as $Producto){
                if($Producto->getId() == $idProducto){
                    return true;
                }
            }
        }
        return false;
    }
}