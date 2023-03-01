<?php

namespace App\Controllers;
require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Clasificaciones;

class ClasificacionesController

{
    private array $dataClasificacion;

    public function __construct(array $_FORM)
    {
        $this->dataClasificacion = array();
        $this->dataClasificacion['id'] = $_FORM['id'] ?? NULL;
        $this->dataClasificacion['nombre'] = $_FORM['nombre'] ?? NULL;
    }
    public function create($withFiles = null) {
        try {
            if (!empty($this->dataClasificacion['nombre']) && !Clasificaciones::clasificacionRegistrada($this->dataClasificacion['nombre'])) {
                $Clasificacion = new Clasificaciones($this->dataClasificacion);
                if ($Clasificacion->insert()) {
                    unset($_SESSION['frmClasificaciones']);
                    header("Location: ../../views/modules/clasificaciones/index.php?respuesta=success&mensaje=Clasificacion Registrada");
                }
            } else {
                header("Location: ../../views/modules/clasificaciones/create.php?respuesta=error&mensaje=Clasificacion ya registrada");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    public function edit($withFiles = null)
    {
        try {

            $clasificacion = new Clasificaciones($this->dataClasificacion);
            if($clasificacion->update()){
                unset($_SESSION['frmClasificaciones']);
            }
            header("Location: ../../views/modules/clasificaciones/show.php?id=" . $clasificacion->getId() . "&respuesta=success&mensaje=Clasificacion Actualizada");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    static public function searchForID(array $data)
    {
        try {
            $result = Clasificaciones::searchForId($data['id']);
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
    static public function getAll(array $data = null)
    {
        try {
            $result = Clasificaciones::getAll();
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
    static public function activate(int $id)
    {
        try {
            $ObjClasificacion = Clasificaciones::searchForId($id);
            if ($ObjClasificacion->update()) {
                header("Location: ../../views/modules/clasificaciones/index.php?respuesta=success&mensaje=Registro actualizado");
            } else {
                header("Location: ../../views/modules/clasificaciones/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    static public function inactivate(int $id)
    {
        try {
            $ObjClasificacion = Clasificaciones::searchForId($id);
            if ($ObjClasificacion->update()) {
                header("Location: ../../views/modules/clasificaciones/index.php?respuesta=success&mensaje=Registro actualizado");
            } else {
                header("Location: ../../views/modules/clasificaciones/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectClasificacion(array $params = []) {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "clasificacion_id";
        $params['name'] = $params['name'] ?? "clasificacion_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrClasificaciones = array();
        if ($params['where'] != "") { //Si hay filtro
            $base = "SELECT * FROM clasificacion WHERE ";
            $arrClasificaciones = Clasificaciones::search($base . ' ' . $params['where']);
        } else {
            $arrClasificaciones = Clasificaciones::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrClasificaciones) && count($arrClasificaciones) > 0) {
            /* @var $arrClasificaciones Clasificaciones[] */
            foreach ($arrClasificaciones as $clasificacion)
                if (!ClasificacionesController::clasificacionIsInArray($clasificacion->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($clasificacion != "") ? (($params['defaultValue'] == $clasificacion->getId()) ? "selected" : "") : "") . " value='" . $clasificacion->getId() . "'>" . $clasificacion->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }
    private static function clasificacionIsInArray($idClasificacion, $ArrClasificaciones)
    {
        if (count($ArrClasificaciones) > 0) {
            foreach ($ArrClasificaciones as $Clasificacion) {
                if ($Clasificacion->getId() == $idClasificacion) {
                    return true;
                }
            }
        }
        return false;
    }
}